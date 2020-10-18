<template>
    <div>
        <grid-view :columns="columns"
                   :data="data"
                   create-name="Add Appointment"
                   on-delete="/api/appointments"
                   on-edit-name="edit-appointment"
                   on-create-name="create-appointment"
                   @delete="data.data.splice($event, 1)"
        ></grid-view>
    </div>
</template>
<style>
</style>
<script>
    import GridView from './../Table/Grid.vue'
    export default{
        data(){
            return {
                columns: [
                    {
                        label: 'Title',
                        prop: 'appointment.title',
                        sort: true
                    }, {
                        label: 'User Name',
                        prop: 'user.name',
                        sort: true
                    }, {
                        label: 'Due',
                        prop: 'date_due_for_return',
                        sort: true
                    }
                ],
                data: []
            }
        },
        components: {
            GridView,
        },
        beforeRouteEnter (to, from, next) {
            axios.get(`/api/appointments`, {params: to.query}).then(function (response) {
                next(vm => vm.setData(response.data))
            })
        },
        beforeRouteUpdate (to, from, next) {
            var vm = this
            axios.get(`/api/appointments`, {params: to.query}).then(function (response) {
                vm.setData(response.data)
                next()
            })
        },
        methods: {
            setData(response){
                this.data = response
            },
        }
    }
</script>
