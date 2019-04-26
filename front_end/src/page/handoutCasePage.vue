<template>
    <div class="handout-page">
        <el-row type="flex" align="middle" class="search-bar" justify="start">
            <el-col :span="1"><span class="search-bar-title">ID查询</span></el-col>
            <el-col :span="4"><el-input type="text" placeholder="请输入完整质检单ID" style="margin-left:20px;"/></el-col>
            <el-col :span="19" id="buttons-group">
                <el-button type="primary" id="search-button">搜索</el-button>
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
            <div class="list-filter">
                <el-form
                        label-width="90px"
                >
                    <el-row align="middle">
                        <el-col :span="8">
                            <el-form-item label="业务线">
                                <el-select v-model="filter.workline">
                                    <el-option label="区域一" value="shanghai"></el-option>
                                    <el-option label="区域二" value="beijing"></el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>

                        <el-col :span="8">
                            <el-form-item label="被质检团队">
                                <el-select v-model="filter.beTestTeam">
                                    <el-option label="区域一" value="shanghai"></el-option>
                                    <el-option label="区域二" value="beijing"></el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>

                        <el-col :span="8">
                            <el-form-item label="创建人">
                                <el-select v-model="filter.createUser">
                                    <el-option label="区域一" value="shanghai"></el-option>
                                    <el-option label="区域二" value="beijing"></el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                    </el-row>

                    <el-row align="middle">
                        <el-col :span="8">
                            <el-form-item label="质检团队">
                                <el-select v-model="filter.testTeam">
                                    <el-option label="区域一" value="shanghai"></el-option>
                                    <el-option label="区域二" value="beijing"></el-option>
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
                        <el-button type="primary" @click="onSearch">查询</el-button>
                        <el-button plain @click="resetSearch">重置</el-button>
                        <el-button plain @click="excelOut" v-if="$route.path == '/admin/completeCase'">导出</el-button>
                    </el-row>
                </el-form>
            </div>

            <div class="list-content">
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
                        <!-- TODO -->
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
                            prop="address"
                            label="创建时间"
                            sortable
                    >
                    </el-table-column>
                    <el-table-column
                            prop="address"
                            label="创建人"
                    >
                    </el-table-column>
                    <el-table-column
                            prop="address"
                            label="被质检人"
                    >
                    </el-table-column>
                    <el-table-column
                            prop="address"
                            label="被质检团队"
                    >
                    </el-table-column>
                </el-table>
                <el-row class="list-footer">
                    <el-col :span="3" style="margin-top: 3px;">
                        <span>共 {{ total }} 条</span>
                    </el-col>
                    <el-col :span="12">&nbsp;</el-col>
                    <el-col :span="9">
                        <el-pagination
                                :current-page.sync="currentPage"
                                :page-size="10"
                                layout="prev, pager, next, jumper"
                                :total="total"
                        />
                    </el-col>
                </el-row>
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
                <el-form :model="dialog">
                    <el-form-item label="业务线" :label-width="dialog.width">
                        <el-select v-model="dialog.workLine">
                            <el-option label="全部" value="0" />
                            <el-option label="在线" value="1" />
                            <el-option label="热线" value="2" />
                        </el-select>
                    </el-form-item>
                    <el-form-item label="被质检单位" :label-width="dialog.width">
                        <el-select v-model="dialog.beTestingCompany">
                            <el-option label="全部" value="0" />
                        </el-select>
                    </el-form-item>
                    <el-form-item label="评价结果" :label-width="dialog.width">
                        <el-select v-model="dialog.commentCompany" multiple placeholder="请选择评价结果">
                            <el-option label="满意" value="1" />
                            <el-option label="一般" value="0" />
                            <el-option label="不满意" value="-1" />
                        </el-select>
                    </el-form-item>
                    <el-form-item label="问题类型" :label-width="dialog.width">
                        <el-select v-model="dialog.problemType">
                            <el-option label="全部" value="0" />
                            <el-option label="售后问题" value="1" />
                            <el-option label="运费问题" value="2" />
                            <el-option label="商家问题" value="3" />
                            <el-option label="一般问题" value="4" />
                        </el-select>
                    </el-form-item>
                    <el-form-item label="客服类型" :label-width="dialog.width">
                        <el-select v-model="dialog.serviceType">
                            <el-option label="全部" value="0" />
                            <el-option label="活动客服" value="1" />
                            <el-option label="假货客服" value="2" />
                            <el-option label="高级客服" value="3" />
                            <el-option label="运费客服" value="4" />
                        </el-select>
                    </el-form-item>
                    <el-form-item label="数量" :label-width="dialog.width" style="margin-top: 50px;">
                        <el-input v-model="dialog.name" autocomplete="off" placeholder="请输入数量，不得超过case总数" style="width: 300px;"/>
                        <el-popover
                                placement="right"
                                width="100"
                                trigger="click"
                        >
                            <div
                                    v-loading="loading"
                                    element-loading-background="rgba(255,255,255)"
                            >
                                <span>筛选总数：{{caseTotal}}</span>
                            </div>
                            <a slot="reference" style="margin-left: 20px;font-size:14px;color:#4a90e2;" @click="popoverCount">查看case总数</a>
                        </el-popover>
                    </el-form-item>
                    <el-form-item label="分配方式" :label-width="dialog.width">
                        <el-select v-model="dialog.handoutType">
                            <el-option label="平均分配" value="0" />
                            <el-option label="手动分配" value="1" />
                        </el-select>
                        <div class="handout-children">
                            <!--平均分配-->
                            <div v-if="dialog.handoutType==0">
                                <!-- TODO select-->
                                <el-select v-model="value5" multiple placeholder="请选择质检员">
                                    <el-option
                                    v-for="item in options"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                                    </el-option>
                                </el-select>
                            </div>
                            <!--手动分配-->
                            <div v-else>
                                <div v-for="(item,index) in dialog.handoutData" style="display: flex" :key="index">
                                    <el-select v-model="item.worker_id" placeholder="请选择质检员">
                                        <el-option label="test" value="0"/>
                                    </el-select>
                                    <span>&nbsp;分配&nbsp;</span>
                                    <el-input v-modl="item.caseNum" style="width: 60px;"/>
                                    <span>&nbsp;单</span>
                                </div>
                                <el-button @click="addArrs" plain>增加</el-button>
                            </div>
                        </div>
                    </el-form-item>
                </el-form>
                <div slot="footer" class="dialog-footer">
                    <el-button type="primary" @click="dialog.visible = false">确 定</el-button>
                    <el-button plain @click="dialog.visible = false">取 消</el-button>
                </div>
            </el-dialog>
        </keep-alive>
    </div>
</template>

<script>
    export default {
        name: "handout-case-page",
        data() {
            return {
                filter: {
                    workline: '',
                    beTestTeam: '',
                    createUser: '',
                    testTeam: '',
                    time: ''
                },
                total: 90,
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
                tableData: null,
                dialog: {
                    width: '101px',
                    visible: false,
                    workLine: '0',
                    beTestingCompany: '0',
                    commentCompany: ["1","0","-1"],
                    problemType: '0',
                    serviceType: '0',
                    handoutType: '0',
                    handoutData: [
                        {
                            'worker_id': '',
                            'caseNum': 0
                        }
                    ]
                },
                caseTotal: 100,
                loading: true
            }
        },
        methods: {
            onSearch: function() {

            },
            resetSearch: function() {

            },
            excelOut: function() {

            },
            handoutClick: function() {
                this.dialog.visible = true;
            },
            popoverCount: function() {
                this.loading=true;
                // TODO await api
                this.loading=false;
            },
            addArrs: function() {
                this.dialog.handoutData.push({'worker_id':'','caseNum':0});
            }
        },
    }
</script>

<style>
    .list-filter .el-form-item__label {
        text-align: right !important;
    }

    .handout-page {
        color: rgb(90,90,90);
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
        padding: 20px;
    }

    .list-content {
        height: calc(92% - 132px);
        margin: 10px;
    }

    .list-footer {
        margin-top: 10px;
        margin-left: 5px;
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

    .list-filter .el-col {
        height: 50px !important;
    }
</style>