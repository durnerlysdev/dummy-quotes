<template>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-info">Search quote by ID</h2>
            <form
                @submit.prevent="fetchQuote"
                class="d-inline-flex align-items-center"
            >
                <div class="ms-2">
                    <label for="quoteId" class="form-label visually-hidden"
                        >ID:</label
                    >
                    <input
                        type="text"
                        class="form-control form-control-sm shadow-sm no-spinners"
                        id="quoteId"
                        v-model="quoteId"
                        required
                        placeholder="ID"
                        style="width: 80px"
                        @input="handleInput"
                    />
                </div>
                <button type="submit" class="btn btn-outline-info btn-sm ms-2">
                    Search
                </button>
            </form>
        </div>

        <div
            v-if="loading"
            class="mt-3 alert alert-info shadow-sm"
            role="alert"
        >
            Loading quote with ID
            {{ quoteId }}...
        </div>
        <div v-else-if="quote" class="mt-3 card shadow-lg border-info">
            <div class="card-body">
                <h5 class="card-title text-info">
                    <span
                        v-if="quote.id"
                        style="
                            text-align: right;
                            display: inline-block;
                            width: calc(100% - 2em);
                        "
                        >Quote found!</span
                    >
                    <span v-else>Quote not found</span>
                </h5>

                <div v-if="quote.id">
                    <p class="card-text">
                        <strong>ID: </strong>
                        <span class="text-muted"> {{ quote.id }}</span>
                    </p>
                    <p class="card-text">
                        <strong>Author: </strong>
                        <span class="text-muted"> {{ quote.author }}</span>
                    </p>
                    <p class="card-text">
                        <strong>Text: </strong>
                        <span class="text-muted fst-italic">
                            {{ quote.quote }}</span
                        >
                    </p>
                </div>
            </div>
        </div>

        <div v-else class="mt-3 alert alert-warning" role="alert">
            Could not search the quote.
            <span v-if="retryAfter > 0">
                Request limit exceeded. Retrying in
                {{ retryAfter }} seconds...
            </span>
            <span v-else-if="error && error.message">
                {{ error.message }}
            </span>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";

const quoteId = ref("");
const quote = ref(null);
const loading = ref(false);
const error = ref(null);
const router = useRouter();
const route = useRoute();
const retryAfter = ref(0);
const isRouteIdInitial = ref(false);

let retryInterval = null;

const handleInput = (event) => {
    quoteId.value = event.target.value.replace(/[^0-9]/g, "");
};

async function fetchQuote() {
    loading.value = true;
    quote.value = null;
    quote.error = null;
    error.value = null;
    retryAfter.value = 0;

    clearInterval(retryInterval);

    try {
        const response = await fetch(`/api/quotes/${quoteId.value}`);
        if (!response.ok) {
            const errorData = await response.json();

            if (errorData.retry_after) {
                retryAfter.value = errorData.retry_after;
                retryInterval = setInterval(() => {
                    retryAfter.value--;
                    if (retryAfter.value <= 0) {
                        clearInterval(retryInterval);
                        fetchQuote();
                    }
                }, 1000);
            } else {
                error.value = errorData;
            }
            quote.value = null;
        } else {
            quote.value = await response.json();
        }
    } catch (err) {
        error.value = {
            message: err.message || "An unexpected error occurred.",
        };
        console.error(`Error fetching quote with ID ${quoteId.value}:`, err);
        quote.value = null;
    } finally {
        loading.value = false;
    }
}

// Fetch quote by id on component mount
onMounted(() => {
    if (route.params.id) {
        quoteId.value = route.params.id;
        fetchQuote();
    }
});
</script>
