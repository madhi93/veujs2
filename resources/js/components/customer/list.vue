<template>
    <div class="content-wrapper" >
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Customers</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active">Customers</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row" style="margin-bottom:20px;">
                    <div class="col-md-12">
                        <router-link tag="button" type="button"  :to="{ name:'createUser'}" class="btn btn-primary float-right">
                            <i class="fa fa-plus"></i>
                            Add User
                        </router-link>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">User Table</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Full Name </th>
                                            <th>Email</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(user , index) in users" :key="user.id">
                                            <td>{{ (index + 1) + (10 * (currentPage - 1)) }}</td>
                                            <td>{{ user.fullname }}</td>
                                            <td>{{ user.email }}</td>
                                            <td>
                                                <router-link tag="button" type="button"
                                                    class="btn btn-sm btn-icon btn-info"
                                                    :to="{ name:'editUser', params: { 'user_id': user.id }}">
                                                    <span class="fa fa-edit"></span>
                                                </router-link>
                                                <button type="button" class="btn btn-sm btn-icon btn-danger">
                                                    <span class="fa fa-trash"></span>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-show="!users.length">
                                            <td colspan="7" class="no-data-found-info">No admin users found</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer clearfix" v-if="totalPages > 1">
                                <paginate :page-count="totalPages" :page-range="3" :margin-pages="2"
                                    :click-handler="searchUsers" :container-class="'pagination float-right'"
                                    :page-class="'page-item'">
                                </paginate>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
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
                users: [],
                totalItems: 0,
                currentPage: null,
                filter: {},
                form: {},
                totalPages: 0,
                user: {}
            }
        },
        mounted: function () {
            this.getusers();
        },
        methods: {
            getusers: function () {
                axios.get('/customer/list')
                    .then(r => r.data)
                    .then((response) => {
                        this.users = response.data;
                        this.totalItems = response.totalItems;
                        this.currentPage = response.currentPage;
                        this.totalPages = Math.ceil(this.totalItems / 15);
                        this.filter = response.filter;
                    });
            },
            searchusers: function (page) {
                axios.post('/customer/search/' + page, this.filter)
                    .then(r => r.data)
                    .then((response) => {
                        this.users = response.data;
                        this.totalItems = response.totalItems;
                        this.currentPage = response.currentPage;
                        this.totalPages = Math.ceil(this.totalItems / 15);
                        //this.filter = response.filter;
                    });
            },
            createUser: function () {
                this.$router.push({
                    path: '/customers/create'
                });
            }
        }
    }

</script>
