<template>
    <div class="content-wrapper" >
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Products</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active">Products</li>
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
                        <router-link tag="button" type="button"  :to="{ name:'createProduct'}" class="btn btn-primary float-right">
                            <i class="fa fa-plus"></i>
                            Add Product
                        </router-link>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Product Table</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product Name </th>
                                            <th>Description</th>
                                            <th>Quantities</th>
                                            <th>Price</th>
                                            <th>Selling Price</th>
                                            <th>In Stock</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(product , index) in products" :key="product.id">
                                            <td>{{ (index + 1) + (10 * (currentPage - 1)) }}</td>
                                            <td>{{ product.name }}</td>
                                            <td>{{ product.description }}</td>
                                            <td>{{ product.quantity }} {{ product.unit }}</td>
                                            <td>{{ product.price }}</td>
                                            <td>{{ product.sale_price }}</td>
                                            <td>
                                                <span v-if="product.in_stock == 1" class="badge badge-success">
                                                    In Stock
                                                </span>
                                                <span v-if="product.in_stock == 0" class="badge badge-danger">
                                                    Out Of Stock
                                                </span>
                                            </td>
                                            <td>
                                                <router-link tag="button" type="button"
                                                    class="btn btn-sm btn-icon btn-info"
                                                    :to="{ name:'editProduct', params: { 'product_id': product.id }}">
                                                    <span class="fa fa-edit"></span>
                                                </router-link>
                                                <button type="button" class="btn btn-sm btn-icon btn-danger">
                                                    <span class="fa fa-trash"></span>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-show="!products.length">
                                            <td colspan="9" class="no-data-found-info">No admin products found</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer clearfix" v-if="totalPages > 1">
                                <paginate :page-count="totalPages" :page-range="3" :margin-pages="2"
                                    :click-handler="searchProducts" :container-class="'pagination float-right'"
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
                products: [],
                totalItems: 0,
                currentPage: null,
                filter: {},
                form: {},
                totalPages: 0,
                product: {}
            }
        },
        mounted: function () {
            this.getproducts();
        },
        methods: {
            getproducts: function () {
                axios.get('/product/list')
                    .then(r => r.data)
                    .then((response) => {
                        this.products = response.data;
                        this.totalItems = response.totalItems;
                        this.currentPage = response.currentPage;
                        this.totalPages = Math.ceil(this.totalItems / 15);
                        this.filter = response.filter;
                    });
            },
            searchproducts: function (page) {
                axios.post('/product/search/' + page, this.filter)
                    .then(r => r.data)
                    .then((response) => {
                        this.products = response.data;
                        this.totalItems = response.totalItems;
                        this.currentPage = response.currentPage;
                        this.totalPages = Math.ceil(this.totalItems / 15);
                        //this.filter = response.filter;
                    });
            },
            createProduct: function () {
                this.$router.push({
                    path: '/product/create'
                });
            }
        }
    }

</script>
