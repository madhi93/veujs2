<template>
    <div class="content-wrapper" >
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Create Role</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Dashbaord</a></li>
                            <li class="breadcrumb-item active">Role List</li>
                            <li class="breadcrumb-item active">Update</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-6 offset-md-3">
                        <div v-if="errors !== null" class="alert content-alert content-alert--danger mt-4" role="alert">
                            <div class="content-alert__info">
                                <span class="content-alert__info-icon ua-icon-warning"></span>
                            </div>
                            <div class="content-alert__content">
                                <div class="content-alert__heading"></div>
                                <div class="content-alert__message">
                                    <ul class="custom-alert__list" v-for="(values , key) in errors" :key="key">
                                        <li v-for="(value , index) in values" :key="index">{{ value }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- general form elements -->
                        <div class="card card-default">
                            <div class="card-header">
                                <h3 class="card-title">Update Role Form</h3>
                            </div>
                            <!-- /.card-header -->
                            <form role="form" ref="editRoleFrom" name="editRoleFrom" v-on:submit.prevent="editRole">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Role Name</label>
                                        <input type="text" class="form-control" name="name" v-model="form.name"
                                            placeholder="Role Name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="guard_name">Guard Name</label>
                                        <input type="text" class="form-control" name="guard_name" v-model="form.guard_name"
                                            placeholder="Guard Name">
                                    </div>

                                    <!-- /.box-body -->
                                </div>
                                <div class="card-footer">
                                    <button type="button" @click="listRole()" class="btn btn-default">Cancel</button>
                                    <button type="submit" class="btn btn-success float-right">Update</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.row -->
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
</template>
<style scope>
    .text-red {
        color: red !important;
        padding-left: 10px;
    }

</style>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                form: {},
                errors: null,
            }
        },
        mounted: function () {
            this.getRole();
        },
        methods: {
            getRole: function () {
                axios.get('/admin/role/fetch/' + this.$route.params.role_id)
                    .then(r => r.data)
                    .then((response) => {
                        this.form = response.data;
                    });
            },
            editRole: function () {
                axios.post('/admin/role/edit/' + this.$route.params.role_id, this.form)
                    .then(r => r.data)
                    .then((response) => {
                        this.errors = null;
                        this.listRole();
                    }).catch((error) => {
                        this.errors = typeof error.response.data.errors !== 'undefined' ? error.response.data
                            .errors : null;
                    });
                //hidebtnLoader();
            },
            listRole: function () {
                this.$router.push({
                    path: '/admin/roles'
                });
            }
        }
    }

</script>
