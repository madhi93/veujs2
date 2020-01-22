<template>
    <div class="content-wrapper" >
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Delivery Slots</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active">Delivery Slots</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row" style="margin-bottom:20px;">
                    <div class="col-md-12">
                        <button type="button" @click="showModal()" class="btn btn-primary float-right">
                            <i class="fa fa-plus"></i>
                            Add Delivery Area
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Delivery Slot Table</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Area Name </th>
                                            <th>Pincode</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(area , index) in areas" :key="area.id">
                                            <td>{{ (index + 1) + (10 * (currentPage - 1)) }}</td>
                                            <td>{{ area.name }}</td>
                                            <td>{{ area.pin_code }}</td>
                                            <td>
                                                <button type="button" @click="fetchArea(area.id)"
                                                    class="btn btn-sm btn-icon btn-info">
                                                    <span class="fa fa-edit"></span>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-icon btn-danger">
                                                    <span class="fa fa-trash"></span>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-show="!areas.length">
                                            <td colspan="9" class="no-data-found-info">No areas found</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer clearfix" v-if="totalPages > 1">
                                <paginate :page-count="totalPages" :page-range="3" :margin-pages="2"
                                    :click-handler="searchSlots" :container-class="'pagination float-right'"
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

        <div class="modal fade" id="modal-create" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Create Delivery Area</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Area Name</label>
                            <input type="text" class="form-control" name="name" v-model="form.name"
                                placeholder="Delivery Area Name" required>
                        </div>
                        <div class="form-group">
                            <label for="pin_code">Pin Code</label>
                            <input type="text" class="form-control" name="pin_code" v-model="form.pin_code"
                                placeholder="Delivery Area pincode" required>
                        </div>
                        <div class="form-group">
                            <label for="geo_fence">Description</label>
                            <textarea type="text" class="form-control" name="geo_fence" v-model="form.geo_fence"
                                placeholder="Delivery Area GEO Fence" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="tags">Area Tags</label>
                            <vue-tags-input v-model="tag" :tags="tags" placeholder="Add Area Tag"
                                :validation="validation"
                                :autocomplete-filter-duplicates='true'
                                :avoid-adding-duplicates="true"
                                :autocomplete-items="filteredItems" @tags-changed="newTags => tags = newTags" />
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="createArea()">Save changes</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <div class="modal fade" id="modal-edit" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Delivery Area</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Area Name</label>
                            <input type="text" class="form-control" name="name" v-model="form.name"
                                placeholder="Delivery Area Name" required>
                        </div>
                        <div class="form-group">
                            <label for="pin_code">Pin Code</label>
                            <input type="text" class="form-control" name="pin_code" v-model="form.pin_code"
                                placeholder="Delivery Area pincode" required>
                        </div>
                        <div class="form-group">
                            <label for="geo_fence">Description</label>
                            <textarea type="text" class="form-control" name="geo_fence" v-model="form.geo_fence"
                                placeholder="Delivery Area GEO Fence" required></textarea>
                        </div>

                         <div class="form-group">
                            <label for="tags">Area Tags</label>
                            <vue-tags-input v-model="tag" :tags="tags" placeholder="Add Area Tag"
                                :validation="validation"
                                :autocomplete-filter-duplicates='true'
                                :avoid-adding-duplicates="true"
                                :autocomplete-items="filteredItems" @tags-changed="newTags => tags = newTags" />
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="editArea()">Save changes</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
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
                areas: [],
                totalItems: 0,
                currentPage: null,
                filter: {},
                form: {},
                totalPages: 0,
                area: {},
                tag: '',
                tags: [],
                autocompleteItems: [],
                validation: [{
                    classes: 'no-numbers',
                    rule: /^([^0-9]*)$/,
                }, {
                    classes: 'avoid-item',
                    rule: /^(?!Cannot).*$/,
                    disableAdd: true,
                }, {
                    classes: 'no-braces',
                    rule: ({
                        text
                    }) => text.indexOf('{') !== -1 || text.indexOf('}') !== -1,
                },{
                    classes: 'min-length',
                    rule: tag => tag.text.length < 8,
                }],
            }
        },
        computed: {
            filteredItems() {
                return this.autocompleteItems.filter(i => {
                    return i.text.toLowerCase().indexOf(this.tag.toLowerCase()) !== -1;
                });
            },
        },
        mounted: function () {
            this.getareas();
            this.fetchTags();
        },
        methods: {
            getareas: function () {
                axios.get('/delivery-area/list', {
                        params: {
                            'hub_id': this.$route.params.hub_id
                        }
                    })
                    .then(r => r.data)
                    .then((response) => {
                        this.areas = response.data;
                        this.totalItems = response.totalItems;
                        this.currentPage = response.currentPage;
                        this.totalPages = Math.ceil(this.totalItems / 15);
                        this.filter = response.filter;
                    });
            },
            searchareas: function (page) {
                this.filter.hub_id = this.$route.params.hub_id;
                axios.post('/delivery-area/search/' + page, this.filter)
                    .then(r => r.data)
                    .then((response) => {
                        this.areas = response.data;
                        this.totalItems = response.totalItems;
                        this.currentPage = response.currentPage;
                        this.totalPages = Math.ceil(this.totalItems / 15);
                        //this.filter = response.filter;
                    });
            },
            showModal() {
                $('#modal-create').modal('show');
            },
            createArea: function () {
                this.form.hub_id = this.$route.params.hub_id;
                this.form.area_tags = this.tags.map(i => {
                    return i.text.toLowerCase();
                });
                axios.post('/delivery-area/create', this.form)
                    .then(r => r.data)
                    .then((response) => {
                        this.errors = null;
                        this.getareas();
                        $('#modal-create').modal('hide');
                    }).catch((error) => {
                        this.errors = typeof error.response.data.errors !== 'undefined' ? error.response.data
                            .errors : null;
                    });
                //hidebtnLoader();
            },
            fetchArea: function (id) {
                axios.get('/delivery-area/fetch/' + id)
                    .then(r => r.data)
                    .then((response) => {
                        this.form = response.data;
                        this.tags = createTags(this.form.area_tags , this.validation);
                        $('#modal-edit').modal('show');
                    });
            },
            fetchTags: function (id) {
                axios.get('/delivery-area/fetch/' + id + '/tags')
                    .then(r => r.data)
                    .then((response) => {
                        this.autocompleteItems = response.data;
                    });
            },
            editArea: function () {
                this.form.area_tags = this.tags.map(i => {
                    return i.text.toLowerCase();
                });
                axios.post('/delivery-area/edit/' + this.form.id, this.form)
                    .then(r => r.data)
                    .then((response) => {
                        this.errors = null;
                        this.getareas();
                        $('#modal-edit').modal('hide');
                    }).catch((error) => {
                        this.errors = typeof error.response.data.errors !== 'undefined' ? error.response.data
                            .errors : null;
                    });
                //hidebtnLoader();
            },
        }
    }

</script>
