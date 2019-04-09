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
                <router-view></router-view>
            </el-main>
        </el-container>
    </el-container>
</template>

<script>
import TopNav from "./components/TopNav";

export default {
    name: 'app',
    components: {
        TopNav
    },
    data() {
        return {
            username: '未登录',
            isLogin: false,
        }
    },
    mounted() {
        if(this.$route.name===null) {
            this.$router.push('login')
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
