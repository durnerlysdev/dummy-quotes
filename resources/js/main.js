import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import VuePaginate from 'vue-paginate';
import 'bootstrap/dist/css/bootstrap.css';

const app = createApp(App);
app.use(router);
app.use(VuePaginate);
app.mount('#app');