<template>
    <Head title="Billing History" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template v-slot:title>Billing History</template>
                <li class="breadcrumb-item"><Link :href="route('app.admin.customer.subscription', [$page.props.id])" class="text-dark">Subscription</Link></li>
                <li class="breadcrumb-item"><Link :href="route('app.admin.customer.subscription.billing', [$page.props.id])" class="text-dark">Billing</Link></li>
                <li class="breadcrumb-item text-muted" aria-current="page">Billing History</li>
            </Breadcrumb>
            <div class="row mb-4">
                <div class="col-md-5">
                    <div class="card card-body flex-row align-items-start justify-content-between gap-3 border border-2 p-4" v-if="Object.keys(customer).length>0">
                        <div class="d-flex flex-row align-items-center gap-3">
                            <div class="rounded">
                                <template v-if="customer.profile_image_url">
                                    <img :src="customer.profile_image_url" class="rounded" :alt="customer.fullname" width="60">
                                </template>
                                <template v-else>
                                    <span class="text-white d-flex align-items-center justify-content-center text-center rounded fs-6 fw-bold bg-dark" style="width: 60px;height: 60px;">{{ customer.initials }}</span>
                                </template>
                            </div>
                            <div>
                                <h3 class="fs-4 mb-1 fw-semibold">
                                    <Link :href="route('app.admin.customer.show', [customer.id])" class="text-dark">{{ customer.fullname }}</Link>
                                </h3>
                                <span class="fs-2">{{ customer.email }}</span>
                            </div>
                        </div>
                        <div class="border border-2 rounded py-1 px-2 d-flex align-items-center gap-2">
                            <span class="rounded" :class="{
                                'bg-success': customer.status == 'active',
                                'bg-danger': customer.status == 'deactive'
                            }" style="width: 7px; height: 7px;"></span>
                            <span class="fs-2 fw-bold">{{ customer.status == 'active' ? 'Active' : 'Deactive' }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="d-flex align-items-center justify-content-end gap-2">
                        <Link :href="route('app.admin.customer.subscription', [$page.props.id])" class="btn btn-primary btn-sm">All Subscriptions</Link>
                        <Link :href="route('app.admin.customer.subscription.billing', [$page.props.id])" class="btn btn-primary btn-sm">Billing information</Link>
                        <Link :href="route('app.admin.customer.subscription.billing.history', [$page.props.id])" class="btn btn-primary btn-sm">Billing History</Link>
                        <Link :href="route('app.admin.customer.subscription.license', [$page.props.id])" class="btn btn-primary btn-sm">License</Link>
                    </div>
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
                                    <th>No.</th>
                                    <th>Title</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <TransitionGroup tag="tbody" name="fade" class="text-dark fs-3">
                                <template v-if="collection.length>0">
                                    <template v-for="(item, index) in collection" :key="item.id">
                                        <tr>
                                            <td class="align-middle">{{ getItemNum(index) }}</td>
                                            <td class="align-middle">
                                                {{ item.title }}
                                                <div class="mt-2 fs-1 text-dark fw-bold" v-if="item.coupon != null && item.coupon_expire_at != null">
                                                    <template v-if="checkExpiry(item.coupon_expire_at)">
                                                        <span class="mb-0 d-block">Coupon: {{ item.coupon.title }}</span>
                                                        <span class="mb-0 d-block">Discount: {{ item.coupon.discount }}</span>
                                                        <span class="mb-0 d-block">End Date: {{ dateFormat(item.coupon_expire_at, 'DD.MM.YYYY') }}</span>
                                                    </template>
                                                </div>
                                            </td>
                                            <td class="text-capitalize text-dark">
                                                {{ priceFormat(item.amount) }}
                                                <div class="mt-2 fs-1 text-dark fw-bold" v-if="item.coupon != null && item.coupon_expire_at != null">
                                                    <template v-if="checkExpiry(item.coupon_expire_at)">
                                                        Total amount with discount: {{ $page.props.currency_symbol }}{{ discountPrice(item.amount, item.coupon.amount, item.coupon.type) }}
                                                    </template>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <span class="badge fw-bolder" :class="{
                                                    'bg-primary': item.status == 'paid',
                                                    'bg-danger': item.status == 'uncollectible' || item.status == 'void',
                                                    'bg-warning': item.status == 'draft' || item.status == 'open' || item.status == 'upcoming'
                                                }">
                                                    {{ invoiceStatuses.hasOwnProperty(item.status) ? invoiceStatuses[item.status] : item.status }}
                                                </span>
                                            </td>
                                            <td class="align-middle">{{ item.date !== null ? dateFormat(item.date, 'DD.MM.YYYY') : '-' }}</td>
                                            <td class="align-middle"><a :href="route('app.admin.customer.subscription.invoice_download', [item.id, customer.id])" class="btn btn-dark btn-sm"><i class="ti ti-download"></i> Download PDF</a></td>
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
                    <Pagination v-if="renderPaginate" :paginate="paginate" :url="route('app.admin.customer.subscription.billing.history', [$page.props.id])" :current="page" @items="getData"></Pagination>
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
            customer: {},
            collection: [],
            paginate: {},
            perpage: 10,
            page: 1,
            orderBy: 'id',
            order: 'DESC',
            renderPaginate: true,
            status: '',
            search: '',
            dates: null,
            invoiceStatuses: {
                draft: 'Draft',  // Invoice created but not finalized
                open: 'Open',  // Invoice finalized and awaiting payment
                paid: 'Paid',  // Invoice successfully paid
                uncollectible: 'Uncollectible',  // Payment collection failed
                void: 'Void',  // Invoice has been canceled
                upcoming: 'Upcoming'  // Invoice is scheduled for the next billing cycle
            },
            loader: false
        };
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

        async getCustomer() {
            this.loader = true;
            await axios.get(route('api.customer.get.single', [this.$page.props.id]), {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                this.customer = $response.data;

                this.loader = false;
            }).catch((error) => {
                this.loader = false;
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
                page: this.page,
                user_id: this.$page.props.id
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

            await axios.get(route('api.subscription.invoices'), {
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
        }
    },

    created() {
        this.getCustomer();
        this.getData();
    },

    mounted() {
        this.$refs.app_layout.loadScript();
    }
}
</script>
