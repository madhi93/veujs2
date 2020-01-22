<template>
    <div class="content-wrapper" >
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Purchase</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Dashbaord</a></li>
                            <li class="breadcrumb-item active">Purchase List</li>
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
                                <h3 class="card-title">Edit Purchase Form</h3>
                            </div>
                            <!-- /.card-header -->
                            <form role="form" ref="editPurchaseFrom" name="editPurchaseFrom" v-on:submit.prevent="editPurchase">
                                <div class="card-body">
                                   <div class="form-group">
                                        <label for="product_id">Select Product</label>
                                        <select name="product_id" id="product_id" class="form-control" v-model="form.product_id">
                                            <option value="" disabled>Select Purchase Product</option>
                                            <option v-for="product in products" :key="product.id" :value="product.id">
                                                {{ product.name }} - {{ product.category.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="hub_id">Select Delivery Hub</label>
                                        <select name="hub_id" id="hub_id" class="form-control" v-model="form.hub_id">
                                            <option value="" disabled>Select Delivery Hub</option>
                                            <option v-for="hub in hubs" :key="hub.id" :value="hub.id">
                                                {{ hub.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="price">Price</label>
                                        <input type="text" class="form-control" name="price"
                                            v-model="form.price" placeholder="Purchase price" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="quantity">Quantity</label>
                                        <input type="text" class="form-control" name="quantity"
                                            v-model="form.quantity" placeholder="Total quantities" required>
                                    </div>   

                                    <!-- /.box-body -->
                                </div>
                                <div class="card-footer">
                                    <button type="button" @click="listPurchase()" class="btn btn-default">Cancel</button>
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
                form: {},
                errors: null,
                products: [],
                hubs: [],
            }
        },
        mounted: function () {
            this.getPurchase();
            this.getProducts();
            this.getHubs();
        },
        methods: {
            getProducts: function() {
                axios.get('/fetch/products')
                    .then(r => r.data)
                    .then((response) => {
                        this.products = response.data;
                    });
            },
            getHubs: function() {
                axios.get('/fetch/delivery-hubs')
                    .then(r => r.data)
                    .then((response) => {
                        this.hubs = response.data;
                    });
            },
            getPurchase: function () {
                axios.get('/purchase/fetch/' + this.$route.params.purchase_id)
                    .then(r => r.data)
                    .then((response) => {
                        this.form = response.data;
                    });
            },
            editPurchase: function () {
                axios.post('/purchase/edit/' + this.$route.params.purchase_id,this.form)
                    .then(r => r.data)
                    .then((response) => {
                        this.errors = null;
                        this.listPurchase();
                    }).catch((error) => {
                        this.errors = typeof error.response.data.errors !== 'undefined' ? error.response.data
                            .errors : null;
                    });
                //hidebtnLoader();
            },
            listPurchase: function () {
                this.$router.push({
                    path: '/purchases'
                });
            }
        }
    }

</script>
