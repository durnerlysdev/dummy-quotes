import { createRouter, createWebHistory } from 'vue-router';
import AllQuotes from '../components/AllQuotes.vue';
import RandomQuote from '../components/RandomQuote.vue';
import QuoteById from '../components/QuoteById.vue';

const routes = [
    { path: '/', component: AllQuotes },
    { path: '/random', component: RandomQuote },
    { path: '/:id', component: QuoteById, props: true },
];

const router = createRouter({
    history: createWebHistory('quotes-ui'),
    routes,
});

export default router;
