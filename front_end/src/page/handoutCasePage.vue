<template>
    <div class="handout-page">
        <el-row type="flex" align="middle" class="search-bar" justify="start">
            <span>ID查询</span>
            <el-input type="text" placeholder="请输入完整质检单ID" maxwidth="200px"></el-input>
            <el-button type="primary">搜索</el-button>
            <el-button type="primary">分配质检单</el-button>
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

                    <el-row align="middle">
                        <el-col :span="2">
                            <el-form-item>
                                <el-button type="primary" @click="onSearch">查询</el-button>
                            </el-form-item>
                        </el-col>

                        <el-col :span="2">
                            <el-form-item>
                                <el-button plain @click="resetSearch">重置</el-button>
                            </el-form-item>
                        </el-col>
                    </el-row>
                </el-form>
            </div>

            <div class="list-content">
                <el-table
                        :data="tableData"
                        height="250"
                        style="width: 100%"
                        header-cell-class-name="header-cell"
                        max-height="70%"
                >
                    <el-table-column
                            prop="date"
                            label="序号"
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
                    <el-col :span="3">
                        <span>共 {{ total }} 条</span>
                    </el-col>
                    <el-col :span="10">
                        <el-pagination
                                :current-page.sync="currentPage"
                                :page-size="10"
                                layout="prev, pager, next, jumper"
                                :total="total">
                        </el-pagination>
                    </el-col>
                </el-row>
            </div>
        </div>
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
                tableData: null
            }
        },
        methods: {
            onSearch: function() {

            },
            resetSearch: function() {

            }
        }
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
        margin: 10px;
    }

    .list-content {
        height: 70%;
        margin: 10px;
    }

    .list-footer {

    }
</style>