<template>
    <Head title="Plugins" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template v-slot:title>Plugins</template>
                <li class="breadcrumb-item text-muted" aria-current="page">Plugins</li>
            </Breadcrumb>
            <div class="mb-3">
                <div class="d-flex align-items-center justify-content-end gap-2">
                    <Link :href="route('app.admin.plugin.create')" class="btn btn-primary btn-sm"><i class="ti ti-plus"></i> Add New</Link>
                    <Link :href="route('app.admin.plugin.index')" class="btn btn-dark btn-sm"><i class="ti ti-refresh"></i> Reload</Link>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="mb-5">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="per_page" class="fs-1 mb-1 fw-bold">Per Page</label>
                                            <input type="number" v-model="perpage" min="1" id="perpage" class="form-control form-control-sm" placeholder="10" @input="perPageSet()">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="status" class="fs-1 mb-1 fw-bold">Status</label>
                                            <select v-model="status" id="status" class="form-select form-select-sm" @change="getData()">
                                                <option value="">All</option>
                                                <option value="active">Active</option>
                                                <option value="deactive">Deactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="search" class="fs-1 mb-1">Search</label>
                                            <input type="search" v-model="search" id="search" class="form-control form-control-sm" placeholder="Search" @input="getData()">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3 form-input-sm">
                                    <label for="dates" class="fs-1 mb-1">Search By Dates</label>
                                    <VueDatePicker v-model="dates" @update:model-value="datesChange" range multi-calendars :enable-time-picker="false" placeholder="Select Date"></VueDatePicker>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="renderPaginate" class="text-end fs-1 fw-bold text-capitalize py-2">Showing {{ paginate.from ? paginate.from : 0 }} to {{ paginate.to ? paginate.to : 0 }} of {{ paginate.total ? paginate.total : 0 }} records</div>
                    <div class="table-responsive rounded-2 mb-4">
                        <table class="table border text-nowrap customize-table mb-0 align-middle">
                            <thead class="text-dark fs-3">
                                <tr>
                                    <th width="50px">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" v-model="checkAll" @click="selectAll()">
                                        </div>
                                    </th>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Version</th>
                                    <th>Documentation</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <TransitionGroup tag="tbody" name="fade" class="text-dark fs-3">
                                <template v-if="collection.length>0">
                                    <template v-for="(item, index) in collection" :key="item.id">
                                        <tr>
                                            <td class="align-middle">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" v-model="selectedItems" :value="item.id">
                                                </div>
                                            </td>
                                            <td class="align-middle">{{ getItemNum(index) }}</td>
                                            <td class="align-middle">
                                                <template v-if="item.documentation != null">
                                                    <a :href="item.plugin_url" target="_blank">{{ item.plugin_name }}</a>
                                                </template>
                                                <template v-else>
                                                    {{ item.plugin_name }}
                                                </template>
                                            </td>
                                            <td class="align-middle">{{ item.version }}</td>
                                            <td class="align-middle">
                                                <template v-if="item.documentation != null">
                                                    <a :href="plugin.documentation" target="_blank"><i class="ti ti-notebook"></i> Documentation</a>
                                                </template>
                                                <template v-else>
                                                    None
                                                </template>
                                            </td>
                                            <td class="align-middle">{{ dateFormat(item.created_at, 'DD.MM.YYYY') }}</td>
                                            <td class="align-middle">
                                                <template v-if="item.status == 'active'">
                                                    <button type="button" class="btn btn-primary btn-sm" @click.prevent="updateItemStatus($event, item, 'deactive')">Active</button>
                                                </template>
                                                <template v-if="item.status == 'deactive'">
                                                    <button type="button" class="btn btn-danger btn-sm" @click.prevent="updateItemStatus($event, item, 'active')">Deactive</button>
                                                </template>
                                            </td>
                                            <td class="align-middle">
                                                <div class="action-btn d-flex align-items-center gap-2">
                                                    <a :href="item.plugin_file_url" class="btn btn-dark btn-icon btn-sm"><i class="ti ti-download"></i> Download</a>
                                                    <Link :href="route('app.admin.plugin.edit', [item.id])" class="btn btn-primary btn-icon btn-sm">
                                                        <i class="fs-4 ti ti-edit"></i> Edit
                                                    </Link>
                                                    <button type="button" class="btn btn-danger btn-sm" @click="deleteItem($event, item)">
                                                        <i class="fs-4 ti ti-trash"></i> Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td colspan="10">
                                            <div class="py-5 d-flex justify-content-center aling-items-center">
                                                <strong class="fs-4">No Record Found!</strong>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </TransitionGroup>
                        </table>
                    </div>
                    <Pagination v-if="renderPaginate" :paginate="paginate" :url="route('app.admin.plugin.index')" :current="page" @items="getData"></Pagination>
                    <div v-if="renderPaginate" class="text-end fs-1 fw-bold text-capitalize py-2">Showing {{ paginate.from ? paginate.from : 0 }} to {{ paginate.to ? paginate.to : 0 }} of {{ paginate.total ? paginate.total : 0 }} records</div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/admin/Layouts/AppLayout.vue';
import Breadcrumb from '@/admin/Components/Breadcrumb.vue';
import Pagination from '@/admin/Components/Pagination.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import Swal from 'sweetalert2/dist/sweetalert2.js';
import 'sweetalert2/dist/sweetalert2.css';
import moment from 'moment';

export default {
    components: {
        Head,
        Link,
        AppLayout,
        Breadcrumb,
        Pagination,
        VueDatePicker
    },

    data() {
        return {
            user: this.$cookies.get('lxf-user'),
            token: this.$cookies.get('lxf-token'),
            collection: [],
            paginate: {},
            perpage: 10,
            page: 1,
            orderBy: 'id',
            order: 'DESC',
            checkAll: false,
            selectedItems: [],
            renderPaginate: true,
            status: '',
            search: '',
            dates: null,
            loader: false
        };
    },

    methods: {
        dateFormat(date, format, cformat = null) {
            if(cformat) {
                return moment(date, cformat).format(format);
            }

            return moment(date).format(format);
        },
        
        getItemNum(index) {
            index = index+1;
            if(this.page > 1) {
                let num = (this.page-1) * this.perpage;
                index = index+num;
            }

            return (index < 10) ? '0'+index : index;
        },

        perPageSet() {
            if(this.perpage>100) {
                this.perpage = 100;
            }

            if(this.perpage == '' || this.perpage == 0) {
                this.perpage = 1;
            }
            
            this.getData();
        },

        selectAll() {
            this.checkAll = this.checkAll ? false : true; 
            let itemsIds = [];
            if(this.collection.length) {
                this.collection.forEach((item) => {
                    if(!item.other?.is_subscription_active) {
                        itemsIds.push(item.id);
                    }
                });
            }

            if(this.checkAll) {
                this.selectedItems = itemsIds;
            } else {
                this.selectedItems = [];
            }
        },

        datesChange() {
            if(this.dates != null) {
                let dateStart = this.dates[0];
                let dateEnd = this.dates[1];
                if(this.dates[0] != null) {
                    dateStart = moment(this.dates[0]).format('YYYY-MM-DD');
                } else {
                    dateStart = moment().format('YYYY-MM-DD');
                }
                
                if(this.dates[1] != null) {
                    dateEnd = moment(this.dates[1]).format('YYYY-MM-DD');
                } else {
                    dateEnd = moment().format('YYYY-MM-DD');
                }
    
                this.dates = [dateStart, dateEnd];
            }
            
            this.getData();
        },

        async getData(page = this.page) {
            this.page = page;
            this.collection = [];
            this.renderPaginate = false;
            this.loader = true;
            let params = {
                orderby: this.orderBy,
                order: this.order,
                perpage: this.perpage,
                page: this.page
            };

            if(this.status !== '') {
                params.status = this.status;
            }

            if(this.search !== '') {
                params.search = this.search;
            }

            if(this.dates != null) {
                params.dates = this.dates;
            }

            await axios.get(route('api.plugin.get.all'), {
                params: params,
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                if($response.data.length > 0) {
                    this.collection = $response.data;
                }

                this.paginate = $response.paginate;
                this.page = this.paginate.current_page;
                this.$nextTick(() => {
                    this.renderPaginate = true;
                });
                this.loader = false;
            }).catch((error) => {
                this.$nextTick(() => {
                    this.renderPaginate = true;
                });
                this.loader = false;
                toast.error(error.response.data.message);
            });
        },

        async updateItemStatus(event, item, status) {
            let ele = $(event.target);
            if(ele.prop("tagName").toLowerCase() != 'button') {
                ele = ele.closest('button');
            }

            ele.prop('disabled', true);
            ele.html('<i class="ti ti-loader rotate"></i> Loading...');
            await axios.post(route('api.plugin.update.status', [item.id]), {
                status: status 
            }, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                toast.success($response.message);
                this.collection.map((value, index) => {
                    if(value.id == item.id) {
                        this.collection[index].status = status;
                    }
                });
                
                ele.prop('disabled', false);
                ele.html((status == 'active' ? 'Active' : 'Deactive'));
            }).catch((error) => {
                ele.prop('disabled', false);
                ele.html((status == 'active' ? 'Active' : 'Deactive'));
                toast.error(error.response.data.message);
            });
        },

        deleteItem(event, item) {
            let self = this;
            let ele = $(event.target);
            if(ele.prop("tagName").toLowerCase() != 'button') {
                ele = ele.closest('button');
            }

            Swal.fire({
                html: "Please confirm if you want delete this.",
                icon: 'warning',
                buttonsStyling: false,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Not Now',
                customClass: {
                    htmlContainer: 'fs-4',
                    actions: 'm-0 mt-5 gap-3',
                    confirmButton: "btn btn-danger m-0",
                    cancelButton: "btn m-0"
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    self.delete(ele, item);
                }
            });
        },

        async delete(ele, item) {
            ele.prop('disabled', true);
            ele.find('i').removeClass("ti-trash").addClass('ti-loader rotate');
            await axios.post(route('api.plugin.delete', [item.id]), {}, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                toast.success($response.message);
                this.collection.forEach((value, index) => {
                    if (value.id === item.id) {
                        this.collection.splice(index, 1);
                    }
                });
                
                if(!this.collection.length) {
                    this.getData();
                }

                ele.prop('disabled', false);
                ele.find('i').addClass("ti-trash").removeClass('ti-loader rotate');
            }).catch((error) => {
                ele.prop('disabled', false);
                ele.find('i').addClass("ti-trash").removeClass('ti-loader rotate');
                toast.error(error.response.data.message);
            });
        }
    },

    created() {
        this.getData();
    },

    mounted() {
        this.$refs.app_layout.loadScript();
    }
}
</script>

<style>
.fade-enter-active, .fade-leave-active {
    transition: opacity .5s;
}
.fade-enter, .fade-leave-to {
    opacity: 0;
}
</style>