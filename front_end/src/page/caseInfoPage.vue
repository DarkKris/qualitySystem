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
                        <span class="message-content">{{key.content}}</span>
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
                            <el-col :span="12"><div class="data-title">质检结果</div><div class="data-content">{{caseInfo.testFinal}}</div></el-col>
                        </el-row>
                        <el-row>
                            <el-col :span="12"><div class="data-title">评价结果</div><div class="data-content">{{caseInfo.commentFinal}}</div></el-col>
                            <el-col :span="12"><div class="data-title">质检得分</div><div class="data-content">{{caseInfo.testGrade}}</div></el-col>
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
                            <td>{{totalGrade}}</td>
                        </tr>
                    </table>
                </div>
            </el-col>
        </el-row>
    </div>
</template>

<script>
    import { checkLogin, getCaseMsg, getCaseInfo, getCaseGrade } from '../api/getData'
    import topNav from '../components/TopNav'

    export default {
        name: "case-info-page",
        components: { topNav },
        data() {
            return {
                username: '未登录',
                caseMessages: [
                    {
                        type: 'service',
                        username: '在线客服001',
                        time: '2018.11.11 12:01:20',
                        content: '您好，工号001很高兴为您服务，请问什么可以帮助您'
                    },
                    {
                        type: 'user',
                        username: '用户',
                        time: '2018.11.11 12:00:00',
                        content: '您好，我有一个退货售后问题'
                    },
                    {
                        type: 'service',
                        username: '在线客服001',
                        time: '2018.11.11 12:01:20',
                        content: '您好，工号001很高兴为您服务，请问什么可以帮助您您好，工号001很高兴为您服务，请问什么可以帮助您您好，工号001很高兴为您服务，请问什么可以帮助您'
                    },
                    {
                        type: 'user',
                        username: '用户',
                        time: '2018.11.11 12:00:00',
                        content: '您好，我有一个退货售后问题'
                    },
                    {
                        type: 'service',
                        username: '在线客服001',
                        time: '2018.11.11 12:01:20',
                        content: '您好，工号001很高兴为您服务，请问什么可以帮助您'
                    },
                    {
                        type: 'user',
                        username: '用户',
                        time: '2018.11.11 12:00:00',
                        content: '您好，我有一个退货售后问题'
                    },
                    {
                        type: 'service',
                        username: '在线客服001',
                        time: '2018.11.11 12:01:20',
                        content: '您好，工号001很高兴为您服务，请问什么可以帮助您'
                    },
                    {
                        type: 'user',
                        username: '用户',
                        time: '2018.11.11 12:00:00',
                        content: '您好，我有一个退货售后问题'
                    },
                    {
                        type: 'service',
                        username: '在线客服001',
                        time: '2018.11.11 12:01:20',
                        content: '您好，工号001很高兴为您服务，请问什么可以帮助您'
                    },
                    {
                        type: 'user',
                        username: '用户',
                        time: '2018.11.11 12:00:00',
                        content: '您好，我有一个退货售后问题'
                    },
                    {
                        type: 'service',
                        username: '在线客服001',
                        time: '2018.11.11 12:01:20',
                        content: '您好，工号001很高兴为您服务，请问什么可以帮助您'
                    },
                    {
                        type: 'user',
                        username: '用户',
                        time: '2018.11.11 12:00:00',
                        content: '您好，我有一个退货售后问题'
                    },
                    {
                        type: 'service',
                        username: '在线客服001',
                        time: '2018.11.11 12:01:20',
                        content: '您好，工号001很高兴为您服务，请问什么可以帮助您'
                    },
                    {
                        type: 'user',
                        username: '用户',
                        time: '2018.11.11 12:00:00',
                        content: '您好，我有一个退货售后问题'
                    },
                    {
                        type: 'service',
                        username: '在线客服001',
                        time: '2018.11.11 12:01:20',
                        content: '您好，工号001很高兴为您服务，请问什么可以帮助您'
                    },
                    {
                        type: 'user',
                        username: '用户',
                        time: '2018.11.11 12:00:00',
                        content: '您好，我有一个退货售后问题'
                    },
                ],
                caseInfo: {
                    case_id: '12345678',
                    creater: 'testName',
                    workline: 'workline',
                    createTime: '2018.11.11',
                    beTestTeam: 'beTestTeam',
                    testWorker: 'testWorker',
                    beTestWorker: 'beTestWorker',
                    testFinal: '— —',
                    commentFinal: 'commentFinal',
                    testGrade: '— —',
                    saleQues: 'saleQuestion',
                    workerType: 'workerType'
                },
                caseGrade: {
                    ceremony: 10,
                    sysopt: 20,
                    messagetrans: 20,
                    pinpoint: 20,
                    quickhandle: 30
                }
            }
        },
        mounted() {
            this.checklogin();
            // this.getContent();
        },
        methods: {
            checklogin: async function() {
                const res = await checkLogin();
                if(res.code === 200 && res.data!==false) {
                    this.username = res.data.usernick;
                }else{
                    this.$router.push('/login');
                }
            },
            getContent: async function() {
                const msgResp = await getCaseMsg({case_id: this.case_id});
                const infoResp = await getCaseInfo({case_id: this.case_id});
                const gradeResp = await getCaseGrade({case_id: this.case_id});
            }
        },
        computed: {
            case_id: function() {
                return this.$route.params.id;
            },
            totalGrade: function() {
                return this.caseGrade.ceremony+this.caseGrade.sysopt+this.caseGrade.messagetrans+this.caseGrade.pinpoint+this.caseGrade.quickhandle;
            }
        }
    }
</script>

<style scoped>
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
        height: 75%;
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
</style>