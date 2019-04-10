import Vue from 'vue'
import VueRouter from 'vue-router'

import LoginPage from '../page/loginPage'
import adminPage from '../page/adminPage'
import workerPage from '../page/workerPage'

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