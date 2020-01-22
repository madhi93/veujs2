<template>
    <div class="content-wrapper" >
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Order Deliveries</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active">Order Deliveries</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Order Delivery List</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Order No</th>
                                            <th>Customer</th>
                                            <th>Items</th>
                                            <th>Price</th>
                                            <th>Payment Status</th>
                                            <th>Status</th>
                                            <th>Hub</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(order , index) in orders" :key="order.id">
                                            <td>{{ (index + 1) + (10 * (currentPage - 1)) }}</td>
                                            <td>
                                                <router-link tag="a" 
                                                    :to="{ name:'viewOrder', params: { 'order_id': order.id }}">
                                                    {{ order.order_no }}
                                                </router-link>
                                            </td>
                                            <td>
                                                <span v-if="order.customer">{{ order.customer.fullname }}</span>
                                            </td>
                                            <td>{{ order.item_count }}</td>
                                            <td>{{ order.total_amount }}</td>
                                             <td>
                                                <span v-if="order.paid_status == 'pending'" class="badge badge-warning">
                                                    Pending
                                                </span>
                                                <span v-if="order.paid_status == 'refund'" class="badge badge-info">
                                                    Refund
                                                </span>
                                                <span v-if="order.paid_status == 'cod'" class="badge badge-success">
                                                    COD
                                                </span>
                                                <span v-if="order.paid_status == 'unpaid'" class="badge badge-danger">
                                                    Unpiad
                                                </span>
                                                <span v-if="order.paid_status == 'paid'" class="badge badge-success">
                                                    Paid
                                                </span>
                                            </td>
                                            <td>
                                                <span v-if="order.order_status == 'pending'" class="badge badge-warning">
                                                    Pending
                                                </span>
                                                <span v-if="order.order_status == 'processing'" class="badge badge-info">
                                                   Processing
                                                </span>
                                                <span v-if="order.order_status == 'shipped'" class="badge badge-success">
                                                    Shipped
                                                </span>
                                                <span v-if="order.order_status == 'delivered'" class="badge badge-info">
                                                    Delivered
                                                </span>
                                                <span v-if="order.order_status == 'canceled'" class="badge badge-danger">
                                                    Canceled
                                                </span>
                                                <span v-if="order.order_status == 'complete'" class="badge badge-success">
                                                    Complete
                                                </span>
                                            </td>
                                            <td> 
                                                <span v-if="order.hub">{{ order.hub.name }}</span>
                                            </td>                                            
                                            <td>{{ order.created_at }}</td>
                                            <td>
                                                <router-link tag="button" type="button"
                                                    class="btn btn-sm btn-icon btn-info"
                                                    :to="{ name:'editOrder', params: { 'order_id': order.id }}">
                                                    <span class="fa fa-edit"></span>
                                                </router-link>
                                                <button type="button" class="btn btn-sm btn-icon btn-danger">
                                                    <span class="fa fa-trash"></span>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-show="!orders.length">
                                            <td colspan="9" class="no-data-found-info">No admin orders found</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer clearfix" v-if="totalPages > 1">
                                <paginate :page-count="totalPages" :page-range="3" :margin-pages="2"
                                    :click-handler="searchOrders" :container-class="'pagination float-right'"
                                    :page-class="'page-item'">
                                </paginate>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
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
                orders: [],
                totalItems: 0,
                currentPage: null,
                filter: {},
                form: {},
                totalPages: 0,
                order: {}
            }
        },
        mounted: function () {
            this.getorders();
        },
        methods: {
            getorders: function () {
                axios.get('/order/list')
                    .then(r => r.data)
                    .then((response) => {
                        this.orders = response.data;
                        this.totalItems = response.totalItems;
                        this.currentPage = response.currentPage;
                        this.totalPages = Math.ceil(this.totalItems / 15);
                        this.filter = response.filter;
                    });
            },
            searchorders: function (page) {
                axios.post('/order/search/' + page, this.filter)
                    .then(r => r.data)
                    .then((response) => {
                        this.orders = response.data;
                        this.totalItems = response.totalItems;
                        this.currentPage = response.currentPage;
                        this.totalPages = Math.ceil(this.totalItems / 15);
                        //this.filter = response.filter;
                    });
            },
            createOrder: function () {
                this.$router.push({
                    path: '/order/create'
                });
            }
        }
    }

</script>
