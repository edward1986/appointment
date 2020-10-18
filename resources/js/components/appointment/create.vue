<template>
    <div>
        <el-row>
            <el-col :span="24">
                <el-card shadow="never">
                    <div slot="header">
                        <span>{{$route.params.id ? 'Edit' : 'Create'}} Appointment</span>
                    </div>
                    <el-form ref="form" @submit.native.prevent="onSubmit" :model="form" label-width="120px">

                        <el-form-item label="Title" :class="errors.title ? 'is-error is-required' : ''" prop="title">
                            <el-input placeholder="Enter Name" type="text" v-model="form.title"
                                      auto-complete="off"></el-input>
                            <div v-if="errors.title" v-for="error in errors.title" class="el-form-item__error">
                                {{error}}
                            </div>
                        </el-form-item>
                        <el-form-item label="Content" :class="errors.body ? 'is-error is-required' : ''" prop="body">
                            <el-input placeholder="Enter Name" type="textarea" v-model="form.body"
                                      auto-complete="off"></el-input>
                            <div v-if="errors.body" v-for="error in errors.body" class="el-form-item__error">
                                {{error}}
                            </div>
                        </el-form-item>
                        <el-form-item prop="send_date" label="Date due for return">
                            <el-date-picker v-model="form.send_date" type="datetime" placeholder="Pick a day"></el-date-picker>
                            <div v-if="errors.send_date" v-for="error in errors.send_date" class="el-form-item__error">
                                {{error}}
                            </div>
                        </el-form-item>
                        <el-form-item>
                            <el-button :disabled="disabled" type="primary" @click="onSubmit('form')">{{$route.params.id
                                ? 'Edit' : 'Create'}} Appointment
                            </el-button>
                            <el-button @click="onCancel">Cancel</el-button>
                        </el-form-item>
                    </el-form>
                </el-card>
            </el-col>
        </el-row>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                form: {
                    title: '',
                    body: '',
                    send_date: '',
                    item: 'later'
                },
                errors: {},
                loading: false,
                disabled: false,
                send_now: true,

            }
        },
        beforeRouteEnter (to, from, next) {
            if (to.params.id) {
                axios.get(`/api/appointments/${to.params.id}`).then(function (response) {
                    next(vm => vm.setData(response.data))
                }).catch(function (error) {
                    if (error.response.statusText) {
                        next(vm => vm.$message({
                            type: 'error',
                            message: error.response.statusText
                        }))
                    }
                })
            } else {
                next()
            }
        },
        beforeRouteUpdate (to, from, next) {
            var vm = this
            if (to.params.id) {
                vm.errors = {}
                axios.get(`/api/appointments/${to.params.id}`).then(function (response) {
                    vm.setData(response.data)
                    next()
                }).catch(function (error) {
                    if (error.response.statusText) {
                        vm.$message({
                            type: 'error',
                            message: error.response.statusText
                        })
                    }
                })
            } else {
                next()
            }
        },
        methods: {
            setData(response){
                var vm = this
                vm.form = response
            },
            onCancel(){
                this.$router.push({name: 'view-appointment'})
            },
            onSubmit() {
                var vm = this
                this.$refs.form.validate((valid) => {
                    if (valid) {
                        vm.disabled = true
                        vm.errors = {}
                        let id = vm.$route.params.id;
                        axios[id ? 'put' : 'post'](`/api/appointments${id ? `/${id}` : ''}`, vm.form).then(function () {
                            vm.disabled = false
                            vm.errors = {}
                            vm.$message({
                                type: 'success',
                                message: 'Appointment has been created'
                            })
                        }).catch(function (error) {
                            vm.disabled = false
                            if (error.response.data.errors && error.response.data.message) {
                                vm.errors = error.response.data.errors;
                                vm.$message({message: error.response.data.message, type: 'error'})
                            }
                        })
                    }
                })
            }
        }
    }
</script>
