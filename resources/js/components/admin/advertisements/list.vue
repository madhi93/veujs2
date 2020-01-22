<template>
    <div class="content-wrapper" >
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Advertisements</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active">Advertisements</li>
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
                        <router-link tag="button" type="button"  :to="{ name:'createAdvertisement'}" class="btn btn-primary float-right">
                            <i class="fa fa-plus"></i>
                            Add Advertisement
                        </router-link>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Advertisement Table</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Short Description</th>
                                            <th>Type</th>
                                            <th>Start At</th>
                                            <th>Ends At</th>
                                            <th>Shop</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(advertisement , index) in advertisements" :key="advertisement.id">
                                            <td>{{ (index + 1) + (10 * (currentPage - 1)) }}</td>
                                            <td>{{ advertisement.title }}</td>
                                            <td>{{ advertisement.short_description }}</td>
                                            <td>{{ advertisement.type }}</td>
                                            <td>{{ advertisement.start_at }}</td>
                                            <td>{{ advertisement.ends_at }}</td>
                                            <td>
                                                <span v-if="advertisement.shop">{{ advertisement.shop.name  }}</span>
                                            </td>
                                            <td>
                                                <router-link tag="button" type="button"
                                                    class="btn btn-sm btn-icon btn-info"
                                                    :to="{ name:'editAdvertisement', params: { 'advertisement_id': advertisement.id }}">
                                                    <span class="fa fa-edit"></span>
                                                </router-link>
                                                <button type="button" class="btn btn-sm btn-icon btn-danger">
                                                    <span class="fa fa-trash"></span>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-show="!advertisements.length">
                                            <td colspan="9" class="no-data-found-info">No Advertisements found</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer clearfix" v-if="totalPages > 1">
                                <paginate :page-count="totalPages" :page-range="3" :margin-pages="2"
                                    :click-handler="searchAdvertisements" :container-class="'pagination float-right'"
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
                advertisements: [],
                totalItems: 0,
                currentPage: null,
                filter: {},
                form: {},
                totalPages: 0,
                Advertisement: {}
            }
        },
        mounted: function () {
            this.getAdvertisements();
        },
        methods: {
            getAdvertisements: function () {
                axios.get('/advertisement/list')
                    .then(r => r.data)
                    .then((response) => {
                        this.advertisements = response.data;
                        this.totalItems = response.totalItems;
                        this.currentPage = response.currentPage;
                        this.totalPages = Math.ceil(this.totalItems / 15);
                        this.filter = response.filter;
                    });
            },
            searchAdvertisements: function (page) {
                axios.post('/advertisement/search/' + page, this.filter)
                    .then(r => r.data)
                    .then((response) => {
                        this.advertisements = response.data;
                        this.totalItems = response.totalItems;
                        this.currentPage = response.currentPage;
                        this.totalPages = Math.ceil(this.totalItems / 15);
                        //this.filter = response.filter;
                    });
            },
            createAdvertisement: function () {
                this.$router.push({
                    path: '/advertisements/create'
                });
            }
        }
    }

</script>
