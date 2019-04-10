<template>
    <div class="top-nav">
        <span class="top-nav-title">质检工作台</span>
        <div class="top-nav-user">
            <div class="user-alias">
                <img src="">
            </div>
            <span class="user-name">{{ userName }}</span>
            <a class="logout" v-if="userName != '未登录'" @click="logout()">退出</a>
        </div>
    </div>
</template>

<script>
    import { logout } from '../api/getData';

    export default {
        name: "top-nav",
        props: ['userName'],
        methods: {
            logout: async function() {
                await logout();
                this.$router.push('/login');
            }
        }
    }
</script>

<style scoped>
    .top-nav {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 60px;
        background: rgb(17,32,73);
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 1000;
    }

    .logout {
        margin-right: 10px;
        color: #4288ce;
        text-decoration: underline;
        font-size: 0.9em;
    }

    .logout:hover {
        color: #660066;
    }

    .top-nav-title {
        position: relative;
        left: 20px;
        color: white;
        font-size: 1.4em;
    }

    .top-nav-user {
        position: relative;
        display: flex;
        align-items: center;
        left: auto;
        right: 0;
    }

    .top-nav-user .user-alias {
        border-radius: 13px;
        background: rgb(181,217,252);
        height: 26px;
        width: 26px;
    }

    .top-nav-user .user-name {
        color: white;
        margin-left: 5px;
        margin-right: 20px;
        font-size: 0.9em;
    }
</style>