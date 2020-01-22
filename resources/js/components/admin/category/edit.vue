<template>
    <div class="content-wrapper" >
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Category</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Dashbaord</a></li>
                            <li class="breadcrumb-item active">Category List</li>
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
                                <h3 class="card-title">Category Details</h3>
                            </div>
                            <!-- /.card-header -->
                            <form role="form" ref="editCategoryFrom" name="editCategoryFrom"
                                v-on:submit.prevent="editCategory">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Category Name</label>
                                        <input type="text" class="form-control" name="name" v-model="form.name"
                                            placeholder="Product Category Name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea type="text" class="form-control" name="description"
                                            v-model="form.description" placeholder="Category Description"
                                            required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control  custom-switch">
                                            <input class="custom-control-input" type="checkbox" id="is_gift"
                                                v-model="form.is_gift">
                                            <label for="is_gift" class="custom-control-label">IS Gift</label>
                                        </div>
                                    </div>

                                    <!-- /.box-body -->
                                </div>
                                <div class="card-footer">
                                    <button type="button" @click="listCategory()"
                                        class="btn btn-default">Cancel</button>
                                    <base-button :loading="isLoading" :disabled="isLoading" icon="save" color="success"
                                        class="btn btn-success float-right" type="submit">
                                        Save
                                    </base-button>
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
                                    v-on:vdropzone-complete="removeFile" :use-custom-slot="true"
                                    v-on:vdropzone-success="uploadSucess"
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
                                    upload-url="/product-category/feature-image/upload"
                                    @uploading="handleUploading"
                                    :upload-headers="dropzoneOptions.headers" @changed="setFileObject"
                                    @completed="handleCompleted"
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
                    "images": []
                },
                errors: null,
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
                    url: 'product-category/images/upload',
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
            this.getCategory();
            console.log($('meta[name="csrf-token"]').attr('content'));
        },
        methods: {
            handleUploading(form, xhr) {
                form.append('category_id', this.$route.params.category_id);
            },
            handleCompleted(response, form, xhr) {
                this.getCategory();
            },
            sendingEvent(file, xhr, formData) {
                formData.append('category_id', this.form.id);
            },
            removeFile: function (file, response) {
                this.$refs.myVueDropzone.removeAllFiles();
            },
            uploadSucess(file, response){
              this.getProduct();
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
                axios.delete('/product-category/remove-image/' + id)
                    .then(r => r.data)
                    .then((response) => {
                        this.getCategory();
                    });
            },
            getCategory: function () {
                axios.get('/product-category/fetch/' + this.$route.params.category_id)
                    .then(r => r.data)
                    .then((response) => {
                        this.form = response.data;
                        this.previewLogo = this.form.feature;
                    });
            },
            editCategory: function () {
                axios.post('/product-category/edit/' + this.$route.params.category_id, this.form)
                    .then(r => r.data)
                    .then((response) => {
                        this.errors = null;
                        this.listCategory();
                    }).catch((error) => {
                        this.errors = typeof error.response.data.errors !== 'undefined' ? error.response.data
                            .errors : null;
                    });
                //hidebtnLoader();
            },
            listCategory: function () {
                this.$router.push({
                    path: '/product-categories'
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
