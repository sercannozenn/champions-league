import Vue from "vue";
import VueRouter from "vue-router";
import Home from "../components/Home";
import Simulation from "../components/Simulation";

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        component: Home,
        name: 'Home'
    },
    {
        path: '/simulation',
        component: Simulation,
        name: 'Simulation'
    },

];

export default new VueRouter({
    mode: 'history',
    routes
})


