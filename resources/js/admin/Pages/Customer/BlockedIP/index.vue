<template>
    <Head title="Leads" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template v-slot:title>IP Tracking</template>
                <li class="breadcrumb-item text-muted" aria-current="page">IP Tracking</li>
            </Breadcrumb>
            <div class="mb-3">
                <div class="d-flex align-items-center justify-content-end gap-2">
                    <Link :href="route('app.customer.blocked-ip.index')" class="btn btn-dark btn-sm"><i class="ti ti-refresh"></i> Reload</Link>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="mb-5">
                        <div class="row justify-content-between">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="per_page" class="fs-1 mb-1 fw-bold">Per Page</label>
                                            <input type="number" v-model="perpage" min="1" id="perpage" class="form-control form-control-sm" placeholder="10" @input="perPageSet()">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="website" class="fs-1 mb-1 fw-bold">Website</label>
                                            <select v-model="website" id="website" class="form-select form-select-sm" @change="handleWebsiteForms">
                                                <option value="">{{ loader ? 'Loading...' : 'All Websites' }}</option>
                                                <template v-if="!loader && websites.length>0">
                                                    <template v-for="item in websites">
                                                        <option :value="item.id">{{ item.name }}</option>
                                                    </template>
                                                </template>
                                            </select>
                                        </div>
                                    </div>

                                    <div :class="{'d-none': !showForm}" class="col-md-3">
                                        <div class="mb-3">
                                            <label for="form" class="fs-1 mb-1 fw-bold">Forms</label>
                                            <select v-model="form" id="form" class="form-select form-select-sm" @change="getData()">
                                                <option value="">{{ loader ? 'Loading...' : 'All Forms' }}</option>
                                                <template v-if="!loader && forms.length>0">
                                                    <template v-for="item in forms">
                                                        <option :value="item.id">{{ item.name }}</option>
                                                    </template>
                                                </template>
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
<!--                    <div class="mb-5">-->
<!--                        <div class="row">-->
<!--                            <div class="col-md-3">-->
<!--                                <div class="d-flex align-items-center gap-1">-->
<!--                                    <div class="input-group border rounded">-->
<!--                                        <se devlect v-model="bulkAction" id="bulk-action" class="form-select border-none">-->
<!--                                            <option value="">Bulk Actions</option>-->
<!--                                            <option value="delete">Delete</option>-->
<!--                                        </select>-->
<!--                                        <button class="btn btn-light text-dark input-group-text" id="bulk-action-apply" @click="bulkActionApply()">Apply</button>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
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
                                <th>Date & Time</th>
                                <th>Form Name</th>
                                <th>IP</th>
                                <th></th>
                            </tr>
                            </thead>
                            <TransitionGroup tag="tbody" name="fade" class="text-dark fs-2">
                                <template v-if="collection.length>0">
                                    <template v-for="(item, index) in collection" :key="item.id">
                                        <tr>
                                            <td class="align-middle">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" v-model="selectedItems" :value="item.id">
                                                </div>
                                            </td>
                                            <td class="align-middle">{{ getItemNum(index) }}</td>
                                            <td class="align-middle">{{ dateFormat(item.created_at, 'DD.MM.YYYY  - h:mm a') }}</td>
                                            <td class="align-middle">{{ item.wpform_name }}</td>
                                            <td class="align-middle">{{item.form_data?.visitor_info.ip}}</td>

                                            <td class="align-middle">
                                                <div class="action-btn d-flex align-items-center gap-2">
                                                    <button v-if="item.lead_blocked_ip?.is_blocked === 1" type="button" class="btn btn-success btn-sm" @click="UnBlockedIP($event, item.lead_blocked_ip?.id)">
                                                        <i class="ti ti-lock-open fs-3 pe-1"></i> Unblock
                                                    </button>
                                                    <button v-else type="button" class="btn btn-danger btn-sm" @click="BlockedIP($event, item)">
                                                        <i class="ti ti-lock fs-3 pe-1"></i> Block
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-show="itemId === item.id">
                                            <td colspan="9" class="p-4">
                                                <div :id="'lead-details-'+item.id">
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="card border mt-0 mb-3">
                                                                        <div class="card-body p-3">
                                                                            <h5 class="card-title mb-3 fs-3 fw-bolder">Lead Details</h5>
                                                                            <ul class="list-group list-group-flush">
                                                                                <li class="list-group-item d-flex gap-2 px-0">
                                                                                    <strong class="d-flex align-items-center gap-2"><i class="fs-3 ti ti-fingerprint"></i> Lead ID:</strong>
                                                                                    <span>#{{ item.id }}</span>
                                                                                </li>
                                                                                <li class="list-group-item d-flex gap-2 px-0">
                                                                                    <strong class="d-flex align-items-center gap-2"><i class="fs-3 ti ti-building-pavilion"></i> Form Name:</strong>
                                                                                    <span>{{ item.wpform_name }}</span>
                                                                                </li>
                                                                                <li class="list-group-item d-flex gap-2 px-0">
                                                                                    <strong class="d-flex align-items-center gap-2"><i class="fs-3 ti ti-chart-bubble"></i> Status:</strong>
                                                                                    <span>{{ statuses.hasOwnProperty(item.status) ? statuses[item.status] : item.status }}</span>
                                                                                </li>
                                                                                <li class="list-group-item d-flex gap-2 px-0">
                                                                                    <strong class="d-flex align-items-center gap-2"><i class="fs-3 ti ti-eye"></i> Is View:</strong>
                                                                                    <span>{{ item.is_viewed ? 'Yes' : 'No' }}</span>
                                                                                </li>
                                                                                <li class="list-group-item d-flex gap-2 px-0">
                                                                                    <strong class="d-flex align-items-center gap-2"><i class="fs-3 ti ti-calendar-time"></i> Submitted on:</strong>
                                                                                    <span>{{ dateFormat(item.created_at, 'DD.MM.YYYY') }}</span>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="card border mt-0 mb-3">
                                                                        <div class="card-body p-3">
                                                                            <h5 class="card-title mb-3 fs-3 fw-bolder">User Information</h5>
                                                                            <ul class="list-group list-group-flush">
                                                                                <li class="list-group-item d-flex gap-2 px-0">
                                                                                    <strong class="d-flex align-items-center gap-2"><i class="fs-3 ti ti-anchor"></i> IP Address:</strong>
                                                                                    <span>{{ item.form_data?.visitor_info.ip }}</span>
                                                                                </li>
                                                                                <li class="list-group-item d-flex gap-2 px-0">
                                                                                    <strong class="d-flex align-items-center gap-2"><i class="fs-3 ti ti-device-desktop-analytics"></i> Platform:</strong>
                                                                                    <span>{{ item.form_data?.visitor_info.platform }}</span>
                                                                                </li>
                                                                                <li class="list-group-item d-flex gap-2 px-0">
                                                                                    <strong class="d-flex align-items-center gap-2"><i class="fs-3 ti ti-app-window"></i> Browser/OS:</strong>
                                                                                    <span>{{ item.form_data?.visitor_info.browser }}</span>
                                                                                </li>
                                                                                <li class="list-group-item d-flex gap-2 px-0">
                                                                                    <strong class="d-flex align-items-center gap-2"><i class="fs-3 ti ti-link"></i> Referrer URL:</strong>
                                                                                    <span><a :href="item.form_data?.visitor_info.ref_url" target="_blank">{{ item.form_data?.visitor_info.ref_url }}</a></span>
                                                                                </li>
                                                                                <li class="list-group-item d-flex gap-2 px-0">
                                                                                    <strong class="d-flex align-items-center gap-2"><i class="fs-3 ti ti-map-pin"></i> Continent:</strong>
                                                                                    <span>{{ (item.form_data?.visitor_info.continent !== '' && item.form_data?.visitor_info.continent !== 'unknown') ? item.form_data?.visitor_info.continent : "Not Available" }}</span>
                                                                                </li>
                                                                                <li class="list-group-item d-flex gap-2 px-0">
                                                                                    <strong class="d-flex align-items-center gap-2"><i class="fs-3 ti ti-map-pin"></i> Country:</strong>
                                                                                    <span>{{ (item.form_data?.visitor_info.country !== '' && item.form_data?.visitor_info.country !== 'unknown') ? item.form_data?.visitor_info.country : "Not Available" }}</span>
                                                                                </li>
                                                                                <li class="list-group-item d-flex gap-2 px-0">
                                                                                    <strong class="d-flex align-items-center gap-2"><i class="fs-3 ti ti-map-pin"></i> Country Code:</strong>
                                                                                    <span>{{ (item.form_data?.visitor_info.country_code !== '' && item.form_data?.visitor_info.country_code !== 'unknown') ? item.form_data?.visitor_info.country_code : "Not Available" }}</span>
                                                                                </li>
                                                                                <li class="list-group-item d-flex gap-2 px-0">
                                                                                    <strong class="d-flex align-items-center gap-2"><i class="fs-3 ti ti-map-pin"></i> State:</strong>
                                                                                    <span>{{ (item.form_data?.visitor_info.state !== '' && item.form_data?.visitor_info.state !== 'unknown') ? item.form_data?.visitor_info.state : "Not Available" }}</span>
                                                                                </li>
                                                                                <li class="list-group-item d-flex gap-2 px-0">
                                                                                    <strong class="d-flex align-items-center gap-2"><i class="fs-3 ti ti-map-pin"></i> City:</strong>
                                                                                    <span>{{ (item.form_data?.visitor_info.city !== '' && item.form_data?.visitor_info.city !== 'unknown') ? item.form_data?.visitor_info.city : "Not Available" }}</span>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-7">
                                                            <div class="card border mt-0 mb-3">
                                                                <div class="card-body p-3">
                                                                    <h5 class="card-title mb-3 fs-3 fw-bolder">Form Lead Details</h5>
                                                                    <ul class="list-group list-group-flush" v-if="item.form_data?.data">
                                                                        <template v-for="(item, field) in item.form_data?.data">
                                                                            <template v-if="field == 'checkbox-list'">
                                                                                <li class="list-group-item d-flex gap-2 px-0" v-for="(value, key) in item">
                                                                                    <strong class="text-capitalize">{{ formatText(key) }}:</strong>
                                                                                    <span>
                                                                                        <span v-for="(value1, key1) in value">{{ value1 }}{{ (key1 < Object.keys(value).length) ? ', ' : '' }}</span>
                                                                                    </span>
                                                                                </li>
                                                                            </template>
                                                                            <template v-else-if="field == 'file'">
                                                                                <li class="list-group-item d-flex gap-2 px-0" v-for="(value, key) in item">
                                                                                    <strong class="text-capitalize">{{ formatText(key) }}:</strong>
                                                                                    <span><a :href="value.url" target="_blank" class="text-success">{{ value.name }}</a></span>
                                                                                </li>
                                                                            </template>
                                                                            <template v-else>
                                                                                <li class="list-group-item d-flex gap-2 px-0" v-for="(value, key) in item">
                                                                                    <strong class="text-capitalize">{{ formatText(key) }}:</strong>
                                                                                    <span>{{ value }}</span>
                                                                                </li>
                                                                            </template>
                                                                        </template>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
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
                    <Pagination v-if="renderPaginate" :paginate="paginate" :url="route('app.customer.leads.index')" :current="page" @items="getData"></Pagination>
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
            statuses: {
                "new": "New",
                "pending": "Pending",
                "assigned": "Assigned",
                "in-progress": "In Progress",
                "on-hold": "On Hold",
                "follow-up": "Follow Up",
                "duplicate": "Duplicate",
                "contacted": "Contacted",
                "qualified": "Qualified",
                "unqualified": "Unqualified",
                "lost": "Lost",
                "closed": "Closed"
            },
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
            forms: [],
            websites: [],
            itemId: false,
            loadFormSelectBox: false,
            form: '',
            website: '',
            status: '',
            view: '',
            search: '',
            showForm: false,
            dates: null,
            bulkAction : '',
            loader: false
        };
    },

    methods: {
        formatText(str) {
            return str.replace(/-/g, ' ').split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');
        },

        async handleWebsiteForms() {
            this.loader = true;
            this.showForm = this.website !== '';
            this.getData();

            await axios.get(route('api.customer_leads.website.forms'), {
                params: { website_id: this.website },
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                if ($response.data.length > 0) {
                    this.forms = $response.data;
                    if (!this.forms.some(item => item.id === this.form)) {
                        this.form = '';
                    }
                }

                this.loader = false;
            }).catch((error) => {
                this.loader = false
                toast.error(error.response.data.message);
            });
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

        itemToggle(item) {
            if(!this.itemId) {
                this.itemId = item.id;
                this.leadViewed(item);
            } else if(this.itemId && this.itemId !== item.id) {
                this.itemId = item.id;
                this.leadViewed(item);
            } else {
                this.itemId = false;
            }
        },




        async getForms() {
            this.loader = true;
            await axios.get(route('api.customer_leads.get.forms'), {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                if($response.data.length > 0) {
                    this.forms = $response.data;
                }

                this.loader = false;
            }).catch((error) => {
                this.loader = false
                toast.error(error.response.data.message);
            });
        },

        async getWebsites() {
            this.loader = true;
            await axios.get(route('api.customer_leads.get.websites'), {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                if($response.data.length > 0) {
                    this.websites = $response.data;
                    console.log($response.data);
                }

                this.loader = false;
            }).catch((error) => {
                this.loader = false
                toast.error(error.response.data.message);
            });
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

            if(this.form !== '') {
                params.wpform_id = this.form;
            }

            if(this.website !== '') {
                params.website_id = this.website;
            }

            if(this.status !== '') {
                params.status = this.status;
            }

            if(this.view !== '') {
                params.is_viewed = this.view;
            }

            if(this.dates != null) {
                params.dates = this.dates;
            }

            await axios.get(route('api.blocked_ip.get.all'), {
                params: params,
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;

                if($response.data.length > 0) {
                    this.collection = $response.data.map((item) => {
                        item.form_data = JSON.parse(item.form_data);
                        return item;
                    });
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



        UnBlockedIP(event, item) {
            let ele = $(event.target);
            if(ele.prop("tagName").toLowerCase() != 'button') {
                ele = ele.closest('button');
            }
            this.unblocked_ip(ele, item);
        },

        async unblocked_ip(ele, item) {
            ele.prop('disabled', true);
            ele.find('i').removeClass("ti-trash").addClass('ti-loader rotate');
            this.loader = true;
            await axios.post(route('api.blocked_ip.unblocked.ip', [item]), {}, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                toast.success($response.message);
                this.getData();
                ele.prop('disabled', false);
                ele.find('i').addClass("ti-trash").removeClass('ti-loader rotate');
                this.loader = false;
            }).catch((error) => {
                ele.prop('disabled', false);
                ele.find('i').addClass("ti-trash").removeClass('ti-loader rotate');
                this.loader = false;
                toast.error(error.response.data.message);
            });
        },


        BlockedIP(event, item) {
            let ele = $(event.target);
            if(ele.prop("tagName").toLowerCase() != 'button') {
                ele = ele.closest('button');
            }
            this.blocked_ip(ele, item);


        },

        async blocked_ip(ele, item) {
            ele.prop('disabled', true);
            ele.find('i').removeClass("ti-trash").addClass('ti-loader rotate');
            this.loader = true;
            await axios.post(route('api.blocked_ip.blocked.ip', [item.id]), {}, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                toast.success($response.message);
                this.getData();

                ele.prop('disabled', false);
                ele.find('i').addClass("ti-trash").removeClass('ti-loader rotate');
                this.loader = false;
            }).catch((error) => {
                ele.prop('disabled', false);
                ele.find('i').addClass("ti-trash").removeClass('ti-loader rotate');
                this.loader = false;
                toast.error(error.response.data.message);
            });
        },


        async blukDelete() {
            this.loader = true;
            $('#bulk-action-apply').prop('disabled', true);
            await axios.post(route('api.customer_leads.bulk.delete'), {
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
                this.$nextTick(() => {
                    this.renderPaginate = true;
                });
                toast.error(error.response.data.message);
            });
        },

    },

    created() {
        this.getForms();
        this.getWebsites();
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
