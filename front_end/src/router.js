import Vue from 'vue'
import VueRouter from 'vue-router'

import LoginPage from './components/loginPage'

Vue.use(VueRouter);

const routes = [
    {
        path: "/login",
        component: LoginPage
    },
    // {
    //     path: "/",
    //     redirect: '/login'
    // }
];

var router = new VueRouter({
    mode: 'history',
    routes: routes
});

export default router;