require('./bootstrap');

window.Vue = require('vue').default;
import App from "./components/App";
import router from './router/index';
import { store } from './store/index';

const app = new Vue({
    el: '#app',
    router : router,
    store : store,
    template: '<App />',
    components: { App }
});
