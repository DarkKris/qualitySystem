<template>
    <div class="worker-page">
        <el-container id="admin-page">
            <el-header>
                <top-nav :user-name="username" />
            </el-header>
            <el-container class="el-container">
                <el-aside width="200px">
                    <div class="aside">
                        <el-menu
                                default-active="myTask"
                                background-color="#f0f7fe"
                                text-color="#696969"
                                active-text-color="#658beb"
                                router
                                style="margin-top: 10px;border-right: unset !important;"
                        >
                            <el-menu-item index="myTask">
                                我的任务
                            </el-menu-item>
                        </el-menu>
                    </div>
                </el-aside>
                <el-main>
                    <div class="worker-main-content">
                        <div class="worker-list-content">
                            <el-table
                                    :data="tableData"
                                    style="width: 100%;"
                                    height="90%"
                                    header-cell-class-name="header-cell"
                                    max-height="90%"
                            >
                                <el-table-column
                                        prop="date"
                                        label="序号"
                                        width="100%"
                                >
                                </el-table-column>
                                <el-table-column
                                        prop="name"
                                        label="质检单ID"
                                >
                                </el-table-column>
                                <el-table-column
                                        prop="address"
                                        label="业务线"
                                >
                                </el-table-column>
                                <el-table-column
                                        prop="problemType"
                                        label="问题类型"
                                >
                                </el-table-column>
                                <el-table-column
                                        prop="address"
                                        label="创建人"
                                >
                                </el-table-column>
                            </el-table>
                            <el-row class="list-footer">
                                <span>共 {{ total }} 条</span>
                                <el-pagination
                                        :current-page.sync="currentPage"
                                        page-size="10"
                                        layout="prev, pager, next, jumper"
                                        :total="total"
                                />
                            </el-row>
                        </div>
                    </div>
                </el-main>
            </el-container>
        </el-container>
    </div>
</template>

<script>
    import topNav from '../components/TopNav';
    import { checkLogin } from '../api/getData';

    export default {
        name: "worker-page",
        components: { topNav },
        data: function() {
            return {
                username: '未登录',
                tableData: [

                ],
                total: 9,
                currentPage: 1
            }
        },
        methods: {
            checklogin: async function() {
                const res = await checkLogin();
                if(res.code === 200 && res.data!==false) {
                    if(res.data.admin === 0) {
                        this.username = res.data.usernick;
                    }else{
                        this.$router.push('/admin');
                    }
                }else{
                    this.$router.push('/login');
                }
            },
        },
        mounted() {
            this.checklogin();
        }
    }
</script>

<style scoped>
    .worker-main-content {
        background-color: white;
        height: 100%;
        width: 100%;
    }

    .worker-list-content {
        padding: 30px 10px 0 10px;
        height: 73%;
    }

    .list-footer {
        display: flex;
        justify-content: space-between;
        width: 100%;
    }
</style>