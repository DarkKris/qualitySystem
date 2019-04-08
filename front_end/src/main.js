import Vue from 'vue'
import VueRouter from 'vue-router'
import App from './App'

import 'element-ui/lib/theme-chalk/index.css';
import ElementUI from 'element-ui';

import loginPage from './components/loginPage'


Vue.config.productionTip = false;
Vue.use(ElementUI);


const Login = { template: loginPage};


const routes = [
    { path:'/login', components: Login},
];

const router = new VueRouter({
    routes
});

new Vue({
    render: h => h(App),
    router
}).$mount('#app');

/**
 * 消息警告/提示
 * 全局方法
 * @param msg 信息
 * @param type 'success'/'warning'/'error'/''
 */
Vue.prototype.alertMessage = function (msg,type) {
    this.$message({
        duration: 2500,
        message: msg,
        type: type
    });
};