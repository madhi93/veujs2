<template>
    <div class="content-wrapper" >
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Create Product</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Dashbaord</a></li>
                            <li class="breadcrumb-item active">Product List</li>
                            <li class="breadcrumb-item active">Create</li>
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
                                <h3 class="card-title">Create Product Form</h3>
                            </div>
                            <!-- /.card-header -->
                            <form role="form" ref="addProductFrom" name="addProductFrom" v-on:submit.prevent="createProduct">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="category_id">Select Category</label>
                                        <select name="category_id" id="category_id" class="form-control" v-model="form.category_id">
                                            <option value="1">dfdsfdsf</option>
                                            <option value="2">dsfsdfsdf</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Product Name</label>
                                        <input type="text" class="form-control" name="name"
                                            v-model="form.name" placeholder="Product Product Name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea type="text" class="form-control" name="description"
                                            v-model="form.description" placeholder="Product Description" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="price">Product Price</label>
                                        <input type="text" class="form-control" name="price"
                                            v-model="form.price" placeholder="Product price" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="sale_price">Selling Price</label>
                                        <input type="text" class="form-control" name="sale_price"
                                            v-model="form.sale_price" placeholder="Product selling price" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="unit">Unit</label>
                                        <input type="text" class="form-control" name="unit"
                                            v-model="form.unit" placeholder="Product Unit" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="quantity">Unit Value</label>
                                        <input type="text" class="form-control" name="quantity"
                                            v-model="form.quantity" placeholder="Product Unit value" required>
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control  custom-switch">
                                            <input class="custom-control-input" type="checkbox" id="in_stock" v-model="form.in_stock">
                                            <label for="in_stock" class="custom-control-label">In Stock</label>
                                        </div>
                                    </div>

                                    

                                    <!-- /.box-body -->
                                </div>
                                <div class="card-footer">
                                    <button type="button" @click="listProduct()" class="btn btn-default">Cancel</button>
                                    <button type="submit" class="btn btn-success float-right">Create</button>
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
            }
        },
        mounted: function () {
        },
        methods: {
            createProduct: function () {
                axios.post('/product/create', this.form)
                    .then(r => r.data)
                    .then((response) => {
                        this.errors = null;
                        this.listProduct();
                    }).catch((error) => {
                        this.errors = typeof error.response.data.errors !== 'undefined' ? error.response.data
                            .errors : null;
                    });
                //hidebtnLoader();
            },
            listProduct: function () {
                this.$router.push({
                    path: '/products'
                });
            }
        }
    }

</script>
