<template>
    <div class="case-info-page">
        <top-nav :user-name="username"></top-nav>
        <el-row class="case-info">
            <el-col class="case-content">
                <span class="case-info-title">case详情</span>
                <div class="case-content-content">
                    <div
                            class="case-message"
                            v-for="key in caseMessages"
                            v-bind:key="key"
                            :class="key.type === 'user'?'user-message':'service-message'"
                    >
                        <span class="message-title">{{key.username}}&nbsp;&nbsp;&nbsp;{{key.time}}</span>
                        <span class="message-content">{{key.message}}</span>
                    </div>
                </div>
            </el-col>
            <el-col class="info-bar">
                <div class="case-testing-info">
                    <span class="case-info-title">质检信息</span>
                    <div class="case-testing-info-content">
                        <el-row>
                            <el-col :span="12"><div class="data-title">质检单ID</div><div class="data-content">{{caseInfo.case_id}}</div></el-col>
                            <el-col :span="12"><div class="data-title">创建人</div><div class="data-content">{{caseInfo.creater}}</div></el-col>
                        </el-row>
                        <el-row>
                            <el-col :span="12"><div class="data-title">业务线</div><div class="data-content">{{caseInfo.workline}}</div></el-col>
                            <el-col :span="12"><div class="data-title">创建时间</div><div class="data-content">{{caseInfo.createTime}}</div></el-col>
                        </el-row>
                        <el-row>
                            <el-col :span="12"><div class="data-title">被质检团队</div><div class="data-content">{{caseInfo.beTestTeam}}</div></el-col>
                            <el-col :span="12"><div class="data-title">质检员</div><div class="data-content">{{caseInfo.testWorker}}</div></el-col>
                        </el-row>
                        <el-row>
                            <el-col :span="12"><div class="data-title">被质检人</div><div class="data-content">{{caseInfo.beTestWorker}}</div></el-col>
                            <el-col :span="12"><div class="data-title">质检结果</div><div class="data-content">{{testFinalComputed}}</div></el-col>
                        </el-row>
                        <el-row>
                            <el-col :span="12"><div class="data-title">评价结果</div><div class="data-content">{{caseInfo.commentFinal}}</div></el-col>
                            <el-col :span="12"><div class="data-title">质检得分</div><div class="data-content">{{totalGradeComputed}}</div></el-col>
                        </el-row>
                        <el-row>
                            <el-col :span="12"><div class="data-title">售后问题</div><div class="data-content">{{caseInfo.saleQues}}</div></el-col>
                        </el-row>
                        <el-row>
                            <el-col :span="12"><div class="data-title">客服类型</div><div class="data-content">{{caseInfo.workerType}}</div></el-col>
                        </el-row>
                    </div>
                </div>
                <div class="case-grade-info">
                    <span class="case-info-title">质检打分</span>
                    <div v-if="admin || hasComplete">
                        <table class="case-grade-table" cellspacing="0">
                            <tr>
                                <th>打分项</th>
                                <th>得分</th>
                            </tr>

                            <tr>
                                <td class="normal">礼仪规范</td>
                                <td class="digital">{{caseGrade.ceremony}}</td>
                            </tr>

                            <tr>
                                <td class="normal">系统操作规范</td>
                                <td class="digital">{{caseGrade.sysopt}}</td>
                            </tr>

                            <tr>
                                <td class="normal">信息传递规范</td>
                                <td class="digital">{{caseGrade.messagetrans}}</td>
                            </tr>

                            <tr>
                                <td class="normal">精准定位问题</td>
                                <td class="digital">{{caseGrade.pinpoint}}</td>
                            </tr>

                            <tr>
                                <td class="normal">快速处理问题</td>
                                <td class="digital">{{caseGrade.quickhandle}}</td>
                            </tr>

                            <tr>
                                <td class="title">质检得分</td>
                                <td>{{ totalGradeComputed }}</td>
                            </tr>
                        </table>
                    </div>
                    <div v-else>
                        <el-form>
                            <el-form-item label="礼仪规范" label-width="100px">
                                <el-input-number v-model="caseGrade.ceremony"       :min="0" :max="10" label="礼仪规范"     name="number1" ref="number1" style="width: 200px;" @keyup.enter.native="onEnter(1)"/>
                            </el-form-item>
                            <el-form-item label="系统操作规范" label-width="100px">
                                <el-input-number v-model="caseGrade.sysopt"         :min="0" :max="20" label="系统操作规范"  name="number2" ref="number2" style="width: 200px;" @keyup.enter.native="onEnter(2)"/>
                            </el-form-item>
                            <el-form-item label="信息传递规范" label-width="100px">
                                <el-input-number v-model="caseGrade.messagetrans"   :min="0" :max="20" label="信息传递规范"  name="number3" ref="number3" style="width: 200px;" @keyup.enter.native="onEnter(3)"/>
                            </el-form-item>
                            <el-form-item label="精准定位问题" label-width="100px">
                                <el-input-number v-model="caseGrade.pinpoint"       :min="0" :max="20" label="精准定位问题"  name="number4" ref="number4" style="width: 200px;" @keyup.enter.native="onEnter(4)"/>
                            </el-form-item>
                            <el-form-item label="快速处理问题" label-width="100px">
                                <el-input-number v-model="caseGrade.quickhandle"    :min="0" :max="30" label="快速处理问题"  name="number5" ref="number5" style="width: 200px;" @keyup.enter.native="onEnter(5)"/>
                            </el-form-item>
                            <el-form-item style="display: flex;">
                                <el-button type="primary" @click="setGrade(1)">提交，并下一条</el-button>
                                <el-button plain @click="setGrade(2)">提交，回列表</el-button>
                                <el-button plain @click="resetGrade">重置</el-button>
                            </el-form-item>
                        </el-form>
                    </div>
                </div>
            </el-col>
        </el-row>
    </div>
</template>

<script>
    import { checkLogin, getCaseMsg, getCaseData, checkPrivilege, setGrade } from '../api/getData'
    import topNav from '../components/TopNav'

    export default {
        name: "case-info-page",
        components: { topNav },
        data() {
            return {
                admin: false,
                hasComplete: false,
                username: '未登录',
                caseMessages: [],
                caseInfo: {
                    creater: '',
                    workline: '',
                    createTime: '',
                    beTestTeam: '',
                    testWorker: '',
                    beTestWorker: '',
                    commentFinal: '',
                    saleQues: '',
                    workerType: ''
                },
                caseGrade: {
                    ceremony: '_ _',
                    sysopt: '_ _',
                    messagetrans: '_ _',
                    pinpoint: '_ _',
                    quickhandle: '_ _'
                },
                taskList: [],
                listIdx: 0,
            }
        },
        async beforeMount() {
            await this.checklogin();
            await this.getContent();
        },
        methods: {
            checklogin: async function() {
                const res = await checkLogin();
                if(res.code === 200 && res.data!==false) {
                    this.username = res.data.usernick;
                    this.admin = res.data.admin;
                }else{
                    this.$router.push('/login');
                }
            },
            checkVis: async function() {
                if(this.admin) {
                    if(!this.hasComplete) {
                        this.$message.warning('质检单还未完成');
                    }
                }else{
                    // 判断是否有本case权限
                    let result = await checkPrivilege({worker_id: 0, qa_id: this.case_id});
                    if(result.code == 200) {
                        if(result.data !== true) {
                            // 无权限
                            this.$message.error("您没有该质检单的查看操作权限");
                            this.$router.go(-1);
                        }else{
                            if(!this.hasComplete) {
                                await this.resetGrade();
                                await this.$refs.number1.focus();
                            }
                        }
                    }else{
                        this.$message.error(result.message);
                        this.$router.go(-1);
                    }
                }
            },
            getContent: async function() {
                let msgResp = await getCaseMsg({qa_id: this.case_id});
                if(msgResp.code==200) {
                    // 解析json
                    this.caseMessages = [];
                    let data = JSON.parse(msgResp.data).content;
                    data.forEach( (val,index) => {
                        this.caseMessages.push(val);
                    });
                }else{
                    this.$message.error(msgResp.message);
                }

                let infoResp = await getCaseData({condition:{case_id: this.case_id}});
                if(infoResp.code == 200) {
                    if (infoResp.data.length == 0) {
                        this.$message.error('质检单不存在');
                        this.$router.go(-1);
                    }
                    infoResp.data = infoResp.data[0];
                    this.caseInfo.case_id = infoResp.data.case_id;
                    this.caseInfo.creater = infoResp.data.creater_name;
                    this.caseInfo.workline = infoResp.data.work_line == 1 ? '在线' : '热线';
                    this.caseInfo.createTime = new Date(infoResp.data.created_time).toLocaleDateString();
                    this.caseInfo.beTestTeam = infoResp.data.be_test_team;
                    this.caseInfo.testWorker = infoResp.data.worker_name;
                    this.caseInfo.beTestWorker = infoResp.data.be_test_servicer;
                    this.caseInfo.commentFinal = infoResp.data.comment_result == 1 ? '满意' : infoResp.data.comment_result == 0 ? '一般' : '不满意';
                    this.caseInfo.saleQues = this.quesTypeMethod(infoResp.data.problem_type);
                    this.caseInfo.workerType = this.workerTypeMethod(infoResp.data.service_type);
                    this.hasComplete = infoResp.data.status == 2 ? true : false;

                    if(!(typeof(infoResp.data.grade)=='undefined') && infoResp.data.grade) {
                        this.caseGrade = JSON.parse(infoResp.data.grade);
                    }
                }

                // get taskList and listIdx
                let flag = false;
                this.taskList = await getCaseData({condition:{qa_id: 'not null',status: 1, worker_id: 0}});
                if(this.taskList.code == 200) this.taskList = this.taskList.data;
                await this.taskList.forEach( (data,index) => {
                    if(flag===false) {
                        if (data.qa_id == this.case_id) {
                            this.listIdx = index;
                            flag = true;
                        }
                    }
                });

                await this.checkVis();
            },
            quesTypeMethod: function(opt) {
                switch(opt) {
                    case 1:return '售后问题';
                    case 2:return '运费问题';
                    case 3:return '商家问题';
                    case 4:return '一般问题';
                }
            },
            workerTypeMethod: function(opt) {
                switch(opt) {
                    case 1:return '活动客服';
                    case 2:return '假货客服';
                    case 3:return '高级客服';
                    case 4:return '运费客服';
                }
            },
            onEnter: function(num) {
                let name = 'number'+num;

                switch(name)
                {
                    // 聚焦到下一个NumberSelector
                    case 'number1':
                        this.$refs.number2.focus();
                        break;
                    case 'number2':
                        this.$refs.number3.focus();
                        break;
                    case 'number3':
                        this.$refs.number4.focus();
                        break;
                    case 'number4':
                        this.$refs.number5.focus();
                        break;
                    case 'number5':
                        this.setGrade(1);
                        break;
                    default:
                        break;
                }
            },
            setGrade: async function(opt) {
                let data = this.caseGrade;
                data.qa_id = this.case_id;
                let rep = await setGrade(data);
                if(rep.code==200) {
                    switch (opt) {
                        case 1:
                            // 下一单
                            if(this.listIdx+1 === this.taskList.length) {
                                // 到头了
                                this.$message.success('成功提交，已经完成所有工作');
                                setInterval(this.$router.push('/worker'),1000);
                            }else{
                                // 跳转到下一单
                                this.$router.push('/caseinfo/'+this.taskList[this.listIdx+1].case_id);
                            }
                            break;
                        case 2:
                            // 返回列表
                            this.$router.push('/worker');
                            break;
                    }
                    this.$message.success('成功提交');
                }else{
                    this.$message.error('发生错误，提交失败');
                }
            },
            resetGrade: function() {
                this.caseGrade = {
                    ceremony: 0,
                    sysopt: 0,
                    messagetrans: 0,
                    pinpoint: 0,
                    quickhandle: 0
                };
            }
        },
        watch: {
            case_id: function() {
                this.checklogin();
                this.getContent();
            }
        },
        computed: {
            case_id: function() {
                return this.$route.params.id;
            },
            totalGradeComputed: function() {
                if(!this.hasComplete)return'_ _';
                let grade = this.caseGrade.ceremony+this.caseGrade.sysopt+this.caseGrade.messagetrans+this.caseGrade.pinpoint+this.caseGrade.quickhandle;
                return isNaN(grade)?'_ _':grade;
            },
            testFinalComputed: function() {
                if(!this.admin && !this.hasComplete)
                    return '_ _';
                let total = this.totalGradeComputed;
                if(total<60) {
                    return '不合格';
                }else{
                    return '合格';
                }
            }
        }
    }
</script>

<style>
    .case-info-page {
        display: flex;
        justify-content: flex-start;
        align-items: flex-start;
        top: 0;
        left: 0;
        right: 0;
        width: 100%;
        height: 100% !important;
    }

    .message-title {
        font-weight: 500;
        display: block;
        margin-top: 6px;
        font-size: 0.9em;
    }

    .message-content {
        font-size: 0.9em;
    }

    .user-message .message-title {
        color: rgb(218,188,115);
    }

    .service-message .message-title {
        color: rgb(119,150,183);
    }

    .case-info {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height:100%;
        padding: 75px 15px 15px 15px;
    }

    .case-info-title {
        font-size: 1.2em;
        color: black;
        border-left: 4px solid #658beb;
        padding-left: 15px;
    }

    .case-content {
        overflow-y: scroll;
        background: white;
        width: 60%;
        height: 100%;
        padding: 10px;
    }

    .case-conten ::-webkit-scrollbar-track-piece {
        background: white;
    }

    .case-content .case-content-content {
        padding: 20px;
    }

    .info-bar {
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        border-left: 1px solid rgb(216,216,216);
        background: white;
        width: 40%;
        height: 100%;
        padding: 15px;
    }

    .info-bar .case-testing-info {
        width: 100%;
        height: 40%;
    }

    .info-bar .case-grade-info {
        width: 100%;
        height: 60%;
    }

    .case-testing-info-content {
        margin-top: 12px;
    }

    .data-title {
        display: inline-block;
        text-align: right;
        width: 80px;
        font-size: 0.9em;
        color: rgb(145,145,145);
        margin: 3% 0;
    }
    
    .data-content {
        display: inline;
        font-size: 0.9em;
        margin-left: 20px;
    }

    .case-testing-info {
        display: block;
    }

    .case-grade-info {
        display: block;
    }

    .case-grade-table {
        margin-top: 20px;
        width: 350px;
        height: 400px;
        border-radius: 0.6em;
        border: 2px solid rgb(240,240,240);
        overflow: hidden;
    }

    .case-grade-table th {
        text-align: left;
        padding-left: 30px;
        font-weight: 400;
        color: #000;
    }

    .case-grade-table .normal {
        border-top: 2px solid rgb(240,240,240);
        text-align: left;
        padding-left: 30px;
        font-weight: 400;
        color: rgb(103,106,115);
    }

    .case-grade-table .digital {
        border-top: 2px solid rgb(240,240,240);
        text-align: left;
        padding-left: 30px;
        font-weight: 500;
        color: rgb(102,102,102);
    }

    .case-grade-table tr:last-child {
        background: rgb(245,247,250);
        color: rgb(100,103,112);
        font-weight: 500;

    }

    .case-grade-table tr:last-child .title{
        font-size: 1em;
        text-align: right;
        border-top: 2px solid rgb(240,240,240);
    }

    .case-grade-table tr:last-child td{
        font-size: 1.2em;
        padding-left: 30px;
        border-top: 2px solid rgb(240,240,240);
    }

    ::-webkit-scrollbar{
        width:0;
    }

    .el-form-item__label {
        text-align: right !important;
        margin-right: 10px;
    }
</style>