<template>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Shop Categories</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active">Shop Categories</li>
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
                        <router-link tag="button" type="button"  :to="{ name:'createShopCategory'}" class="btn btn-primary float-right">
                            <i class="fa fa-plus"></i>
                            Add Category
                        </router-link>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Category Table</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Category Name </th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(category , index) in categories" :key="category.id">
                                            <td>{{ (index + 1) + (10 * (currentPage - 1)) }}</td>
                                            <td>{{ category.name }}</td>
                                            <td>{{ category.description }}</td>
                                            <td>
                                                <router-link tag="button" type="button"
                                                    class="btn btn-sm btn-icon btn-info"
                                                    :to="{ name:'editShopCategory', params: { 'category_id': category.id }}">
                                                    <span class="fa fa-edit"></span>
                                                </router-link>
                                                <button type="button" class="btn btn-sm btn-icon btn-danger">
                                                    <span class="fa fa-trash"></span>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-show="!categories.length">
                                            <td colspan="7" class="no-data-found-info">No shop category found</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer clearfix" v-if="totalPages > 1">
                                <paginate :page-count="totalPages" :page-range="3" :margin-pages="2"
                                    :click-handler="searchCategorys" :container-class="'pagination float-right'"
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
                categories: [],
                totalItems: 0,
                currentPage: null,
                filter: {},
                form: {},
                totalPages: 0,
                category: {}
            }
        },
        mounted: function () {
            this.getcategories();
        },
        methods: {
            getcategories: function () {
                axios.get('/shop-category/list')
                    .then(r => r.data)
                    .then((response) => {
                        this.categories = response.data;
                        this.totalItems = response.totalItems;
                        this.currentPage = response.currentPage;
                        this.totalPages = Math.ceil(this.totalItems / 15);
                        this.filter = response.filter;
                    });
            },
            searchcategories: function (page) {
                axios.post('/shop-category/search/' + page, this.filter)
                    .then(r => r.data)
                    .then((response) => {
                        this.categories = response.data;
                        this.totalItems = response.totalItems;
                        this.currentPage = response.currentPage;
                        this.totalPages = Math.ceil(this.totalItems / 15);
                        //this.filter = response.filter;
                    });
            },
            createCategory: function () {
                this.$router.push({
                    path: '/shop-categories/create'
                });
            }
        }
    }

</script>
