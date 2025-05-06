<template>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-success">Random quote</h2>
        </div>

        <div
            v-if="loading"
            class="mt-3 alert alert-success shadow-sm"
            role="alert"
        >
            Loading random quote...
        </div>

        <div v-else-if="quote" class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">Quote of the day</h5>
                <blockquote class="blockquote mb-0">
                    <p class="card-text">
                        {{ quote.quote }}
                    </p>
                    <footer class="blockquote-footer mt-2">
                        {{ quote.author }}
                    </footer>
                </blockquote>
            </div>
        </div>
        <div v-else class="mt-3 alert alert-warning" role="alert">
            Could not load the random quote.
            <span v-if="retryAfter > 0">
                Request limit exceeded. Retrying in {{ retryAfter }} seconds...
            </span>
            <span v-else-if="error && error.message">
                {{ error.message }}
            </span>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";

// State
const quote = ref(null);
const loading = ref(true);
const error = ref(null);
const retryAfter = ref(0);

// Utility function for handling fetch errors with retry logic
const handleFetchError = async (response, retryFn) => {
    const errorData = await response.json();
    console.error("API Error:", errorData);
    error.value = errorData;
    if (errorData.retry_after) {
        retryAfter.value = errorData.retry_after;
        let retryInterval = setInterval(() => {
            retryAfter.value--;
            if (retryAfter.value <= 0) {
                clearInterval(retryInterval);
                retryFn();
            }
        }, 1000);
    }
    throw new Error(`HTTP error! status: ${response.status}`);
};

// Function to fetch a random quote from the API
const fetchRandomQuote = async () => {
    loading.value = true;
    error.value = null;
    retryAfter.value = 0;
    clearInterval(null); // Clear any existing interval

    try {
        const response = await fetch("/api/quotes/random");
        if (!response.ok) {
            await handleFetchError(response, fetchRandomQuote);
            return;
        }
        quote.value = await response.json();
    } catch (err) {
        console.error("Error fetching random quote:", err);
        error.value = { message: "Failed to fetch random quote." };
    } finally {
        loading.value = false;
    }
};

// Fetch random quote on component mount
onMounted(fetchRandomQuote);
</script>
