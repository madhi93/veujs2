<template>
    <div class="content-wrapper" >
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Create Ad</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Dashbaord</a></li>
                            <li class="breadcrumb-item active">Ad List</li>
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
                    <div class="col-md-6 offset-md-1">
                        <div v-if="errors !== null" class="alert content-alert content-alert--danger mt-4" role="alert">
                            <div class="content-alert__info">
                                <span class="content-alert__info-icon ua-icon-warning"></span>
                            </div>
                            <div class="content-alert__content">
                                <div class="content-alert__heading"></div>
                                <div class="content-alert__message">
                                    <ul class="custom-alert__list" v-for="(values , key) in errors" :key="key">
                                        <li v-for="(value , index) in values" :key="index" class="error">{{ value }}</li>
                                    </ul>

                                </div>
                            </div>
                        </div>
                        <!-- general form elements -->
                        <div class="card card-default">
                            <div class="card-header">
                                <h3 class="card-title">Create Ad</h3>
                            </div>
                            <!-- /.card-header -->
                            <form role="form" ref="addShopFrom" name="addShopFrom"
                                v-on:submit.prevent="createAd">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Ad Name</label>
                                        <input type="text" class="form-control" name="name" v-model="form.name"
                                           placeholder="Shop Name" required>
                                        <!--<span v-if = errors.></span>-->
                                    </div>
                                    <!--<div v-if="errors.name"> {{ errors.name  }} </div>-->
                                    <!--<div v-if="errors.name">{{ errors.name }}</div>-->
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
                                <h3 class="card-title">Description</h3>
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
                        <button type="button" @click="createAd()" class="btn btn-success float-right">Create</button>
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
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

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
                fileObject: null, 
                editor: ClassicEditor,
                editorData: '<p>Content of the editor.</p>',
                editorConfig: {
                    // The configuration of the editor.
                }
            }
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
            createAd: function () {
                axios.post('/ads-screens/create', this.form)
                    .then(r => r.data)
                    .then((response) => {
                    console.log(response);
                        this.isLoading = true;
                        this.errors = null;
                        this.listShop();
                    }).catch((error) => {
                        this.errors = typeof error.response.data.errors !== 'undefined' ? error.response.data
                            .errors : null;
                    });
            },
            listShop: function () {
                this.$router.push({
                    path: '/ads-screens'
                });
            }
        }
    }

</script>