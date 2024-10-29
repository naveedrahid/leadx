<template>
    <Head title="Subscription" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template v-slot:title>Subscription</template>
                <li class="breadcrumb-item text-muted" aria-current="page">Subscription</li>
            </Breadcrumb>
            <div class="mb-4">
                <div class="d-flex align-items-center justify-content-end gap-2">
                    <Link :href="route('app.customer.subscription.billing.history')" class="btn btn-primary btn-sm">Billing History</Link>
                    <Link :href="route('app.customer.subscription.license')" class="btn btn-primary btn-sm">Plugin License</Link>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-white border-bottom">
                    <h4 class="card-title">Current Subscription</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive rounded-2 mb-4">
                        <table class="table border text-nowrap customize-table mb-0 align-middle">
                            <thead class="text-dark fs-3">
                                <tr>
                                    <th>Subscription</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Next Billing Date</th>
                                    <th>Started</th>
                                    <th>Ended</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <TransitionGroup tag="tbody" name="fade">
                                <template v-if="Object.keys(current_subscription).length>0">
                                    <tr>
                                        <td class="align-middle">
                                            {{ current_subscription.name }} 
                                            <template v-if="current_subscription.status == 'paused'">
                                                <span class="d-block fs-3 fw-bold mt-2">Paused at: {{ current_subscription.paused_at ? dateFormat(current_subscription.paused_at, 'DD.MM.YYYY') : '-' }}</span>
                                            </template>
                                            <div class="mt-2 fs-1 text-dark fw-bold" v-if="current_subscription.coupon != null && current_subscription.coupon_expire_at != null">
                                                <template v-if="checkExpiry(current_subscription.coupon_expire_at)">
                                                    <span class="mb-0 d-block">Coupon: {{ current_subscription.coupon.title }}</span>
                                                    <span class="mb-0 d-block">Discount: {{ current_subscription.coupon.discount }}</span>
                                                    <span class="mb-0 d-block">End Date: {{ dateFormat(current_subscription.coupon_expire_at, 'DD.MM.YYYY') }}</span>
                                                </template>
                                            </div>
                                        </td>
                                        <td class="text-capitalize">
                                            {{ priceFormat(current_subscription.amount) }}
                                            <div class="mt-2 fs-1 text-dark fw-bold" v-if="current_subscription.coupon != null && current_subscription.coupon_expire_at != null">
                                                <template v-if="checkExpiry(current_subscription.coupon_expire_at)">
                                                    Total amount with discount: {{ $page.props.currency_symbol }}{{ discountPrice(current_subscription.amount, current_subscription.coupon.amount, current_subscription.coupon.type) }}
                                                </template>
                                            </div>
                                        </td>
                                        <td class="align-middle fw-bolder text-capitalize" :class="{
                                            'text-primary': current_subscription.status == 'active' || current_subscription.status == 'trialing',
                                            'text-danger': current_subscription.status == 'unpaid' || current_subscription.status == 'past_due',
                                            'text-warning': current_subscription.status == 'paused'
                                        }">{{ subsStatuses.hasOwnProperty(current_subscription.status) ? subsStatuses[current_subscription.status] : current_subscription.status }}</td>
                                        <template v-if="current_subscription.status != 'paused' && current_subscription.status != 'unpaid'">
                                            <td class="align-middle">{{ current_subscription.next_billing_date ? dateFormat(current_subscription.next_billing_date, 'DD.MM.YYYY') : '-' }}</td>
                                        </template>
                                        <template v-else>
                                            <td class="align-middle">-</td>
                                        </template>
                                        <td class="align-middle">{{ current_subscription.start_at ? dateFormat(current_subscription.start_at, 'DD.MM.YYYY') : '-' }}</td>
                                        <td class="align-middle">-</td>
                                        <td class="align-middle">
                                            <div class="d-flex flex-wrap align-items-center gap-2">
                                                <Link :href="route('app.customer.subscription.billing')" class="btn btn-primary btn-sm">Billing Information</Link>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <template v-else-if="Object.keys(subscription).length>0">
                                    <tr>
                                        <td class="align-middle">
                                            {{ subscription.name }}
                                            <div class="mt-2 fs-1 text-dark fw-bold" v-if="subscription.coupon != null && subscription.coupon_expire_at != null">
                                                <template v-if="checkExpiry(subscription.coupon_expire_at)">
                                                    <span class="mb-0 d-block">Coupon: {{ subscription.coupon.title }}</span>
                                                    <span class="mb-0 d-block">Discount: {{ subscription.coupon.discount }}</span>
                                                    <span class="mb-0 d-block">End Date: {{ dateFormat(subscription.coupon_expire_at, 'DD.MM.YYYY') }}</span>
                                                </template>
                                            </div>
                                        </td>
                                        <td class="text-capitalize">
                                            {{ priceFormat(subscription.amount) }}
                                            <div class="mt-2 fs-1 text-dark fw-bold" v-if="subscription.coupon != null && subscription.coupon_expire_at != null">
                                                <template v-if="checkExpiry(subscription.coupon_expire_at)">
                                                    Total amount with discount: {{ $page.props.currency_symbol }}{{ discountPrice(subscription.amount, subscription.coupon.amount, subscription.coupon.type) }}
                                                </template>
                                            </div>
                                        </td>
                                        <td class="align-middle fw-bolder text-capitalize text-danger">
                                            {{ subsStatuses.hasOwnProperty(subscription.status) ? subsStatuses[subscription.status] : subscription.status }}
                                        </td>
                                        <td class="align-middle">-</td>
                                        <td class="align-middle">{{ subscription.start_at ? dateFormat(subscription.start_at, 'DD.MM.YYYY') : '-' }}</td>
                                        <td class="align-middle">{{ subscription.ended_at ? dateFormat(subscription.ended_at, 'DD.MM.YYYY') : '-' }}</td>
                                        <td class="align-middle">
                                            <div class="d-flex flex-wrap align-items-center gap-2">
                                                <Link :href="route('app.customer.subscription.billing')" class="btn btn-info btn-sm">Take a Plan</Link>
                                            </div>
                                        </td>
                                    </tr>
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
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-white border-bottom">
                    <h4 class="card-title">Subscriptions History</h4>
                </div>
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
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="status" class="fs-1 mb-1 fw-bold">Status</label>
                                            <select v-model="status" id="status" class="form-select form-select-sm" @change="getData()">
                                                <option value="">All</option>
                                                <template v-for="(statusValue, statusKey) in subsStatuses" :key="statusKey">
                                                    <option :value="statusKey">{{ statusValue }}</option>
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
                            </div>
                        </div>
                    </div>
                    <div v-if="renderPaginate" class="text-end fs-1 fw-bold text-capitalize py-2">Showing {{ paginate.from ? paginate.from : 0 }} to {{ paginate.to ? paginate.to : 0 }} of {{ paginate.total ? paginate.total : 0 }} records</div>
                    <div class="table-responsive rounded-2 mb-4">
                        <table class="table border text-nowrap customize-table mb-0 align-middle">
                            <thead class="text-dark fs-3">
                                <tr>
                                    <th>No.</th>
                                    <th>Subscription</th>
                                    <th>Amount</th>
                                    <th>Duration</th>
                                    <th>Status</th>
                                    <th>Trial End Date</th>
                                    <th>End Date</th>
                                </tr>
                            </thead>
                            <TransitionGroup tag="tbody" name="fade" class="text-dark fs-3">
                                <template v-if="collection.length>0">
                                    <template v-for="(item, index) in collection" :key="item.id">
                                        <tr>
                                            <td class="align-middle">{{ getItemNum(index) }}</td>
                                            <td class="align-middle text-capitalize">
                                                {{ item.name }}
                                                <div class="mt-2 fs-1 text-dark fw-bold" v-if="item.coupon != null && item.coupon_expire_at != null">
                                                    <template v-if="checkExpiry(item.coupon_expire_at)">
                                                        <span class="mb-0 d-block">Coupon: {{ item.coupon.title }}</span>
                                                        <span class="mb-0 d-block">Discount: {{ item.coupon.discount }}</span>
                                                        <span class="mb-0 d-block">End Date: {{ dateFormat(item.coupon_expire_at, 'DD.MM.YYYY') }}</span>
                                                    </template>
                                                </div>
                                            </td>
                                            <td class="text-capitalize">
                                                {{ priceFormat(item.amount) }}
                                                <div class="mt-2 fs-1 text-dark fw-bold" v-if="item.coupon != null && item.coupon_expire_at != null">
                                                    <template v-if="checkExpiry(item.coupon_expire_at)">
                                                        Total amount with discount: {{ $page.props.currency_symbol }}{{ discountPrice(item.amount, item.coupon.amount, item.coupon.type) }}
                                                    </template>
                                                </div>
                                            </td>
                                            <td class="align-middle text-capitalize">
                                                {{ item.package.duration_lifetime ? 'Lifetime' : item.package.duration +' '+ item.package.duration_type }}
                                            </td>
                                            <td class="align-middle fw-bolder text-capitalize" :class="{
                                                'text-primary': item.status == 'active' || item.status == 'trialing',
                                                'text-danger': item.status == 'unpaid' || item.status == 'past_due' || item.status == 'canceled' || item.status == 'incomplete' || item.status == 'incomplete_expired',
                                                'text-warning': item.status == 'paused'
                                            }">
                                                {{ subsStatuses.hasOwnProperty(item.status) ? subsStatuses[item.status] : item.status }}
                                            </td>
                                            <td class="align-middle">
                                                {{ item.trial_end_at ? dateFormat(item.trial_end_at, 'DD.MM.YYYY') : '-' }}
                                            </td>
                                            <td class="align-middle">
                                                {{ item.ended_at ? dateFormat(item.ended_at, 'DD.MM.YYYY') : '-' }}
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
                    <Pagination v-if="renderPaginate" :paginate="paginate" :url="route('app.customer.subscription.index')" :current="page" @items="getData"></Pagination>
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
import Multiselect from '@vueform/multiselect';

export default {
    components: {
        Head,
        Link,
        AppLayout,
        Breadcrumb,
        Pagination,
        Multiselect,
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
            current_subscription: {},
            subscription: {},
            websites: [],
            selectedWebsite: [],
            websitesOptions: [],
            website_form: {
                website_name: '',
                website_url: '',
                status: 'active'
            },
            websiteCreateFromToggle: false,
            subsStatuses: {
                active: 'Active', // Subscription is currently active
                trialing: 'Trialing', // Subscription is in a trial period
                paused: 'Paused', //  If the subscription is paused
                past_due: 'Past Due', // Payment is overdue
                canceled: 'Canceled', // Subscription has been canceled
                incomplete: 'Incomplete', // Subscription is not fully set up or payment is pending
                incomplete_expired: 'Incomplete Expired', // Subscription setup expired due to incomplete setup
                unpaid: 'Unpaid', // Payment failed and subscription is in unpaid state
            },
            errors: {},
            loader: false
        };
    },

    methods: {
        checkExpiry(date, format = 'YYYY-MM-DD HH:mm:ss') {
            return moment(date, format).isAfter(moment());
        },

        discountPrice(price, discount, type) {
            let discountPrice;

            if (type === 'fixed') {
                discountPrice = price - discount;
            }

            if (type === 'percent') {
                discountPrice = price - ((price / 100) * discount);
            }

            return discountPrice.toFixed(2);
        },

        priceFormat(price, symbol = true) {
            price = parseInt(price).toFixed(2);
            return symbol ? this.$page.props.currency_symbol + price : price; 
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

            if(this.status !== '') {
                params.status = this.status;
            }

            if(this.search !== '') {
                params.search = this.search;
            }

            if(this.dates != null) {
                params.dates = this.dates;
            }

            await axios.get(route('api.subscription.get.all'), {
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

        async getCurrentSubscription() {
            this.current_subscription = {};
            this.loader = true;

            await axios.get(route('api.subscription.current'), {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                this.current_subscription = $response.data;

                this.loader = false;
            }).catch((error) => {
                this.loader = false;
                console.log(error.response.data.message);
            });
        },

        async getSubscription() {
            this.subscription = {};
            this.loader = true;

            await axios.get(route('api.subscription.get.single'), {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                this.subscription = $response.data;

                this.loader = false;
            }).catch((error) => {
                this.loader = false;
                console.log(error.response.data.message);
            });
        },

        confirmCancelSubscription(event) {
            let ele = $(event.target);
            if(ele.prop("tagName").toLowerCase() != 'button') {
                ele = ele.closest('button');
            }

            Swal.fire({
                html: "Please confirm if you want cancel this subscription.",
                icon: 'warning',
                buttonsStyling: false,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: 'Cancel Subscription',
                cancelButtonText: 'No, return',
                customClass: {
                    htmlContainer: 'fs-4',
                    actions: 'm-0 mt-5 gap-3',
                    confirmButton: "btn btn-danger w-100 m-0",
                    cancelButton: "btn btn-active-light w-100 m-0"
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.cancelSubscription(ele);
                }
            });
        },

        async cancelSubscription(ele) {
            ele.prop('disabled', true);
            this.loader = true;
            await axios.post(route('api.subscription.cancel'), {}, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                this.$cookies.set('lxf-success-msg', $response.message, 10);
                this.$cookies.set('lxf-user', $response.data.user, moment(moment()).add(1, 'years').diff(moment(), 'seconds'));

                this.current_subscription = {};
                this.$inertia.visit(route(route().current()));
                ele.prop('disabled', false);
                this.loader = false;
            }).catch((error) => {
                ele.prop('disabled', false);
                this.loader = false;
                toast.error(error.response.data.message);
            });
        }
    },

    created() {
        this.getData();
        this.getCurrentSubscription();
        this.getSubscription();
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

<style src="@vueform/multiselect/themes/default.css"></style>