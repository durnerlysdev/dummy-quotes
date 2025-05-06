<template>
    <div class="container mt-5">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-primary fw-bold">Dummy quotes</h1>
        </header>
        <nav class="mb-4">
            <router-link
                to="/"
                class="btn btn-outline-primary me-2"
                @click.prevent="fetchAllQuotesOnClick"
            >
                View all
            </router-link>
            <router-link
                to="/random"
                class="btn btn-outline-success me-2"
                @click.prevent="fetchRandomQuoteOnClick"
            >
                Random quote
            </router-link>
            <router-link to="/1" class="btn btn-outline-info">
                Search by ID
            </router-link>
        </nav>
        <main class="bg-light p-4 rounded shadow-sm">
            <router-view :key="randomKey"></router-view>
        </main>
    </div>
</template>

<script setup>
import { ref } from "vue";
import "./assets/css/main.css";

const randomKey = ref(0);

const fetchRandomQuoteOnClick = () => {
    randomKey.value++;
};

const fetchAllQuotesOnClick = () => {
    randomKey.value++;
};
</script>
