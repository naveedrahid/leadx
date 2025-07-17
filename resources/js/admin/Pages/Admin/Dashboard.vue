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
                                    <p class="card-text mb-0 text-muted fs-3 fw-bolder fw-normal">All Customers</p>
                                    <span class="fs-8 text-muted"><i class="ti ti-users"></i></span>
                                </div>
                                <h3 class="card-title fs-7 fw-bolder mb-0 text-dark">{{ numFormat(dashboard_count.customers.total_customers) }}</h3>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="card card-body border border-2 p-3 px-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="card-text mb-0 text-muted fs-3 fw-bolder fw-normal">Active Customers</p>
                                    <span class="fs-8 text-muted"><i class="ti ti-users"></i></span>
                                </div>
                                <h3 class="card-title fs-7 fw-bolder mb-0 text-dark">{{ numFormat(dashboard_count.customers.total_active_customers) }}</h3>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="card card-body border border-2 p-3 px-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="card-text mb-0 text-muted fs-3 fw-bolder fw-normal">Total Subscription</p>
                                    <span class="fs-8 text-muted"><i class="ti ti-currency-dollar"></i></span>
                                </div>
                                <h3 class="card-title fs-7 fw-bolder mb-0 text-dark">{{ numFormat(dashboard_count.subscriptions.total_subscriptions) }}</h3>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="card card-body border border-2 p-3 px-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="card-text mb-0 text-muted fs-3 fw-bolder fw-normal">Active Subscription</p>
                                    <span class="fs-8 text-muted"><i class="ti ti-currency-dollar"></i></span>
                                </div>
                                <h3 class="card-title fs-7 fw-bolder mb-0 text-dark">{{ numFormat(dashboard_count.subscriptions.total_active_subscriptions) }}</h3>
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
                customers: {
                    total_customers: 0,
                    total_active_customers: 0,
                    total_deactive_customers: 0
                },
                subscriptions: {
                    total_subscriptions: 0,
                    total_active_subscriptions: 0,
                    total_trialing_subscriptions: 0,
                    total_canceled_subscriptions: 0
                }
            },
            dates: [],
            loader: false
        };
    },

    methods: {
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
            await axios.get(route('api.admin_dashboard.get_data'), {
                params: params,
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                this.dashboard_count = $response.data;
                
                this.loader = false;
            }).catch((error) => {
                this.loader = false;
                console.log(error.response.data.message);
            });
        }
    },

    created() {
        this.getDashboardData();
    },

    mounted() {
        this.$refs.app_layout.loadScript();
    }
}
</script>