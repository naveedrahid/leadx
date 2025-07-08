<template>
    <Head title="Packages" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template v-slot:title>Packages</template>
                <li class="breadcrumb-item text-muted" aria-current="page">Packages</li>
            </Breadcrumb>
            <div class="mb-3">
                <div class="d-flex align-items-center justify-content-end gap-2">
                    <Link :href="route('app.admin.package.create')" class="btn btn-primary btn-sm"><i class="ti ti-plus"></i> Add New</Link>
                    <Link :href="route('app.admin.package.index')" class="btn btn-dark btn-sm"><i class="ti ti-refresh"></i> Reload</Link>
                    <button @click="updateGst" class="btn btn-primary btn-sm btn-update-gst">
                        <i class="ti ti-plus"></i> GST(10%)
                    </button>
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
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label for="duration_type" class="fs-1 mb-1 fw-bold">Duration Type</label>
                                            <select id="duration_type" v-model="duration_type" @change="getData()" class="form-select form-select-sm">
                                                <option value="">All Type</option>
                                                <option value="day">Day</option>
                                                <option value="week">Week</option>
                                                <option value="month">Month</option>
                                                <option value="year">Year</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label for="free_trial" class="fs-1 mb-1 fw-bold">Free Trial</label>
                                            <select id="free_trial" v-model="free_trial" @change="getData()" class="form-select form-select-sm">
                                                <option value="">All</option>
                                                <option value="1">Trial</option>
                                                <option value="0">No Trial</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label for="lead_limit" class="fs-1 mb-1 fw-bold">Leads Limit</label>
                                            <select id="lead_limit" v-model="lead_limit" @change="getData()" class="form-select form-select-sm">
                                                <option value="">All</option>
                                                <option value="1">Unlimited</option>
                                                <option value="0">Limited</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label for="website_limit" class="fs-1 mb-1 fw-bold">Website Limit</label>
                                            <select id="website_limit" v-model="website_limit" @change="getData()" class="form-select form-select-sm">
                                                <option value="">All</option>
                                                <option value="1">Unlimited</option>
                                                <option value="0">Limited</option>
                                            </select>
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
                    <div class="mb-5">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="d-flex align-items-center gap-1">
                                    <div class="input-group border rounded">
                                        <select v-model="bulkAction" id="bulk-action" class="form-select border-none">
                                            <option value="">Bulk Actions</option>
                                            <option value="delete">Delete</option>
                                        </select>
                                        <button class="btn btn-light text-dark input-group-text" id="bulk-action-apply" @click="bulkActionApply()">Apply</button>
                                    </div>
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
                                    <th>Title</th>
                                    <th>Price</th>
                                    <th>Duration</th>
                                    <th>Sort</th>
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
                                                <template v-if="!item.has_subscriptions">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" v-model="selectedItems" :value="item.id">
                                                    </div>
                                                </template>
                                                <template v-else>
                                                    <div class="form-check form-check-sm">
                                                        <input type="checkbox" class="form-check-input" disabled>
                                                    </div>
                                                </template>
                                            </td>
                                            <td class="align-middle">{{ getItemNum(index) }}</td>
                                            <td class="align-middle">{{ item.title }}</td>
                                            <td class="align-middle">{{ priceFormat(item.price) }}</td>
                                            <td class="align-middle">{{ item.duration_lifetime ? 'Lifetime' : item.format_duration }}</td>
                                            <td class="align-middle">{{ item.sort }}</td>
                                            <td class="align-middle">{{ dateFormat(item.created_at, 'DD.MM.YYYY') }}</td>
                                            <td class="align-middle">
                                                <template v-if="item.has_subscriptions">
                                                    <template v-if="item.status == 'active'">
                                                        <span class="badge bg-primary opacity-50">Active</span>
                                                    </template>
                                                    <template v-if="item.status == 'deactive'">
                                                        <span class="badge bg-danger opacity-50">Deactive</span>
                                                    </template>
                                                </template>
                                                <template v-else>
                                                    <template v-if="item.status == 'active'">
                                                        <button type="button" class="btn btn-primary btn-sm" @click.prevent="updateItemStatus($event, item, 'deactive')">Active</button>
                                                    </template>
                                                    <template v-if="item.status == 'deactive'">
                                                        <button type="button" class="btn btn-danger btn-sm" @click.prevent="updateItemStatus($event, item, 'active')">Deactive</button>
                                                    </template>
                                                </template>
                                            </td>
                                            <td class="align-middle">
                                                <div class="action-btn d-flex align-items-center gap-2">
                                                    <Link :href="route('app.admin.package.show', [item.id])" class="btn btn-info btn-icon btn-sm">
                                                        <i class="fs-4 ti ti-eye"></i> Show
                                                    </Link>
                                                    <Link :href="route('app.admin.package.edit', [item.id])" class="btn btn-primary btn-icon btn-sm">
                                                        <i class="fs-4 ti ti-edit"></i> Edit
                                                    </Link>
                                                    <template v-if="!item.has_subscriptions">
                                                        <button type="button" class="btn btn-danger btn-sm" @click="deleteItem($event, item)">
                                                            <i class="fs-4 ti ti-trash"></i> Delete
                                                        </button>
                                                    </template>
                                                    <template v-else>
                                                        <button type="button" class="btn btn-danger btn-sm" disabled>
                                                            <i class="fs-4 ti ti-trash"></i> Delete
                                                        </button>
                                                    </template>
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
                    <Pagination v-if="renderPaginate" :paginate="paginate" :url="route('app.admin.package.index')" :current="page" @items="getData"></Pagination>
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
            duration_type: '',
            free_trial: '',
            website_limit: '',
            lead_limit: '',
            status: '',
            search: '',
            dates: null,
            bulkAction : '',
            loader: false

        };
    },

    methods: {
        priceFormat(price, symbol = true) {
            price = parseInt(price).toFixed(2);
            return symbol ? this.$page.props.currency_symbol + price : price;
        },
        async updateGst() {
            let button = document.querySelector('.btn-update-gst');
            let oldHtml = button.innerHTML;

            button.disabled = true;
            button.innerHTML = '<i class="ti ti-loader rotate"></i> Updating...';

            try {
                const response = await axios.post(route('api.package.gstUpdate'), {}, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: "Bearer " + this.token,
                    },
                });
                this.getData();
            } catch (error) {
                console.error(error);
            } finally {
                button.disabled = false;
                button.innerHTML = oldHtml;
            }
        },

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

            if(this.duration_type !== '') {
                params.duration_type = this.duration_type;
            }

            if(this.free_trial !== '') {
                params.free_trial = this.free_trial;
            }

            if(this.website_limit !== '') {
                params.website_limit = this.website_limit;
            }

            if(this.lead_limit !== '') {
                params.lead_limit = this.lead_limit;
            }

            if(this.status !== '') {
                params.status = this.status;
            }

            if(this.search !== '') {
                params.search = this.search;
            }

            if(this.dates != null) {
                params.dates = this.dates;
            }

            await axios.get(route('api.package.get.all'), {
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
            await axios.post(route('api.package.update.status', [item.id]), {
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
            await axios.post(route('api.package.delete', [item.id]), {}, {
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
        },

        bulkActionApply() {
            let self = this;
            if(self.bulkAction == '') {
                toast.warning('Please choose a bulk action.');
                return false;
            }

            if(self.selectedItems.length == 0) {
                toast.warning('Please select an item for bulk action.');
                return false;
            }

            if(self.bulkAction  == 'delete') {
                Swal.fire({
                    html: "Please confirm if you want delete selected items.",
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
                        self.blukDelete();
                    }
                });
            }
        },

        async blukDelete() {
            this.loader = true;
            $('#bulk-action-apply').prop('disabled', true);
            await axios.post(route('api.package.bulk.delete'), {
                ids: this.selectedItems
            }, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                this.bulkAction = '';
                this.checkAll = false;
                this.selectedItems = [];
                toast.success($response.message);
                this.getData();
                this.$nextTick(() => {
                    this.renderPaginate = true;
                });
                this.loader = false;
                $('#bulk-action-apply').prop('disabled', false);
            }).catch((error) => {
                this.loader = false;
                $('#bulk-action-apply').prop('disabled', false);
                self.$nextTick(() => {
                    self.renderPaginate = true;
                });
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
