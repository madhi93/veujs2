<template>
    <div class="content">
        <div class="kt-subheader  kt-grid__item" id="kt_subheader">
            <div class="kt-container  kt-container--fluid ">
                <div class="kt-subheader__main">
                    <h3 class="kt-subheader__title">
                        Plans </h3>
                    <span class="kt-subheader__separator kt-hidden"></span>
                    <div class="kt-subheader__breadcrumbs">
                        <a href="javascript:;" class="kt-subheader__breadcrumbs-home"><i
                                class="flaticon2-shelter"></i></a>
                        <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="javascript:;" class="kt-subheader__breadcrumbs-link">
                            Plan </a>
                        <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="javascript:;"
                            class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
                            Create Plan</a>
                    </div>
                </div>
                <div class="kt-subheader__toolbar">
                    <div class="kt-subheader__wrapper">
                        <a href="javascript:;" class="btn kt-subheader__btn-primary">


                            <i class="flaticon2-plus"></i> &nbsp;Add Plan
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
                                    Create Plan Form
                                </h3>
                            </div>
                        </div>
                        <!--begin::Form-->
                        <form class="kt-form" role="form" ref="addPlanForm" name="addPlanForm"
                            v-on:submit.prevent="createPlan">
                            <div class="kt-portlet__body">
                                <div class="kt-section kt-section--first">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" class="form-control" name="plan_name"
                                                    v-model="form.plan_name" placeholder="Plan Name" required>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <textarea type="text" class="form-control" name="description" col="4"
                                                    v-model="form.description" placeholder="Description" required></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="amount">Plan Amount</label>
                                                <input id="amount" type="number" class="form-control" name="amount"
                                                    v-model="form.amount" placeholder="plan amount" min="0" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="invoice_period">Invoice Period</label>
                                                <input id="invoice_period" type="number" class="form-control" name="invoice_period"
                                                    v-model="form.invoice_period" placeholder="Invoice Period" min="0" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="invoice_period">Invoice Period</label>
                                                <select name="invoice_interval" id="invoice_interval" v-model="form.invoice_interval" class="form-control">
                                                    <option value="none">None</option>
                                                    <option value="hour">Hours</option>
                                                    <option value="day">Days</option>
                                                    <option value="month">Months</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-portlet__foot">
                                <div class="kt-form__actions">
                                    <button type="button" @click="listPlan()" class="btn btn-secondary">Cancel</button>
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

            createPlan: function () {
                axios.post('/admin/plan/create', this.form)
                    .then(r => r.data)
                    .then((response) => {
                        this.errors = null;
                        this.listPlan();
                    }).catch((error) => {
                        this.errors = typeof error.response.data.errors !== 'undefined' ? error.response.data
                            .errors : null;
                    });
                //hidebtnLoader();
            },
            listPlan: function () {
                this.$router.push({
                    path: '/admin/plans'
                });
            }
        }
    }

</script>
