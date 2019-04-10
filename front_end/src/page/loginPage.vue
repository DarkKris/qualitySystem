<template>
    <el-container id="app">
        <el-header>
            <top-nav :user-name="username"></top-nav>
        </el-header>
        <el-container>
            <el-main style="height: 100%;">
                <div class="login-page">
                    <div class="login-from">
                        <div class="from-content">
                            <el-form :model="ruleForm" status-icon :rules="rules" ref="ruleForm" label-width="100px" class="demo-ruleForm">
                                <el-form-item label="账号" prop="username" class="input-label" label-width="70px">
                                    <el-input type="text" v-model="ruleForm.username" autocomplete="off" placeholder="请输入8位数账号" maxlength="8"></el-input>
                                </el-form-item>
                                <el-form-item label="密码" prop="password" class="input-label" label-width="70px">
                                    <el-input type="password" v-model="ruleForm.password" autocomplete="off" placeholder="请输入密码"></el-input>
                                </el-form-item>
                                <el-button type="primary" @click="submitForm('ruleForm')" style="width: 100%">登  录</el-button>
                            </el-form>
                        </div>
                    </div>
                </div>
            </el-main>
        </el-container>
    </el-container>

</template>

<script>
    import TopNav from "../components/TopNav";
    import { login,checkLogin } from "../api/getData"

    export default {
        name: "login-page",
        components: { TopNav },
        data() {
            var validateUsername = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('请输入账号'));
                } else if (value.length !== 8) {
                    callback(new Error('数字账号应为8位'));
                } else {
                    callback();
                }
            };
            var validatePass = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('请输入密码'));
                } else {
                    callback();
                }
            };
            return {
                ruleForm: {
                    username: '',
                    password: '',
                },
                rules: {
                    username: [
                        { validator: validateUsername, trigger: 'blur' }
                    ],
                    password: [
                        { validator: validatePass, trigger: 'blur' }
                    ]
                },
                username: '未命名',
            };
        },
        methods: {
            submitForm: function(formName) {
                this.$refs[formName].validate(async (valid) => {
                    if (valid) {
                        const res = await login({username: this.ruleForm.username, password: this.ruleForm.password});
                        if(res.code === 200)
                        {
                            if(res.data.admin){
                                this.$router.push('admin');
                            }else{
                                this.$router.push('worker');
                            }
                        }else{
                            this.alertMessage('账号或密码输入错误，请重新输入。','error');
                        }
                    } else {
                        this.alertMessage('请输入账号、密码。','error');
                    }
                });

            },
            checklogin: async function() {
                const res = await checkLogin();
                if(res.code === 200 && res.data!==false) {
                    if(res.data === 'admin') {
                        this.$router.push('admin');
                    }else{
                        this.$router.push('worker');
                    }
                }else{
                    console.log(res);
                }
            }
        },
        mounted() {
            // this.checklogin();
        }
    }
</script>

<style>
    .login-page {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
        margin-top: 40px;
    }

    .login-from {
        background: white;
        height: 230px;
        width: 530px;
        border-radius: 2px;
        border: 1px solid rgba(0,0,0,0.1);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .from-content {
        height: 150px;
        width: 370px;
        margin-top: 10px;
        margin-bottom: 20px;
    }

    .el-form-item__label {
        text-align: left !important;
    }

    .el-input >input {
        height: 33px !important;
    }

    .el-form .el-button {
        padding: 8px 20px !important;
    }
</style>