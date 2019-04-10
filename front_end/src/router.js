import Vue from 'vue'
import VueRouter from 'vue-router'

import LoginPage from './components/loginPage'
import adminPage from './components/adminPage'
import workerPage from './components/workerPage'

Vue.use(VueRouter);

const routes = [
    {
        path: "/login",
        component: LoginPage
    },
    {
        path: "/admin",
        component: adminPage
    },
    {
        path: "/worker",
        component: workerPage
    },
    {
        path: "/",
        redirect: '/login'
    }
];

var router = new VueRouter({
    mode: 'history',
    routes: routes
});

export default router;