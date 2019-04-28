<template>
    <div>
        <el-container id="admin-page">
            <el-header>
                <top-nav :user-name="username" />
            </el-header>
            <el-container class="el-container">
                <el-aside width="200px">
                    <div class="aside">
                        <el-menu
                                :default-active="defaultActive"
                                background-color="#f0f7fe"
                                text-color="#696969"
                                active-text-color="#658beb"
                                router
                                style="margin-top: 10px;border-right: unset !important;"
                        >
                            <el-menu-item index="handoutCase">
                                分配质检单
                            </el-menu-item>
                            <el-menu-item index="completeCase">
                                完结质检单
                            </el-menu-item>
                        </el-menu>
                    </div>
                </el-aside>
                <el-main>
                    <router-view class="main" />
                </el-main>
            </el-container>
        </el-container>
    </div>
</template>

<script>
    import { checkLogin } from "../api/getData";
    import topNav from '../components/TopNav';

    export default {
        name: "admin-page",
        components: { topNav },
        data() {
            return {
                username: '未登录',
            }
        },
        methods: {
            checklogin: async function() {
                const res = await checkLogin();
                if(res.code === 200 && res.data!==false) {
                    if(res.data.admin === 1) {
                        this.username = res.data.usernick;
                    }else{
                        this.$router.push('/worker');
                    }
                }else{
                    this.$router.push('/login');
                }
            },
            redirectTo: function() {
                if(this.$route.path === '/admin')
                    this.$router.push('/admin/handoutCase');
            },
        },
        mounted() {
            this.checklogin();
            this.redirectTo();
        },
        computed: {
            defaultActive: function() {
                return this.$route.path.replace('/admin/', '');
            }
        }
    }
</script>

<style>
    section {
        height: 100%;
    }

    .aside {
        top: 60px;
        left: 0px;
        position: fixed;
        background: rgb(240,247,254);
        height: 100%;
        width: 200px;
        z-index: 100;
    }

    .el-menu-item {
        border-left: 4px transparent solid !important;
        padding-left: 30px !important;
    }

    .el-menu-item:hover {
        color: #658beb !important;
        background-color: unset !important;
    }

    .aside .is-active {
        border-left: 4px #658beb solid !important;
        background-color: rgb(226,237,252) !important;
    }

    .main {
        position: absolute;
        top: 60px;
        right: 0;
        bottom: 0;
        left: 200px;
        height: auto;
        width: auto;
        padding: 0 10px 20px 10px;

        z-index: 0;
    }
</style>