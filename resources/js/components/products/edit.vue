<template>
    <div class="content-wrapper" >
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Product</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Dashbaord</a></li>
                            <li class="breadcrumb-item active">Product List</li>
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
                    <div class="col-md-6 offset-md-2">
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
                                <h3 class="card-title">Edit Product Form</h3>
                            </div>
                            <!-- /.card-header -->
                            <form role="form" ref="editProductFrom" name="editProductFrom"
                                v-on:submit.prevent="editProduct">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="category_id">Select Category</label>
                                        <select name="category_id" id="category_id" class="form-control"
                                            v-model="form.category_id">
                                            <option  v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Product Name</label>
                                        <input type="text" class="form-control" name="name" v-model="form.name"
                                            placeholder="Product Product Name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea type="text" class="form-control" name="description"
                                            v-model="form.description" placeholder="Product Description"
                                            required></textarea>
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
                                    <button type="submit" class="btn btn-success float-right">Update</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.card -->
                        <div class="card card-default">
                            <div class="card-header">
                                <h3 class="card-title">Category Images</h3>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 col-sm-6" v-for="image in form.images" :key="image.id">
                                        <div class="img-container">
                                            <img :src="image.url" alt="Avatar" class="image img-thumbnail">
                                            <div class="img-overlay">
                                                <a @click="removeImage(image.id)" class="icon" title="User Profile">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <vue-dropzone ref="myVueDropzone" id="dropzone" v-on:vdropzone-sending="sendingEvent"
                                    v-on:vdropzone-success="uploadSucess"
                                    v-on:vdropzone-complete="removeFile" :use-custom-slot="true"
                                    :options="dropzoneOptions">
                                </vue-dropzone>
                                <div class="alert alert-error" v-if="upload_errors.length">
                                    <div class="alert-heading"></div>
                                    <div class="content-alert__message">
                                        <ul class="custom-alert__list" v-for="(values , key) in upload_errors"
                                            :key="key">
                                            <li v-for="(value , index) in values" :key="index">{{ value }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-default">
                            <div class="card-header">
                                <h3 class="card-title">Category Feature Image</h3>
                            </div>
                            <!-- /.card-header -->

                            <div class="card-body">
                                <div id="pick-avatar" class="image-upload-box">
                                    <div class="overlay">
                                        <font-awesome-icon class="white-icon" icon="camera" />
                                    </div>
                                    <img v-if="previewLogo" :src="previewLogo" class="preview-logo">
                                    <div v-else class="upload-content">
                                        <font-awesome-icon class="upload-icon" icon="cloud-upload-alt" />
                                        <p class="upload-text"> Upload </p>
                                    </div>
                                </div>
                                <avatar-cropper :labels="{ submit: 'submit', cancel: 'Cancle'}"
                                    :cropper-options="cropperOptions" :output-options="cropperOutputOptions"
                                    :output-quality="0.8" :upload-handler="cropperHandler" trigger="#pick-avatar"
                                    upload-url="/product/feature-image/upload"
                                    @uploading="handleUploading"
                                    @completed="handleCompleted"
                                    :upload-headers="dropzoneOptions.headers" @changed="setFileObject"
                                    @error="handleUploadError" />

                                <div class="alert alert-error" v-if="feature_upload_errors">
                                    <div class="alert-heading"></div>
                                    <p class="mb-0">
                                        {{feature_upload_errors}}
                                    </p>
                                </div>
                            </div>

                        </div>
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
    import vue2Dropzone from 'vue2-dropzone';
    import 'vue2-dropzone/dist/vue2Dropzone.min.css';

    export default {
        components: {
            vueDropzone: vue2Dropzone,
            AvatarCropper
        },
        data() {
            return {
                form: {
                    images: []
                },
                errors: null,
                categories: [],
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
                previewLogo: null,
                fileSendUrl: '/api/settings/company',
                fileObject: null,
                dropzoneOptions: {
                    url: 'product/images/upload',
                    thumbnailWidth: 150,
                    maxFilesize: 0.5,
                    addRemoveLinks: true,
                    dictDefaultMessage: "<i class='fa fa-cloud-upload'></i>UPLOAD ME",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                },
                upload_errors: [],
                feature_upload_errors: null,
            }
        },
        mounted: function () {
            this.getProduct();
            this.getcategories();
        },
        methods: {
            handleUploading(form, xhr) {
                form.append('product_id', this.$route.params.product_id);
            },
            handleCompleted(response, form, xhr) {
                this.getProduct();
            },
            sendingEvent(file, xhr, formData) {
                formData.append('product_id', this.$route.params.product_id);
            },
            uploadSucess(file, response){
              this.getProduct();
            },
            removeFile: function (file, response) {
                this.$refs.myVueDropzone.removeAllFiles();
            },
            cropperHandler(cropper) {
                this.previewLogo = cropper.getCroppedCanvas().toDataURL(this.cropperOutputMime);
                this.feature_upload_errors = null;
            },
            setFileObject(file) {
                this.fileObject = file;
                this.feature_upload_errors = null;
            },
            handleUploadError(message, type, xhr) {
                this.feature_upload_errors = message;
            },
            removeImage(id) {
                axios.delete('/product/remove-image/' + id)
                    .then(r => r.data)
                    .then((response) => {
                        this.getProduct();
                    });
            },
            getcategories: function () {
                axios.get('/product/categories/fetch')
                    .then(r => r.data)
                    .then((response) => {
                        this.categories = response.data;
                    });
            },
            getProduct: function () {
                axios.get('/product/fetch/' + this.$route.params.product_id)
                    .then(r => r.data)
                    .then((response) => {
                        this.form = response.data;
                        this.previewLogo = this.form.feature;
                    });
            },
            editProduct: function () {
                axios.post('/product/edit/' + this.$route.params.product_id, this.form)
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

<style scoped>
    .img-container {
        position: relative;
        margin-bottom: 20px;
    }

    .image {
        display: block;
        width: 100%;
        /* //height: auto; */
    }

    .img-overlay {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        height: 100%;
        width: 100%;
        opacity: 0;
        transition: .3s ease;
    }

    .img-container:hover .img-overlay {
        opacity: 1;
    }

    .img-container:hover .image {
        opacity: 0.4;
    }

    .icon {
        color: black;
        font-size: 24px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        text-align: center;
    }

    .fa-trash:hover {
        color: #e40f0f;
    }

</style>
