<template>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Create Advertisement</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Dashbaord</a></li>
                            <li class="breadcrumb-item active">Advertisement List</li>
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
                                        <label for="title">Advertisement Title</label>
                                        <input type="text" class="form-control" name="title" v-model="form.title"
                                            placeholder="Advertisement title" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="type">Type</label>
                                        <select name="type" id="type" class="form-control" v-model="form.type">
                                            <option :value="'image'">Image</option>
                                            <option :value="'images'">Slider</option>
                                            <option :value="'video'">Video</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="short_description">Short Description</label>
                                        <textarea type="text" class="form-control" name="short_description"
                                            v-model="form.short_description"
                                            placeholder="Advertisement Short Description" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="start_at">Start At</label>
                                        <flat-pickr class="form-control" :config="config" name="start_at"
                                            v-model="form.start_at" placeholder="Start At"></flat-pickr>
                                    </div>
                                    <div class="form-group">
                                        <label for="end_at">End At</label>
                                        <flat-pickr class="form-control" :config="config" name="end_at"
                                            v-model="form.end_at" placeholder="End At"></flat-pickr>
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
                    <div class="col-md-4">
                        <div class="card card-default">
                            <div class="card-header">
                                <h3 class="card-title">Advertisement Days</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <!-- checkbox -->
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary1" >
                                                <label for="checkboxPrimary1">
                                                    Sunday
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <!-- checkbox -->
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary1" >
                                                <label for="checkboxPrimary1">
                                                    Monday
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <!-- checkbox -->
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary1" >
                                                <label for="checkboxPrimary1">
                                                    Tuesday
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <!-- checkbox -->
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary1" >
                                                <label for="checkboxPrimary1">
                                                    Sunday
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <!-- checkbox -->
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary1" >
                                                <label for="checkboxPrimary1">
                                                    Wednesday
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <!-- checkbox -->
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary1" >
                                                <label for="checkboxPrimary1">
                                                    Thursday
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <!-- checkbox -->
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary1" >
                                                <label for="checkboxPrimary1">
                                                    Friday
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <!-- checkbox -->
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary1" >
                                                <label for="checkboxPrimary1">
                                                    Saturday
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                <div class="row">
                    <div class="col-md-10 offset-md-1">
                        <hr>
                        <button type="button" @click="listAdvertisement()" class="btn btn-default">Cancel</button>
                        <button type="button" @click="createAdvertisement()"
                            class="btn btn-success float-right">Create</button>
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
        components: {
            AvatarCropper
        },
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
                },
                config: {
                    wrap: true,
                    altInput: true,
                    enableTime: true,
                    dateFormat: "Y-m-d H:i",
                },
            }
        },
        mounted: function () {
            this.getcategories();
        },
        methods: {
            cropperHandler(cropper) {
                this.previewLogo = cropper.getCroppedCanvas().toDataURL(this.cropperOutputMime);
            },
            setFileObject(file) {
                this.fileObject = file;
            },
            handleUploadError(message, type, xhr) {
                // window.toastr['error']('Oops! Something went wrong...')
            },
            getcategories: function (searchText = "") {
                axios.get('/advertisement/categories/fetch', {
                        params: {
                            'searchText': searchText
                        }
                    })
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
                axios.get('/advertisement/categories/fetch', {
                    'searchText': search
                }).then(r => r.data).then((response) => {
                    vm.customers = response.data;
                    loading(false);
                });
            }, 350),
            selectedCategory: function () {
                this.form.category_id = this.category.id;
            },
            createAdvertisement: function () {
                axios.post('/advertisement/create', this.form)
                    .then(r => r.data)
                    .then((response) => {
                        this.isLoading = true;
                        this.errors = null;
                        this.listAdvertisement();
                    }).catch((error) => {
                        this.errors = typeof error.response.data.errors !== 'undefined' ? error.response.data
                            .errors : null;
                    });
                hidebtnLoader();
            },
            listAdvertisement: function () {
                this.$router.push({
                    path: '/advertisements'
                });
            }
        }
    }

</script>
