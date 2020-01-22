<template>
    <div class="content-wrapper" >
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Create Delivery Slot</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Dashbaord</a></li>
                            <li class="breadcrumb-item active">Delivery Slot List</li>
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
                                <h3 class="card-title">Create Slot Form</h3>
                            </div>
                            <!-- /.card-header -->
                            <form role="form" ref="addSlotFrom" name="addSlotFrom" v-on:submit.prevent="createSlot">
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
                config: {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true 
                }
            }
        },
        mounted: function () {
        },
        methods: {
            createSlot: function () {
                axios.post('/delivery-slot/create', this.form)
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
            doSomethingOnClose: function (selectedDates, dateStr, instance) {
                var res = dateStr.split("to");
                this.filter.start_date = res[0];
                this.filter.end_date = res[1];
            },
            listSlot: function () {
                this.$router.push({
                    path: '/delivery-slots'
                });
            }
        }
    }

</script>
