<template>
    <div class="content-wrapper" >
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Advertisement</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Dashbaord</a></li>
                            <li class="breadcrumb-item active">Advertisement List</li>
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
                    <div class="col-md-6 offset-md-1">
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
                                <h3 class="card-title">Create Advertisement</h3>
                            </div>
                            <!-- /.card-header -->
                            <form role="form" ref="addAdvertisementFrom" name="addAdvertisementFrom"
                                v-on:submit.prevent="createAdvertisement">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Advertisement Name</label>
                                        <input type="text" class="form-control" name="name" v-model="form.name"
                                           placeholder="Advertisement Name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="advertisement_no">Advertisement Number</label>
                                        <input type="text" class="form-control" name="advertisement_no" v-model="form.advertisement_no"
                                           placeholder="Advertisement number" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="short_description">Short Description</label>
                                        <textarea type="text" class="form-control" name="short_description"
                                            v-model="form.short_description" placeholder="Advertisement Short Description"
                                            required></textarea>
                                    </div>



                                    <!-- /.box-body -->
                                </div>
                            </form>
                        </div>
                        <!-- /.card -->
                        <div class="card card-default">
                            <div class="card-header">
                                <h3 class="card-title">Descriptions</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <ckeditor :editor="editor" v-model="form.description" :config="editorConfig"></ckeditor>                                
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                    <div class="col-md-4">
                         <div class="card card-default">
                            <div class="card-header">
                                <h3 class="card-title">Advertisement Categories</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                
                                      <div class="form-group">
                                        <label for="category_id">Select Category</label>
                                        <v-select label="name" :filterable="false" :options="categories"
                                            @search="searchCategories" @change="selectedCategory" v-model="category"
                                            style="width:100%;">
                                            <template slot="no-options">
                                                type to search advertisement categories..
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
                                    </div>
                                <!-- /.box-body -->
                            </div>
                        </div>

                        <div class="card card-default">
                            <div class="card-header">
                                <h3 class="card-title">Advertisement Logo</h3>
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
                                    upload-url="/advertisement/feature-image/upload"
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
                <div class="row">
                    <div class="col-md-10 offset-md-1">
                        <hr>
                        <button type="button" @click="listAdvertisement()" class="btn btn-default">Cancel</button>
                        <button type="button" @click="editAdvertisement()" class="btn btn-success float-right">Update</button>
                    </div>
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
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

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
                    url: 'advertisement/images/upload',
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
                editor: ClassicEditor,
                editorData: '<p>Content of the editor.</p>',
                editorConfig: {
                    // The configuration of the editor.
                }
            }
        },
        mounted: function () {
            this.getAdvertisement();
            this.getcategories();
        },
        methods: {
            handleUploading(form, xhr) {
                form.append('advertisement_id', this.$route.params.advertisement_id);
            },
            handleCompleted(response, form, xhr) {
                this.getAdvertisement();
            },
            sendingEvent(file, xhr, formData) {
                formData.append('advertisement_id', this.$route.params.advertisement_id);
            },
            uploadSucess(file, response){
              this.getAdvertisement();
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
                axios.delete('/advertisement/remove-image/' + id)
                    .then(r => r.data)
                    .then((response) => {
                        this.getAdvertisement();
                    });
            },
            getcategories: function () {
                axios.get('/advertisement/categories/fetch')
                    .then(r => r.data)
                    .then((response) => {
                        this.categories = response.data;
                    });
            },
            getAdvertisement: function () {
                axios.get('/advertisement/fetch/' + this.$route.params.advertisement_id)
                    .then(r => r.data)
                    .then((response) => {
                        this.form = response.data;
                        this.category = this.form.category;
                        this.previewLogo = this.form.feature;
                    });
            },
            editAdvertisement: function () {
                axios.post('/advertisement/edit/' + this.$route.params.advertisement_id, this.form)
                    .then(r => r.data)
                    .then((response) => {
                        this.errors = null;
                        this.listAdvertisement();
                    }).catch((error) => {
                        this.errors = typeof error.response.data.errors !== 'undefined' ? error.response.data
                            .errors : null;
                    });
                //hidebtnLoader();
            },
            listAdvertisement: function () {
                this.$router.push({
                    path: '/advertisements'
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
