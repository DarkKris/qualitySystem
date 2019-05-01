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
                                        type="index"
                                        label="序号"
                                        width="100%"
                                >
                                </el-table-column>
                                <el-table-column
                                        label="质检单ID"
                                >
                                    <template scope="scope">
                                        <a href='' @click="$router.push('/caseinfo/'+scope.row.qa_id)">{{scope.row.qa_id}}</a>
                                    </template>
                                </el-table-column>
                                <el-table-column
                                        prop="work_line"
                                        label="业务线"
                                >
                                </el-table-column>
                                <el-table-column
                                        prop="problem_type"
                                        label="问题类型"
                                >
                                </el-table-column>
                                <el-table-column
                                        prop="creater_name"
                                        label="创建人"
                                >
                                </el-table-column>
                            </el-table>
                            <div class="list-footer">
                                <span>共 {{ total }} 条</span>
                                <el-pagination
                                        :current-page.sync="currentPage"
                                        page-size="10"
                                        layout="prev, pager, next, jumper"
                                        :total="total"
                                />
                            </div>
                        </div>
                    </div>
                </el-main>
            </el-container>
        </el-container>
    </div>
</template>

<script>
    import topNav from '../components/TopNav';
    import { checkLogin, getCaseData } from '../api/getData';

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
            getCaseList: async function() {
                const listArr = await getCaseData({condition:{qa_id: 'not null',status: 1, worker_id: 0}});
                if(listArr.code==200) {
                    this.tableData = [];
                    listArr.data.forEach( item => {
                        this.tableData.push({
                            qa_id: item.qa_id,
                            work_line: item.work_line==1?'在线':'热线',
                            problem_type: this.quesTypeMethod(item.problem_type),
                            creater_name: item.creater_name
                        });
                    });
                    this.total = listArr.data.length;
                }
            },
            quesTypeMethod: function(opt) {
                switch(opt) {
                    case 1:return '售后问题';
                    case 2:return '运费问题';
                    case 3:return '商家问题';
                    case 4:return '一般问题';
                }
            },
        },
        mounted() {
            this.checklogin();
            this.getCaseList();
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
</style>