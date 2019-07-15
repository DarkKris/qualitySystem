<template>
    <div class="handout-page">
        <el-row type="flex" align="middle" class="search-bar" justify="start">
            <el-col :span="1"><span class="search-bar-title">ID查询</span></el-col>
            <el-col :span="4"><el-input type="number" placeholder="请输入完整质检单ID" style="margin-left:20px;" v-model="searchID" :clearable="true"/></el-col>
            <el-col :span="19" id="buttons-group">
                <el-button type="primary" id="search-button" @click="onSearch">搜索</el-button>
                <el-button
                        type="primary"
                        id="handout-button"
                        v-if="$route.path == '/admin/handoutCase'"
                        @click="handoutClick"
                >
                    分配质检单
                </el-button>
            </el-col>
        </el-row>
        <div class="case-list">
            <div class="list-filter" v-if="filter.visible">
                <el-form
                        label-width="90px"
                >
                    <el-row align="middle">
                        <el-col :span="8">
                            <el-form-item label="业务线">
                                <el-select v-model="filter.workline">
                                    <el-option label="全部" value="0" />
                                    <el-option label="在线" value="1" />
                                    <el-option label="热线" value="2" />
                                </el-select>
                            </el-form-item>
                        </el-col>

                        <el-col :span="8">
                            <el-form-item label="被质检团队">
                                <el-select v-model="filter.beTestTeam">
                                    <el-option label="全部" value="0" />
                                    <el-option v-for="item in initData.be_test_team" :label="item.be_test_team" :value="item.be_test_team" :key="item.be_test_team"/>
                                </el-select>
                            </el-form-item>
                        </el-col>

                        <el-col :span="8">
                            <el-form-item label="创建人">
                                <el-select v-model="filter.createUser">
                                    <el-option label="全部" value="0" />
                                    <el-option v-for="item in initData.creater" :label="item.nickname" :value="item.created_user" :key="item.created_user"/>
                                </el-select>
                            </el-form-item>
                        </el-col>
                    </el-row>

                    <el-row align="middle">
                        <el-col :span="8">
                            <el-form-item label="质检员">
                                <el-select v-model="filter.testWorker">
                                    <el-option label="全部" value="0" />
                                    <el-option v-for="item in initData.test_worker" :label="item.nickname" :value="item.worker_id" :key="item.worker_id"/>
                                </el-select>
                            </el-form-item>
                        </el-col>

                        <el-col :span="12">
                            <el-form-item label="创建时间">
                                <el-date-picker
                                        size="large"
                                        v-model="filter.time"
                                        type="daterange"
                                        align="right"
                                        unlink-panels
                                        range-separator="至"
                                        start-placeholder="开始日期"
                                        end-placeholder="结束日期"
                                        :picker-options="pickerOptions2"
                                >
                                </el-date-picker>
                            </el-form-item>
                        </el-col>
                    </el-row>

                    <el-row class="filter-button-group">
                        <el-button type="primary" @click="onFilter">查询</el-button>
                        <el-button plain @click="resetFilter">重置</el-button>
                        <el-button plain @click="excelOut" v-if="$route.path == '/admin/completeCase'">导出</el-button>
                    </el-row>
                </el-form>
            </div>

            <div class="list-content">
                <p class="statistics-p" v-if="$route.path == '/admin/completeCase'">合格数量:{{goodTotal}} &nbsp;&nbsp;不合格数量:{{ungood}} &nbsp;&nbsp;合格率:{{goodPercent}}</p>
                <el-table
                        :data="tableData.filter(data => !searchID || data.qa_id == searchID)"
                        style="width: 100%;"
                        height="90%"
                        header-cell-class-name="header-cell"
                        max-height="90%"
                >
                    <el-table-column
                            label="序号"
                            width="100%"
                            type="index"
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
                            prop="created_time"
                            label="创建时间"
                            sortable
                    >
                    </el-table-column>
                    <el-table-column
                            prop="creater_name"
                            label="创建人"
                    >
                    </el-table-column>
                    <el-table-column
                            prop="be_test_servicer"
                            label="被质检人"
                    >
                    </el-table-column>
                    <el-table-column
                            prop="be_test_team"
                            label="被质检团队"
                    >
                    </el-table-column>
                    <el-table-column
                            label="质检结果"
                            v-if="$route.path == '/admin/completeCase'"
                    >
                        <template scope="scope">
                            {{isGood(scope.row.grade)}}
                        </template>
                    </el-table-column>
                </el-table>
                <div class="list-footer" v-if="filter.visible">
                    <span>共 {{ caseTotal }} 条</span>
                    <el-pagination
                            :current-page.sync="currentPage"
                            page-size="9999"
                            layout="prev, pager, next, jumper"
                            :total="caseTotal"
                    />
                </div>
            </div>
        </div>

        <keep-alive>
            <el-dialog
                    title="分配质检单"
                    :visible.sync="dialog.visible"
                    width="700px"
                    custom-class="dialog"
                    style="overflow-y: scroll"
                    append-to-body="true"
            >
                <el-form :model="dialog" status-icon :rules="rules" ref="dialogForm">
                    <el-form-item label="业务线" :label-width="dialog.width">
                        <el-select v-model="dialog.work_line">
                            <el-option label="全部" value="0" />
                            <el-option label="在线" value="1" />
                            <el-option label="热线" value="2" />
                        </el-select>
                    </el-form-item>
                    <el-form-item label="被质检单位" :label-width="dialog.width">
                        <el-select v-model="dialog.be_test_team">
                            <el-option label="全部" value="0" />
                            <el-option v-for="item in initData.be_test_team" :label="item.be_test_team" :value="item.be_test_team" :key="item.be_test_team"/>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="评价结果" :label-width="dialog.width">
                        <el-select v-model="dialog.comment_result" multiple placeholder="请选择评价结果">
                            <el-option label="满意" value="1" />
                            <el-option label="一般" value="0" />
                            <el-option label="不满意" value="-1" />
                        </el-select>
                    </el-form-item>
                    <el-form-item label="问题类型" :label-width="dialog.width">
                        <el-select v-model="dialog.problem_type">
                            <el-option label="全部" value="0" />
                            <el-option label="售后问题" value="1" />
                            <el-option label="运费问题" value="2" />
                            <el-option label="商家问题" value="3" />
                            <el-option label="一般问题" value="4" />
                        </el-select>
                    </el-form-item>
                    <el-form-item label="客服类型" :label-width="dialog.width">
                        <el-select v-model="dialog.service_type">
                            <el-option label="全部" value="0" />
                            <el-option label="活动客服" value="1" />
                            <el-option label="假货客服" value="2" />
                            <el-option label="高级客服" value="3" />
                            <el-option label="运费客服" value="4" />
                        </el-select>
                    </el-form-item>
                    <el-form-item label="数量" :label-width="dialog.width" style="margin-top: 50px;" prop="handout_total">
                        <el-input type="number" v-model="dialog.handout_total" autocomplete="off" placeholder="请输入数量，不得超过case总数" style="width: 300px;"/>
                        <el-popover
                                placement="right"
                                width="100"
                                trigger="click"
                        >
                            <div
                                    v-loading="loading"
                                    element-loading-background="rgba(255,255,255)"
                            >
                                <span>筛选总数：{{filterTotal}}</span>
                            </div>
                            <a slot="reference" style="margin-left: 20px;font-size:14px;color:#4a90e2;" @click="popoverCount">查看case总数</a>
                        </el-popover>
                    </el-form-item>
                    <el-form-item label="分配方式" :label-width="dialog.width" prop="handout_type">
                        <el-select v-model="dialog.handout_type" style="width: 200px !important;">
                            <el-option label="平均分配" value="0" />
                            <el-option label="手动分配" value="1" />
                        </el-select>
                        <div class="handout-children">
                            <!--平均分配-->
                            <div v-if="dialog.handout_type==0">
                                <el-select v-model="dialog.handout_worker" multiple placeholder="请选择质检员" style="width: 350px !important;">
                                    <el-option
                                        v-for="(item,index) in initData.test_worker"
                                        :key="index"
                                        :label="item.nickname"
                                        :value="item.worker_id"
                                    />
                                </el-select>
                            </div>
                            <!--手动分配-->
                            <div v-else>
                                <div v-for="(item,index) in dialog.handout_data" style="display: flex;margin:5px 0;" :key="index">
                                    <el-select v-model="item.worker_id" placeholder="请选择质检员">
                                        <el-option v-for="item in initData.test_worker" :label="item.nickname" :value="item.worker_id" :key="item.worker_id"/>
                                    </el-select>
                                    <span style="margin-left: 15px;margin-right: 5px;">分配</span>
                                    <el-input type="number" v-model="item.caseNum" style="width: 60px;"/>
                                    <span style="margin-left: 5px;">单</span>
                                    <el-button @click="deleArrs(index)" plain style="margin-left: 5px;">删除</el-button>
                                </div>
                                <el-button @click="addArrs" plain style="margin-top: 15px;">增加</el-button>
                            </div>
                        </div>
                    </el-form-item>
                </el-form>
                <div slot="footer" class="dialog-footer">
                    <el-button type="primary" @click="handoutCaseFunction">确 定</el-button>
                    <el-button plain @click="resetDialog">取 消</el-button>
                </div>
            </el-dialog>
        </keep-alive>
        <el-dialog
                title="错误"
                :visible.sync="validateDialog.visible"
                width="30%"
                :before-close="handleClose"
                append-to-body="true"
        >
            <span>{{validateDialog.msg}}</span>
            <span slot="footer" class="dialog-footer">
                <el-button type="primary" @click="validateDialog.visible = false">确 定</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
    import { getFilterCount, getBeTestTeam, getTestWorker, getCreater, getCaseData, filterHandout, exportExcel } from "../api/getData";

    export default {
        name: "handout-case-page",
        data() {
            // 表单检验项begin
            // var validateHandoutType = (rule, value, callback) => {
            //     console.log(this.dialog.handout_type);
            //     if (this.dialog.handout_type == 0) {
            //         // 平均分配
            //         console.log("begin");
            //         if(this.dialog.handout_worker.length > this.filterTotal) {
            //             callback(new Error('(平均每人分配0单)请合理分配质检单'));
            //         }else{
            //             callback();
            //         }
            //         console.log("here");
            //     } else {
            //         let count = 0;
            //         this.dialog.handout_data.forEach( (data, index) => {
            //             if(data.worker_id == '') callback(new Error('请选择质检员'));
            //             if(data.caseNum == 0) callback(new Error('请填写正确单数'));
            //             count+=parseInt(data.caseNum);
            //         });
            //         if(count != this.dialog.handout_total) {
            //             let msg = '已分配数量与未分配数量不符' + '(剩余'+ (this.dialog.handout_total-count) +'单未分配)';
            //             callback(new Error(msg));
            //         }else{
            //             callback();
            //         }
            //     }
            //     callback();
            // };
            var validateHandoutTotal = (rule, value, callback) => {
                if (this.filterTotal == -1) {
                    callback(new Error('请先点击"查看case总数"'));
                }
                if (!value) {
                    callback(new Error('输入数量'));
                } else if(value > this.filterTotal) {
                    callback(new Error('数量不得超过case总数'));
                } else if(value <= 0) {
                    callback(new Error('请合理填写分配数量'));
                } else {
                    callback()
                }
            };
            // 表单检验项end

            // 数据
            return {
                rules: {
                    handout_total: [
                        { validator: validateHandoutTotal, trigger: 'blur' }
                    ]
                    // handout_type: [
                    //     { validator: validateHandoutType, trigger: 'blur' }
                    // ]
                },
                searchID: '',
                filter: {
                    visible: true,
                    workline: '0',
                    beTestTeam: '0',
                    createUser: '0',
                    testWorker: '0',
                    time: '',
                },
                currentPage: 1,
                pickerOptions2: {
                    shortcuts: [{
                        text: '最近一周',
                        onClick(picker) {
                            const end = new Date();
                            const start = new Date();
                            start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
                            picker.$emit('pick', [start, end]);
                        }
                    }, {
                        text: '最近一个月',
                        onClick(picker) {
                            const end = new Date();
                            const start = new Date();
                            start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
                            picker.$emit('pick', [start, end]);
                        }
                    }, {
                        text: '最近三个月',
                        onClick(picker) {
                            const end = new Date();
                            const start = new Date();
                            start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
                            picker.$emit('pick', [start, end]);
                        }
                    }]
                },
                tableData: [],
                dialog: {
                    width: '101px',
                    visible: false,
                    work_line: '0',
                    be_test_team: '0',
                    comment_result: ["1","0","-1"],
                    problem_type: '0',
                    service_type: '0',
                    // upon has be handle with Function getCondition
                    handout_total: null,
                    handout_type: '0',
                    handout_worker: [],
                    handout_data: [
                        {
                            'worker_id': '',
                            'caseNum': 0
                        }
                    ]
                },
                filterTotal: -1,
                goodTotal: 0,
                caseTotal: 100,
                loading: true,
                initData: {
                    be_test_team: [],
                    test_worker: [],
                    creater: [],
                },
                validateDialog: {
                    visible: false,
                    msg: ""
                }
            }
        },
        mounted() {
            this.getInitData();
        },
        methods: {
            // 搜索
            onSearch: function() {
                // onSearch 用js方式进行数据筛选，避免与后端交互
            },
            // filter查询
            onFilter: async function() {
                // 根据route.path更改查询类型

                let condition = {};

                if(this.path == '/admin/handoutCase') {
                    condition = {qa_id: 'not null', status: 1};
                }else{
                    condition = {qa_id: 'not null', status: 2};
                }

                if(this.filter.beTestTeam != 0) condition.be_test_team = this.filter.beTestTeam;
                if(this.filter.workline != 0) condition.work_line = this.filter.workline;
                if(this.filter.createUser != 0) condition.created_user = this.filter.createUser;
                if(this.filter.testWorker != 0) condition.worker_id = this.filter.testWorker;
                if(this.filter.time !== '') {
                    condition.created_time = ['',''];
                    let res = this.filter.time;
                    console.log(res);
                    condition.created_time[0] = new Date(res[0].getTime() + 24 * 60 * 60 * 1000);
                    condition.created_time[1] = new Date(res[1].getTime() + 48 * 60 * 60 * 1000);
                }

                let listArr = await getCaseData({condition: condition});

                if(listArr.code == 200) {
                    this.drawTable(listArr.data);
                }
            },
            // 重置filter条件
            resetFilter: function() {
                this.filter = {
                    visible: true,
                    workline: '0',
                    beTestTeam: '0',
                    createUser: '0',
                    testWorker: '0',
                    time: '',
                };
                this.onFilter();
            },
            // 导出excel
            excelOut: async function() {
                let condition = {};

                if(this.path == '/admin/handoutCase') {
                    condition = {qa_id: 'not null', status: 1};
                }else{
                    condition = {qa_id: 'not null', status: 2};
                }

                if(this.filter.beTestTeam != 0) condition.be_test_team = this.filter.beTestTeam;
                if(this.filter.workline != 0) condition.work_line = this.filter.workline;
                if(this.filter.createUser != 0) condition.created_user = this.filter.createUser;
                if(this.filter.testWorker != 0) condition.worker_id = this.filter.testWorker;
                if(this.filter.time !== '') {
                    condition.created_time = ['',''];
                    let res = this.filter.time;
                    console.log(res);
                    condition.created_time[0] = new Date(res[0].getTime() + 24 * 60 * 60 * 1000);
                    condition.created_time[1] = new Date(res[1].getTime() + 48 * 60 * 60 * 1000);
                }

                let resp = await exportExcel({condition: condition});
                if( resp.code == 200 ) {
                    let routeUrl = this.$router.resolve({
                        path: "/download",
                    });
                    window.open(routeUrl.href, '_blank');
                    this.$message.success('导出成功，开始导出');
                } else {
                    this.$message.error('导出失败');
                }
            },
            // 弹出dialog
            handoutClick: function() {
                this.dialog.visible = true;
            },
            // 查询总数功能
            popoverCount: async function() {
                this.loading=true;
                let res = await getFilterCount(this.dialog);
                if(res.code === 200) {
                    this.filterTotal = res.data;
                    this.loading=false;
                }else{
                    this.$message.warning(res.message);
                }
            },
            // Dialog中手动分配的添加按钮
            addArrs: function() {
                this.dialog.handout_data.push({'worker_id':'','caseNum':0});
            },
            // Dialog中手动分配的删除按钮
            deleArrs: function(index) {
                this.dialog.handout_data.splice(index,1);
            },
            // 重置Dialog中搜索条件
            resetDialog: function() {
                this.dialog = {
                    width: '101px',
                    visible: false,
                    work_line: '0',
                    be_test_team: '0',
                    comment_result: ["1","0","-1"],
                    problem_type: '0',
                    service_type: '0',
                    handout_type: '0',
                    handout_worker: [],
                    handout_data: [
                        {
                            'worker_id': '',
                            'caseNum': 0
                        }
                    ]
                };
            },
            // 初始化界面时请求数据
            getInitData: async function() {
                // 根据router 更改获取数据条件
                let condition = {};

                if(this.path == '/admin/handoutCase') {
                    condition = {condition:{qa_id:'not null', status: 1}};
                }else{
                    condition = {condition:{qa_id:'not null', status: 2}};
                }

                // 获取data列表
                let listArr = await getCaseData(condition);
                if(listArr.code==200) {
                    this.caseTotal = listArr.data.length;
                    this.drawTable(listArr.data);
                }

                // 获取被质检单位列表
                let beTestTeamArr = await getBeTestTeam();
                if(beTestTeamArr.code==200) {
                    this.initData.be_test_team = [];
                    beTestTeamArr.data.forEach( item => {
                        this.initData.be_test_team.push(item);
                    });
                }

                // 获取质检员列表
                let testWorkerArr = await getTestWorker();
                if(testWorkerArr.code==200) {
                    this.initData.test_worker = [];
                    testWorkerArr.data.forEach( item => {
                        this.initData.test_worker.push({'worker_id':item.user_id,'nickname':item.usernick});
                    });
                }

                // 获取创建人列表
                let createrArr = await getCreater();
                if(createrArr.code==200) {
                    this.initData.creater = [];
                    createrArr.data.forEach( item => {
                        this.initData.creater.push({'created_user':item.user_id,'nickname':item.usernick});
                    });
                }
            },
            // workLineComputed方法
            workLineComputed: function(opt) {
                return opt==0?'全部':opt==1?'在线':opt==2?'热线':'错误';
            },
            // 渲染table
            drawTable: function(data) {
                this.goodTotal = 0;
                this.tableData = [];

                data.forEach( item => {
                    if(item.status == 2) {
                        let grade = JSON.parse(item.grade);
                        let grade_total = grade.ceremony + grade.messagetrans + grade.pinpoint + grade.quickhandle + grade.sysopt;
                        if (grade_total >= 60) this.goodTotal += 1;
                    }
                    this.tableData.push({
                        qa_id: item.qa_id,
                        work_line: this.workLineComputed(item.work_line),
                        created_time: new Date(item.created_time).toLocaleDateString(),
                        creater_name: item.creater_name,
                        be_test_servicer: item.be_test_servicer,
                        be_test_team: item.be_test_team,
                        grade: item.grade
                    });
                });
            },
            // handoutCase
            handoutCaseFunction: async function() {
                let res = await this.myValidate();
                await this.$refs['dialogForm'].validate((valid) => {
                    if (valid) {
                        // pass
                    } else {
                        return false;
                    }
                });

                if(res===false)return false;

                let condition = this.dialog;

                let rep = await filterHandout(condition);
                if(rep.code == 200) {
                    this.$message.success('发放质检单成功!');
                    this.resetDialog();
                }else{
                    this.$message.error('发放质检单失败：'+rep.message);
                }

                this.dialog.visible = false;
            },
            // validate
            myValidate: function() {
                if (this.dialog.handout_type == 0) {
                    // 平均分配
                    if(this.dialog.handout_worker.length > this.dialog.handout_total) {
                        this.validateDialog.msg = "(平均每人分配0单)请合理分配质检单";
                        this.validateDialog.visible = true;
                        return false;
                    }
                } else {
                    let count = 0;
                    this.dialog.handout_data.forEach( (data, index) => {
                        if(data.worker_id == '') callback(new Error('请选择质检员'));
                        if(data.caseNum == 0) callback(new Error('请填写正确单数'));
                        count+=parseInt(data.caseNum);
                    });
                    if(count != this.dialog.handout_total) {
                        let msg = '已分配数量与未分配数量不符' + '(剩余'+ (this.dialog.handout_total-count) +'单未分配)';
                        // callback(new Error(msg));
                        this.validateDialog.msg = msg;
                        this.validateDialog.visible = true;
                        return false;
                    }
                }
                return true;
                // callback();
            },
            handleClose: function() {
                this.validateDialog.visible = false;
            },
            isGood: function(grade) {
                let obj = JSON.parse(grade);
                let total = obj.ceremony + obj.sysopt + obj.messagetrans + obj.pinpoint + obj.quickhandle;
                if(total>=60) {
                    return "合格";
                }else{
                    return "不合格";
                }
            }
        },
        watch: {
            path: function(val) {
                this.searchID = '';
                this.getInitData();
                this.resetFilter();
            },
            searchID: function(val) {
                if(val != "" && val!=null) {
                    this.filter.visible = false;
                }else{
                    this.filter.visible = true;
                }
            }
        },
        computed: {
            path: function() {
                return this.$route.path;
            },
            ungood: function() {
                return this.caseTotal - this.goodTotal;
            },
            goodPercent: function() {
                return (this.goodTotal / this.caseTotal * 100).toFixed(2) + "%";
            }
        }
    }
</script>

<style>
    .statistics-p {
        float: right;
        font-size: 16px;
        margin: 2px;
    }

    .list-filter .el-form-item__label {
        text-align: right !important;
    }

    .handout-page {
        color: rgb(90,90,90);
    }

    .handout-page .el-input >input {
        height: 33px !important;
    }

    .handout-page .search-bar .el-button {
        height: 33px !important;
        line-height: 0;
    }

    .search-bar {
        background: white;
        width: 100%;
        min-height: 60px;
        height: 7%;
    }

    .search-bar .search-bar-title {
        margin-left: 10px;
        font-size: 14px;
    }

    .search-bar #search-button {
        margin-left: 40px;
    }

    .search-bar #buttons-group {
        display: flex;
        justify-content: space-between;
        margin-right: 20px;
    }

    .case-list {
        margin-top: 1%;
        background-color: white;
        height: 92%;
        width: 100%;
    }

    .el-table__header {
        overflow: hidden !important;
        border-top: 1px solid rgb(235,238,245) !important;
        border-left: 1px solid rgb(235,238,245) !important;
        border-right: 1px solid rgb(235,238,245) !important;
        border-top-left-radius: 10px !important;
        border-top-right-radius: 10px !important;
    }

    .header-cell {
        background-color: rgb(241,243,247) !important;
        color: rgb(90,90,90);
    }

    .list-filter {
        padding: 20px 20px 0 20px;
    }

    .list-content {
        height: calc(92% - 132px);
        margin: 10px;
        padding-top: 20px;
    }

    .list-footer {
        margin-top: 10px;
        margin-left: 5px;
        display: flex;
        justify-content: space-between;
        flex-flow: row nowrap;
        width: 100%;
    }

    .list-filter .el-col {
        height: 50px !important;
    }

    .el-date-editor {
        width: 456px !important;
        height: 34px;
        margin-top: 2px;
    }

    .el-form-item__label {
        font-size: 14px !important;
    }

    .el-date-editor .el-range__icon {
        line-height: 25px !important;
    }

    .filter-button-group {
        margin-left: 90px;
    }

    .dialog {
        border-radius: 8px;
    }

    .dialog .el-form-item__label {
        text-align: right !important;
        /*margin-right: 30px;*/
    }

    .dialog .el-select {
        width: 300px !important;
    }

    .handout-children {
        margin-left: 10px;
        margin-top: 5px;
    }

    .el-dialog {
        z-index: 9999;
    }
</style>