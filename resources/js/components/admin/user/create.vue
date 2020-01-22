<template>
    <div class="content-wrapper" >
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Create Admin</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Dashbaord</a></li>
                            <li class="breadcrumb-item active">Admin List</li>
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
                                <h3 class="card-title">Create Admin Form</h3>
                            </div>
                            <!-- /.card-header -->
                            <form role="form" ref="addUserFrom" name="addUserFrom" v-on:submit.prevent="createUser">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="first_name">First Name</label>
                                        <input type="text" class="form-control" name="first_name"
                                            v-model="form.first_name" placeholder="First Name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" class="form-control" name="last_name"
                                            v-model="form.last_name" placeholder="Last Name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" name="email" v-model="form.email"
                                            placeholder="Email address" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input id="password" type="password" name="password" class="form-control"
                                            v-model="form.password" placeholder="password">
                                    </div>
                                    <div class="form-group">
                                        <label for="tags">Roles</label>
                                        <vue-tags-input v-model="tag" :tags="tags" placeholder="Add Area Tag"
                                            :validation="validation"
                                            :autocomplete-filter-duplicates='true'
                                            :add-only-from-autocomplete='true'
                                            :avoid-adding-duplicates="true"
                                            :autocomplete-items="filteredItems" @tags-changed="newTags => tags = newTags" />
                                    </div>

                                    <!-- /.box-body -->
                                </div>
                                <div class="card-footer">
                                    <button type="button" @click="listUser()" class="btn btn-default">Cancel</button>
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
    import { VueTagsInput, createTags } from '@johmun/vue-tags-input';
    

    export default {
         components: {
            VueTagsInput,
        },
        data() {
            return {
                form: {
                    'profile_image': null
                },
                errors: null,
                profile_image_prev: null,
                tag: '',
                tags: [],
                roles: [],
            }
        },
        computed: {
            filteredItems() {
                return this.roles.filter(i => {
                    return i.text.toLowerCase().indexOf(this.tag.toLowerCase()) !== -1;
                });
            },
        },
        mounted: function () {
            this.getRoles();
        },
        methods: {
            onFileChange(e) {
                this.form.profile_image = URL.createObjectURL(this.$refs.profileImage.files[0]);
            },
            isObject: function (a) {
                return (!!a) && (a.constructor === Object);
            },
            getRoles: function(){
                axios.get('/fetch/roles')
                    .then(r => r.data)
                    .then((response) => {
                        this.roles = response.data;
                    })
            },
            createUser: function () {
                // const formData = new FormData(this.$refs.addUserFrom);

                // let config = {
                //     headers: {
                //         'Content-Type': 'multipart/form-data'
                //     }
                // };
                this.form.user_roles = this.tags.map(i => {
                    return i.text.toLowerCase();
                });
                axios.post('/admin/user/create', this.form)
                    .then(r => r.data)
                    .then((response) => {
                        this.errors = null;
                        this.listUser();
                    }).catch((error) => {
                        this.errors = typeof error.response.data.errors !== 'undefined' ? error.response.data
                            .errors : null;
                    });
                //hidebtnLoader();
            },
            listUser: function () {
                this.$router.push({
                    path: '/admin/users'
                });
            }
        }
    }

</script>
