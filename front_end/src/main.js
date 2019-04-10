import Vue from 'vue'

import 'element-ui/lib/theme-chalk/index.css';
import ElementUI from 'element-ui';

import App from './App'
import router from "./router/router"

Vue.config.productionTip = false;
Vue.use(ElementUI);

new Vue({
    el: '#app',
    render: c => c(App),
    router: router,
}).$mount('#app');

/**
 * 消息警告/提示
 * 全局方法
 * @param msg 信息
 * @param type 'success'/'warning'/'error'/''
 */
Vue.prototype.alertMessage = function(msg,type) {
    this.$message({
        duration: 2500,
        message: msg,
        type: type
    });
};