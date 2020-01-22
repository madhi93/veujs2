<template>
    <div class="content-wrapper" >
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Purchases</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active">Purchases</li>
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
                        <router-link tag="button" type="button"  :to="{ name:'createPurchase'}" class="btn btn-primary float-right">
                            <i class="fa fa-plus"></i>
                            Add Purchase
                        </router-link>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Purchase Table</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product Name </th>
                                            <th>Price</th>
                                            <th>Quantities</th>
                                            <th>Delivery Hub</th>
                                            <th>Created By</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(purchase , index) in purchases" :key="purchase.id">
                                            <td>{{ (index + 1) + (10 * (currentPage - 1)) }}</td>
                                            <td>
                                                <span v-if="purchase.product">{{ purchase.product.name }}</span>
                                            </td>
                                            <td>{{ purchase.price }}</td>
                                            <td>{{ purchase.quantity }} {{ purchase.unit }}</td>
                                            <td>
                                                <span v-if="purchase.hub">{{ purchase.hub.name }}</span>
                                            </td>
                                            <td>
                                                <span v-if="purchase.user">{{ purchase.user.fullname }}</span>
                                            </td>
                                            <td>
                                                <router-link tag="button" type="button"
                                                    class="btn btn-sm btn-icon btn-info"
                                                    :to="{ name:'editPurchase', params: { 'purchase_id': purchase.id }}">
                                                    <span class="fa fa-edit"></span>
                                                </router-link>
                                                <button type="button" class="btn btn-sm btn-icon btn-danger">
                                                    <span class="fa fa-trash"></span>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-show="!purchases.length">
                                            <td colspan="9" class="no-data-found-info">No admin purchases found</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer clearfix" v-if="totalPages > 1">
                                <paginate :page-count="totalPages" :page-range="3" :margin-pages="2"
                                    :click-handler="searchPurchases" :container-class="'pagination float-right'"
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
                purchases: [],
                totalItems: 0,
                currentPage: null,
                filter: {},
                form: {},
                totalPages: 0,
                purchase: {}
            }
        },
        mounted: function () {
            this.getpurchases();
        },
        methods: {
            getpurchases: function () {
                axios.get('/purchase/list')
                    .then(r => r.data)
                    .then((response) => {
                        this.purchases = response.data;
                        this.totalItems = response.totalItems;
                        this.currentPage = response.currentPage;
                        this.totalPages = Math.ceil(this.totalItems / 15);
                        this.filter = response.filter;
                    });
            },
            searchpurchases: function (page) {
                axios.post('/purchase/search/' + page, this.filter)
                    .then(r => r.data)
                    .then((response) => {
                        this.purchases = response.data;
                        this.totalItems = response.totalItems;
                        this.currentPage = response.currentPage;
                        this.totalPages = Math.ceil(this.totalItems / 15);
                        //this.filter = response.filter;
                    });
            },
            createPurchase: function () {
                this.$router.push({
                    path: '/purchase/create'
                });
            }
        }
    }

</script>
