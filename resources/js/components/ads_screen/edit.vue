<template>
    <div class="content-wrapper" >
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Ad</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Dashbaord</a></li>
                            <li class="breadcrumb-item active">Ad List</li>
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
                                <h3 class="card-title">Update Ad</h3>
                            </div>
                            <!-- /.card-header -->
                            <form role="form" ref="addShopFrom" name="addShopFrom"
                                v-on:submit.prevent="createAd">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Ad Name</label>
                                        <input type="text" class="form-control" name="name" v-model="form.name"
                                           placeholder="Shop Name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="shop_no">Ad Number</label>
                                        <input type="text" class="form-control" name="ads_no" v-model="form.ads_no"
                                           placeholder="Shop number" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="short_description">Short Description</label>
                                        <textarea type="text" class="form-control" name="short_description"
                                            v-model="form.short_description" placeholder="Shop Short Description"
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
                    <!-- /.row -->
                </div>
                <div class="row">
                    <div class="col-md-10 offset-md-1">
                        <hr>
                        <button type="button" @click="listShop()" class="btn btn-default">Cancel</button>
                        <button type="button" @click="editAds()" class="btn btn-success float-right">Update</button>
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
                    url: 'shop/images/upload',
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
            this.getAds();
        },
        methods: {
            handleUploading(form, xhr) {
                form.append('ads_id', this.$route.params.ads_id);
            },
            handleCompleted(response, form, xhr) {
                this.getAds();
            },
            sendingEvent(file, xhr, formData) {
                formData.append('ads_id', this.$route.params.ads_id);
            },
            uploadSucess(file, response){
              this.getAds();
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
            getAds: function () {
                axios.get('/ads-screens/fetch/' + this.$route.params.ads_id)
                    .then(r => r.data)
                    .then((response) => {
                        this.form = response.data;
                        this.category = this.form.category;
                        this.previewLogo = this.form.feature;
                    });
            },
            editAds: function () {
                axios.post('/ads-screens/edit/' + this.$route.params.ads_id, this.form)
                    .then(r => r.data)
                    .then((response) => {
                    console.log(response);
                        this.errors = null;
                        this.listaAds();
                    }).catch((error) => {
                        this.errors = typeof error.response.data.errors !== 'undefined' ? error.response.data
                            .errors : null;
                    });
                //hidebtnLoader();
            },
            listaAds: function () {
                this.$router.push({
                    path: '/ads-screens'
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
