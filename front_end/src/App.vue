<template>
    <el-container id="app">
        <el-header>
            <top-nav
                  :user-name="username"
                  :is-login="isLogin"
            >
            </top-nav>
        </el-header>
        <el-container>
            <el-aside width="200px" v-if="isLogin">Aside</el-aside>
            <el-main style="height: 100%;">
                <component :is="content"></component>
            </el-main>
        </el-container>
    </el-container>
</template>

<script>
import TopNav from "./components/TopNav";
import LoginPage from "./components/loginPage"

export default {
    name: 'app',
    components: {
        TopNav,
        LoginPage
    },
    data() {
        return {
            content: 'LoginPage',
            username: '未登录',
            isLogin: false,
        }
    }
}
</script>

<style>
  #app {
      font-family: 'Avenir', Helvetica, Arial, sans-serif;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      height: 100%;
  }
</style>
