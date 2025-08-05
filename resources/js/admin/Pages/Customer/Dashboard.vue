<template>
    <Head title="Dashboard" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <div class="mb-3">
                <div class="row justify-content-between">
                    <div class="col-md-5">
                        <div class="card card-body flex-row align-items-center gap-3 border border-2 p-4">
                            <div class="rounded">
                                <template v-if="user.profile_image_url">
                                    <img :src="user.profile_image_url" class="rounded" :alt="user.fullname" width="60">
                                </template>
                                <template v-else>
                                    <span class="text-white d-flex align-items-center justify-content-center text-center rounded fs-6 fw-bold bg-primary" style="width: 60px;height: 60px;">{{ user.initials }}</span>
                                </template>
                            </div>
                            <div>
                                <h3 class="fs-4 mb-1 fw-semibold">Hi, <span class="text-dark">{{ user.fullname }}</span></h3>
                                <span class="fs-2">Welcome To {{ $page.props.app.name }} - {{ current_date() }}</span>
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
            <div class="row">
                <div class="col-md-12">
                    <div class="row gx-3">
                        <div class="col-lg-3 col-6">
                            <div class="card card-body border border-2 p-3 px-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="card-text mb-0 text-muted fs-3 fw-bolder fw-normal">Total Leads</p>
                                    <span class="fs-8 text-muted"><i class="ti ti-list-check"></i></span>
                                </div>
                                <h3 class="card-title fs-7 fw-bolder mb-0 text-dark">{{ numFormat(dashboard_count.leads.total_leads) }}</h3>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="card card-body border border-2 p-3 px-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="card-text mb-0 text-muted fs-3 fw-bolder fw-normal">Viewed Leads</p>
                                    <span class="fs-8 text-muted"><i class="ti ti-eye"></i></span>
                                </div>
                                <h3 class="card-title fs-7 fw-bolder mb-0 text-dark">{{ numFormat(dashboard_count.leads.total_viewed) }}</h3>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="card card-body border border-2 p-3 px-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="card-text mb-0 text-muted fs-3 fw-bolder fw-normal">Unviewed Leads</p>
                                    <span class="fs-8 text-muted"><i class="ti ti-eye-off"></i></span>
                                </div>
                                <h3 class="card-title fs-7 fw-bolder mb-0 text-dark">{{ numFormat(dashboard_count.leads.total_unviewed) }}</h3>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="card card-body border border-2 p-3 px-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="card-text mb-0 text-muted fs-3 fw-bolder fw-normal">Spam Leads</p>
                                    <span class="fs-8 text-muted"><i class="ti ti-ban"></i></span>
                                </div>
                                <h3 class="card-title fs-7 fw-bolder mb-0 text-dark">{{ numFormat(dashboard_count.leads.is_spam) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12" v-if="Object.keys(current_subscription).length>0">
                    <div class="row gx-3">
                        <div class="col-lg-6 col-md-12">
                            <div class="card card-body p-4 border border-2">
                                <div class="mb-4">
                                    <p class="mb-1 fw-bolder text-muted fs-3">Current Subscription</p>
                                    <div class="p-3 border border-2 rounded">
                                        <h5 class="fs-4 fw-bolder mb-1 text-dark">{{ current_subscription.name }} - <span class="fw-bolder text-dark">{{ priceFormat(current_subscription.amount) }}</span></h5>
                                        <p class="fs-3 fw-bold mb-1 text-danger" v-if="current_subscription.trial_start_at !== null">7 Days Free Trial</p>
                                        <p class="fs-1 fw-bold mb-0 text-muted"><strong>Next Billing Date: </strong> {{ current_subscription.next_billing_date !== null ? dateFormat(current_subscription.next_billing_date, 'DD.MM.YYYY') : '-' }}</p>
                                    </div>
                                </div>
                                <div class="mb-4" v-if="current_subscription.websites.length>0">
                                    <p class="mb-3 fw-bolder text-muted fs-3">Current Subscription Websites</p>
                                    <div class="d-flex flex-column gap-2">
                                        <template v-for="website in current_subscription.websites" :key="website.id">
                                            <div class="d-flex align-items-center gap-3 py-2 px-3 border border-2 rounded">
                                                <div>
                                                    <h6 class="mb-0 fw-semibold">{{ website.website_name }}</h6>
                                                    <span class="fs-2">{{ website.website_url }}</span>
                                                </div>
                                                <div class="ms-auto text-end">
                                                    <span class="fs-2 fw-bolder text-capitalize rounded-1 py-2 px-3" :class="{
                                                        'bg-light-info text-info': website.status == 'active',
                                                        'bg-light-danger text-danger': website.status == 'deactive',
                                                    }">{{ website.status }}</span>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/admin/Layouts/AppLayout.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import Swal from 'sweetalert2/dist/sweetalert2.js';
import 'sweetalert2/dist/sweetalert2.css';
import moment from 'moment';

export default {
    components: {
        Head,
        Link,
        AppLayout,
        VueDatePicker
    },

    data() {
        return {
            user: this.$cookies.get('lxf-user'),
            token: this.$cookies.get("lxf-token"),
            dashboard_count: {
                leads: {
                    total_leads: 0,
                    total_viewed: 0,
                    total_unviewed: 0
                }
            },
            current_subscription: {},
            dates: [],
            loader: false
        }
    },

    methods: {
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

        current_date() {
            return moment().format('MMMM DD YYYY');
        },

        numFormat(number) {
            return (number < 10) ? '0'+number : number;
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
            this.getDashboardData();
        },

        async getDashboardData() {
            let params = {};
            if(this.dates != null) {
                params.dates = this.dates;
            }
            
            this.loader = true;
            await axios.get(route('api.customer_dashboard.get_data'), {
                params: params,
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                console.log(response.data);
                
                let $response = response.data;
                this.dashboard_count = $response.data;
                
                this.loader = false;
            }).catch((error) => {
                this.loader = false;
                console.log(error.response.data.message);
            });
        },

        async getCurrentSubscription() {
            this.loader = true;
            await axios.get(route('api.subscription.current'), {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                if($response.data) {
                    this.current_subscription = $response.data;
                }
                this.loader = false;
            }).catch((error) => {
                this.loader = false;
                console.log(error.response.data.message);
            });
        }
    },

    created() {
        this.getDashboardData();
        this.getCurrentSubscription();
    },

    mounted() {
        this.$refs.app_layout.loadScript();
    }
}
</script>