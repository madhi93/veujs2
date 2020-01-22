<template>
    <div class="content-wrapper" >
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Create Delivery Hub</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Dashbaord</a></li>
                            <li class="breadcrumb-item active">Delivery Hub List</li>
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
                                <h3 class="card-title">Create Hub Form</h3>
                            </div>
                            <!-- /.card-header -->
                            <form role="form" ref="addHubFrom" name="addHubFrom" v-on:submit.prevent="createHub">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="city_id">Select City</label>
                                        <select name="city_id" id="city_id" class="form-control" v-model="form.city_id">
                                            <option value="" disabled>Select Delivery Area City</option>
                                            <option v-for="city in cities" :key="city.id" :value="city.id">{{ city.name }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Hub Name</label>
                                        <input type="text" class="form-control" name="name"
                                            v-model="form.name" placeholder="Delivery Hub Name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea type="text" class="form-control" name="description"
                                            v-model="form.description" placeholder="Delivery Hub Description" required></textarea>
                                    </div>

                                    

                                    <!-- /.box-body -->
                                </div>
                                <div class="card-footer">
                                    <button type="button" @click="listHub()" class="btn btn-default">Cancel</button>
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
                cities: [],
            }
        },
        mounted: function () {
            this.getCities();
        },
        methods: {
            getCities: function(){
                axios.get('/fetch/cities')
                    .then(r => r.data)
                    .then((response) => {
                        this.cities = response.data;
                    });
            },
            createHub: function () {
                axios.post('/delivery-hub/create', this.form)
                    .then(r => r.data)
                    .then((response) => {
                        this.errors = null;
                        this.listHub();
                    }).catch((error) => {
                        this.errors = typeof error.response.data.errors !== 'undefined' ? error.response.data
                            .errors : null;
                    });
                //hidebtnLoader();
            },
            listHub: function () {
                this.$router.push({
                    path: '/delivery-hubs'
                });
            }
        }
    }

</script>
