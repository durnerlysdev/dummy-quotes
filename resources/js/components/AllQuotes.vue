<template>
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div
                class="card-header bg-primary text-white d-flex justify-content-between align-items-center"
            >
                <h2>All Quotes</h2>
            </div>
            <div class="card-body">
                <div v-if="loading" class="alert alert-info" role="alert">
                    Loading quotes...
                </div>
                <table
                    v-else-if="allQuotes.length > 0"
                    class="table table-striped"
                >
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Author</th>
                            <th>Quote</th>
                        </tr>
                    </thead>
                    <tbody style="height: 350px; overflow-y: auto">
                        <tr v-for="quote in displayedQuotes" :key="quote.id">
                            <td>{{ quote.id }}</td>
                            <td>{{ quote.author }}</td>
                            <td>{{ quote.quote }}</td>
                        </tr>
                    </tbody>
                </table>

                <div v-else class="mt-3 alert alert-warning" role="alert">
                    Could not load the random quotes.

                    <span v-if="retryAfter > 0">
                        Request limit exceeded. Retrying in
                        {{ retryAfter }} seconds...
                    </span>
                    <span v-else-if="error && error.message">
                        {{ error.message }}
                    </span>
                </div>
            </div>
            <nav
                v-if="totalPages > 1"
                aria-label="Quotes pagination"
                class="card-footer py-3"
            >
                <ul class="pagination justify-content-center">
                    <li
                        class="page-item"
                        :class="{ disabled: currentPage === 1 }"
                    >
                        <button class="page-link" @click="prevPageGroup">
                            Previous
                        </button>
                    </li>

                    <li v-if="startPage > 1" class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>

                    <li
                        v-for="page in visiblePageNumbers"
                        :key="page"
                        class="page-item"
                        :class="{ active: currentPage === page }"
                    >
                        <button class="page-link" @click="goToPage(page)">
                            {{ page }}
                        </button>
                    </li>

                    <li v-if="endPage < totalPages" class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>

                    <li
                        class="page-item"
                        :class="{ disabled: currentPage === totalPages }"
                    >
                        <button class="page-link" @click="nextPageGroup">
                            Next
                        </button>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";

// State
const loading = ref(true);
const allQuotes = ref([]);
const retryAfter = ref(0);
const error = ref(null);
const currentPage = ref(1);
const perPage = ref(5);
const totalQuotes = ref(0);
const pagesToShow = ref(10);
const retryInterval = null;

// Computed properties for pagination
const totalPages = computed(() => Math.ceil(totalQuotes.value / perPage.value));
const startPage = computed(() =>
    Math.max(1, Math.ceil(currentPage.value - pagesToShow.value / 2))
);
const endPage = computed(() =>
    Math.min(totalPages.value, startPage.value + pagesToShow.value - 1)
);
const visiblePageNumbers = computed(() => {
    const pages = [];
    for (let i = startPage.value; i <= endPage.value; i++) {
        pages.push(i);
    }
    return pages;
});
const displayedQuotes = computed(() => {
    const startIndex = (currentPage.value - 1) * perPage.value;
    const endIndex = startIndex + perPage.value;
    return allQuotes.value.slice(startIndex, endIndex);
});

// Utility function for handling fetch errors with retry logic
const handleFetchError = async (response) => {
    const errorData = await response.json();
    console.error("API Error:", errorData);
    error.value = errorData;
    if (errorData.retry_after) {
        retryAfter.value = errorData.retry_after;
        let retryInterval = setInterval(() => {
            retryAfter.value--;
            if (retryAfter.value <= 0) {
                clearInterval(retryInterval);
                fetchQuotes();
            }
        }, 1000);
    }
    throw new Error(`HTTP error! status: ${response.status}`);
};

// Function to fetch quotes from the API
const fetchQuotes = async () => {
    loading.value = true;
    retryAfter.value = 0;
    error.value = null;
    clearInterval(retryInterval);
    try {
        const response = await fetch("/api/quotes");
        if (!response.ok) {
            await handleFetchError(response);
            return;
        }
        const data = await response.json();
        allQuotes.value = data.quotes;
        totalQuotes.value = data.total;
    } catch (err) {
        console.error("Error fetching quotes:", err);
        allQuotes.value = [];
        totalQuotes.value = 0;
    } finally {
        loading.value = false;
    }
};

// Pagination control functions
const goToPage = (page) => {
    currentPage.value = page;
};

const nextPageGroup = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
    }
};

const prevPageGroup = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
};

// Fetch quotes on component mount
onMounted(() => {
    fetchQuotes();
});
</script>
