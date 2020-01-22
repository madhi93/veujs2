<template>
    <div class="content-wrapper" >
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Delivery Slot</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Dashbaord</a></li>
                            <li class="breadcrumb-item active">Delivery Slot List</li>
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
                                <h3 class="card-title">Edit Slot Form</h3>
                            </div>
                            <!-- /.card-header -->
                            <form role="form" ref="editSlotFrom" name="editSlotFrom" v-on:submit.prevent="editSlot">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Slot Name</label>
                                        <input type="text" class="form-control" name="name"
                                            v-model="form.name" placeholder="Slot Slot Name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea type="text" class="form-control" name="description"
                                            v-model="form.description" placeholder="Slot Description" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="start_at">Start At</label>
                                        <flat-pickr v-model="form.start_at" :config="config" class="date-rage-picker form-control"
                                            placeholder="Start At" @on-close="doSomethingOnClose" name="start_at">
                                        </flat-pickr>
                                    </div>
                                    <div class="form-group">
                                        <label for="end_at">End At</label>
                                        <flat-pickr v-model="form.end_at" :config="config" class="date-rage-picker form-control"
                                            placeholder="End At" name="end_at">
                                        </flat-pickr>
                                    </div>
                                    

                                    <!-- /.box-body -->
                                </div>
                                <div class="card-footer">
                                    <button type="button" @click="listSlot()" class="btn btn-default">Cancel</button>
                                    <button type="submit" class="btn btn-success float-right">Update</button>
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
                config: {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true 
                }
            }
        },
        mounted: function () {
            this.getSlot();
        },
        methods: {
            getSlot: function () {
                axios.get('/delivery-slot/fetch/' + this.$route.params.slot_id)
                    .then(r => r.data)
                    .then((response) => {
                        this.form = response.data;
                    });
            },
            editSlot: function () {
                axios.post('/delivery-slot/edit/' + this.$route.params.slot_id,this.form)
                    .then(r => r.data)
                    .then((response) => {
                        this.errors = null;
                        this.listSlot();
                    }).catch((error) => {
                        this.errors = typeof error.response.data.errors !== 'undefined' ? error.response.data
                            .errors : null;
                    });
                //hidebtnLoader();
            },
            listSlot: function () {
                this.$router.push({
                    path: '/delivery-slots'
                });
            }
        }
    }

</script>
