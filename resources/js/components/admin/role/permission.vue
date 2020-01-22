<template>
    <div class="content-wrapper" >
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Create Permission</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Dashbaord</a></li>
                            <li class="breadcrumb-item active">Permission List</li>
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
                    <div class="col-md-12">
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
                                <h3 class="card-title" style="text-transform:capitalize;">{{role.name}} role Permissions</h3>
                            </div>
                            <!-- /.card-header -->
                            <form role="form" ref="editPermissionFrom" name="editPermissionFrom"
                                v-on:submit.prevent="update">

                                
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-6" v-for="permission in permissions"
                                            :key="permission.id">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" :id="permission.name" v-model="role.role_permissions" :value="permission.name">
                                                <label :for="permission.name" class="custom-control-label" style="text-transform:capitalize;">{{permission.name}}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <div class="card-footer">
                                    <button type="button" @click="listRoles()" class="btn btn-default">Cancel</button>
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
<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                role: {},
                permissions: [],
                errors: []
            }
        },
        mounted: function () {
            this.getPermissions();
            this.getRolePermissions();
        },
        methods: {
            getRolePermissions: function () {
                axios.get('/admin/role/fetch/' + this.$route.params.role_id)
                    .then(r => r.data)
                    .then((response) => {
                        this.role = response.data;
                    });
            },
            getPermissions: function () {
                axios.get('/admin/permission/list')
                    .then(r => r.data)
                    .then((response) => {
                        this.permissions = response.data;
                    });
            },
            update: function (page) {
                axios.post('/admin/role/' + this.$route.params.role_id + '/update/permissions', this.role.role_permissions)
                    .then(r => r.data)
                    .then((response) => {
                        this.role = response.data;
                        notification(response.title, response.message, 'success');
                    }).catch((error) => {
                        //notification('Upload Invoice' , "Upload Invoice Faild!" , 'danger');
                    });
            },
            listRoles: function () {
                this.$router.push({
                    path: '/admin/roles'
                });
            }
        }
    }

</script>
