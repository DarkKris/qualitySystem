import Vue from 'vue'
import VueRouter from 'vue-router'

import LoginPage from '../page/loginPage'
import adminPage from '../page/adminPage'
import workerPage from '../page/workerPage'
import handoutCasePage from '../page/handoutCasePage'
import caseInfoPage from '../page/caseInfoPage'
import download from '../page/download'

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
    },
    {
        path: "/download",
        component: download
    }
];

var router = new VueRouter({
    mode: 'history',
    routes: routes
});

export default router;