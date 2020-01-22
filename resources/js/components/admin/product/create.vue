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
                            <form role="form" ref="addProductFrom" name="addProductFrom"
                                v-on:submit.prevent="createProduct">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="category_id">Select Category</label>
                                        <v-select label="name" :filterable="false" :options="categories"
                                            @search="searchCategories" @change="selectedCategory" v-model="category"
                                            style="width:100%;">
                                            <template slot="no-options">
                                                type to search product categories..
                                            </template>
                                            <template slot="option" slot-scope="option">
                                                <div class="d-center">
                                                    {{ option.name }}
                                                </div>
                                            </template>
                                            <template slot="selected-option" slot-scope="option">
                                                <div class="selected d-center">
                                                    {{ option.name }}
                                                </div>
                                            </template>
                                        </v-select>
                                        <div id="pick-avatar" class="image-upload-box">
                                            <div class="overlay">
                                                <font-awesome-icon class="white-icon" icon="camera" />
                                            </div>
                                            <img v-if="previewLogo" :src="previewLogo" class="preview-logo">
                                            <div v-else class="upload-content">
                                                <font-awesome-icon class="upload-icon" icon="cloud-upload-alt" />
                                                <p class="upload-text"> Upload  </p>
                                            </div>
                                        </div>
                                        <avatar-cropper 
                                            :labels="{ submit: 'submit', cancel: 'Cancle'}"
                                            :cropper-options="cropperOptions" 
                                            :output-options="cropperOutputOptions"
                                            :output-quality="0.8" 
                                            :upload-handler="cropperHandler"
                                            trigger="#pick-avatar" 
                                            @changed="setFileObject"
                                            @error="handleUploadError" /> 
                                        <!-- <select name="category_id" id="category_id" class="form-control"
                                            v-model="form.category_id" v-for="category in categories"
                                            :key="category.id">
                                            <option :value="category.id">{{ category.name }}</option>
                                        </select> -->
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Product Name</label>
                                        <!-- <input type="text" class="form-control" name="name" v-model="form.name"
                                            :invalid="$v.form.name.$error" placeholder="Product Product Name" required> -->
                                        <base-input :invalid="$v.form.name.$error" v-model="form.name" type="text"
                                            name="name" focus tab-index="1" :placeholder="'Product Name'"
                                            @input="$v.form.name.$touch()" />
                                        <div v-if="$v.form.name.$error">
                                            <span v-if="!$v.form.name.required"
                                                class="text-danger">{{ required }}</span>
                                            <span v-if="!$v.form.name.minLength" class="text-danger">
                                                {{ $v.form.name.$params.minLength.min }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <base-text-area v-model="form.description" rows="4" name="description"
                                            placeholder="Product Description" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="price">Product Price</label>
                                        <input type="number" class="form-control" name="price" v-model="form.price"
                                            placeholder="Product price" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="sale_price">Selling Price</label>
                                        <input type="number" class="form-control" name="sale_price"
                                            v-model="form.sale_price" placeholder="Product selling price" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="unit">Unit</label>
                                        <input type="text" class="form-control" name="unit" v-model="form.unit"
                                            placeholder="Product Unit" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="quantity">Unit Value</label>
                                        <input type="number" class="form-control" name="quantity"
                                            v-model="form.quantity" placeholder="Product Unit value" required>
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control  custom-switch">
                                            <input class="custom-control-input" type="checkbox" id="in_stock"
                                                v-model="form.in_stock">
                                            <label for="in_stock" class="custom-control-label">In Stock</label>
                                        </div>
                                    </div>



                                    <!-- /.box-body -->
                                </div>
                                <div class="card-footer">
                                    <button type="button" @click="listProduct()" class="btn btn-default">Cancel</button>
                                    <!-- <button type="submit" class="btn btn-success float-right">Create</button> -->
                                    <base-button :loading="isLoading" :disabled="isLoading" icon="save" color="success"
                                        class="btn btn-success float-right" type="submit">
                                        Create
                                    </base-button>
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
    import AvatarCropper from "vue-avatar-cropper";

    export default {
        components: { AvatarCropper },
        data() {
            return {
                form: {},
                errors: null,
                categories: [],
                initLoading: false,
                isLoading: false,
                isEdit: true,
                category: {},
                previewLogo: null,
                    cropperOutputOptions: {
                    width: 150,
                    height: 150
                },
                cropperOptions: {
                    autoCropArea: 1,
                    viewMode: 0,
                    movable: true,
                    zoomable: true
                },
                isFetchingData: false,
                isLoading: false,
                isHidden: false,
                country: null,
                previewLogo: null,
                countries: [],
                passData: [],
                fileSendUrl: '/api/settings/company',
                fileObject: null
            }
        },
        mounted: function () {
            this.getcategories();
        },
        methods: {
            cropperHandler (cropper) {
                this.previewLogo = cropper.getCroppedCanvas().toDataURL(this.cropperOutputMime);
            },
            setFileObject (file) {
                this.fileObject = file;
            },
            handleUploadError (message, type, xhr) {
               // window.toastr['error']('Oops! Something went wrong...')
            },
            getcategories: function (searchText = "") {
                axios.get('/product/categories/fetch' , { params : { 'searchText' : searchText}})
                    .then(r => r.data)
                    .then((response) => {
                        this.categories = response.data;
                    });
            },
            searchCategories: function (search, loading) {
                loading(true);
                this.getcategories(loading, search, this);
            },
            onSearchCustomers: _.debounce((loading, search, vm) => {
                axios.post('/product/categories/fetch', {'searchText': search}).then(r => r.data).then((response) => {
                    vm.customers = response.data;
                    loading(false);
                });
            }, 350),
            selectedCategory: function () {

            },
            createProduct: function () {
                this.$v.form.$touch()
                if (this.$v.$invalid) {
                    return false;
                }
                axios.post('/product/create', this.form)
                    .then(r => r.data)
                    .then((response) => {
                        this.isLoading = true;
                        this.errors = null;
                        this.listProduct();
                    }).catch((error) => {
                        this.errors = typeof error.response.data.errors !== 'undefined' ? error.response.data
                            .errors : null;
                    });
                hidebtnLoader();
            },
            listProduct: function () {
                this.$router.push({
                    path: '/products'
                });
            }
        }
    }

</script>