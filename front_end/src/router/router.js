import Vue from 'vue'
import VueRouter from 'vue-router'

import LoginPage from '../page/loginPage'
import adminPage from '../page/adminPage'
import workerPage from '../page/workerPage'
import handoutCasePage from '../page/handoutCasePage'
// import completeCasePage from '../page/completeCasePage'
import caseInfoPage from '../page/caseInfoPage'

Vue.use(VueRouter);

const routes = [
    {
        path: "/login",
        component: LoginPage
    },
    {
        path: "/admin",
        component: adminPage,
        children: [
            {
                path: "handoutCase",
                component: handoutCasePage
            },
            {
                path: "completeCase",
                component: handoutCasePage
            }
        ]
    },
    {
        path: "/worker",
        component: workerPage
    },
    {
        path: "/",
        redirect: '/login'
    },
    {
        path: "/caseInfo/:id",
        component: caseInfoPage
    }
];

var router = new VueRouter({
    mode: 'history',
    routes: routes
});

export default router;