<template>
    <div class="content">
        <div class="kt-subheader  kt-grid__item" id="kt_subheader">
            <div class="kt-container  kt-container--fluid ">
                <div class="kt-subheader__main">
                    <h3 class="kt-subheader__title">
                        Visas </h3>
                    <span class="kt-subheader__separator kt-hidden"></span>
                    <div class="kt-subheader__breadcrumbs">
                        <a href="javascript:;" class="kt-subheader__breadcrumbs-home"><i
                                class="flaticon2-shelter"></i></a>
                        <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="javascript:;" class="kt-subheader__breadcrumbs-link">
                            Visa </a>
                        <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="javascript:;"
                            class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
                            Create Visa</a>
                    </div>
                </div>
                <div class="kt-subheader__toolbar">
                    <div class="kt-subheader__wrapper">
                        <a href="javascript:;" class="btn kt-subheader__btn-primary">


                            <i class="flaticon2-plus"></i> &nbsp;Add Visa
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div v-if="errors !== null" class="alert alert-outline-danger" role="alert">
                        <div class="alert-icon"><i class="flaticon-warning"></i></div>
                        <div class="alert-text">
                            <h4 class="alert-heading">Got Issues!</h4>
                            <ul v-for="(values , key) in errors" :key="key">
                                <li v-for="(value , index) in values" :key="index">{{ value }}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="kt-portlet">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">
                                    Create Visa Form
                                </h3>
                            </div>
                        </div>
                        <!--begin::Form-->
                        <form class="kt-form" role="form" ref="addVisaForm" name="addVisaForm"
                            v-on:submit.prevent="createVisa">
                            <div class="kt-portlet__body">
                                <div class="kt-section kt-section--first">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" class="form-control" name="visa_name"
                                                    v-model="form.visa_name" placeholder="Visa Name" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="visa_from">Visa From</label>
                                                <select name="visa_from" id="visa_from" v-model="form.visa_from" class="form-control">
                                                    <option value="in">India</option>
                                                    <option value="us">Us</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="visa_to">Visa To</label>
                                                <select name="visa_to" id="visa_to" v-model="form.visa_to" class="form-control">
                                                    <option value="in">India</option>
                                                    <option value="us">Us</option>
                                                </select>
                                            </div>
                                        </div>
                                         <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="amount">Visa Amount</label>
                                                <input id="amount" type="number" class="form-control" name="amount"
                                                    v-model="form.amount" placeholder="visa amount" min="0" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-portlet__foot">
                                <div class="kt-form__actions">
                                    <button type="button" @click="listVisa()" class="btn btn-secondary">Cancel</button>
                                    <button type="submit" class="btn btn-success pull-right">Create</button>
                                    <!-- <button type="reset" class="btn btn-primary">Submit</button>
                                    <button type="reset" class="btn btn-secondary">Cancel</button> -->
                                </div>
                            </div>
                        </form>

                        <!--end::Form-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scope>
    .text-red {
        color: red !important;
        padding-left: 10px;
    }

</style>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                form: {},
                errors: null,
            }
        },
        mounted: function () {},
        methods: {

            createVisa: function () {
                axios.post('/admin/visa/create', this.form)
                    .then(r => r.data)
                    .then((response) => {
                        this.errors = null;
                        this.listVisa();
                    }).catch((error) => {
                        this.errors = typeof error.response.data.errors !== 'undefined' ? error.response.data
                            .errors : null;
                    });
                //hidebtnLoader();
            },
            listVisa: function () {
                this.$router.push({
                    path: '/admin/visas'
                });
            }
        }
    }

</script>
