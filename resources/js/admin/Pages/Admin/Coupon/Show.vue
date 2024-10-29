<template>
    <Head title="Coupon Details" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template v-slot:title>Coupon Details</template>
                <li class="breadcrumb-item"><Link :href="route('app.admin.coupon.index')" class="text-dark">Coupons</Link></li>
                <li class="breadcrumb-item text-muted" aria-current="page">Coupon Details</li>
            </Breadcrumb>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-end gap-2 mb-5">
                        <Link :href="route('app.admin.coupon.edit', [$page.props.id])" class="btn btn-info">Edit</Link>
                        <template v-if="!item.has_subscriptions">
                            <button class="btn btn-danger" @click="deleteItem($event, item)">Delete</button>
                        </template>
                        <template v-else>
                            <button class="btn btn-danger" disabled>Delete</button>
                        </template>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <strong class="fs-4 fw-bolder mb-1 d-block">Title:</strong>
                                        <span class="fs-3 fw-bold">{{ item.title }}</span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <strong class="fs-4 fw-bolder mb-1 d-block">Code:</strong>
                                        <span class="fs-3 fw-bold">{{ item.code }}</span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <strong class="fs-4 fw-bolder mb-1 d-block">Description:</strong>
                                        <span class="fs-3 fw-bold">{{ item.description }}</span>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <strong class="fs-4 fw-bolder mb-1">Status: </strong>
                                        <template v-if="item.status == 'active'">
                                            <span class="fs-3 fw-bold text-primary text-capitalize">{{ item.status }}</span>
                                        </template>
                                        <template v-else>
                                            <span class="fs-3 fw-bold text-dark text-capitalize">{{ item.status }}</span>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <strong class="fs-4 fw-bolder mb-1 d-block">Discount:</strong>
                                        <span class="fs-3 fw-bold">{{ item.discount }}</span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <strong class="fs-4 fw-bolder mb-1 d-block">Duration:</strong>
                                        <span class="fs-3 fw-bold">
                                            <template v-if="item.duration == 'once'">Once</template>
                                            <template v-if="item.duration == 'repeating'">Repeating ({{ item.duration_month }} Month)</template>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <strong class="fs-4 fw-bolder mb-1 d-block">Max Uses:</strong>
                                        <span class="fs-3 fw-bold">{{ item.max_uses }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <strong class="fs-4 fw-bolder mb-1 d-block">Max Uses Per User:</strong>
                                        <span class="fs-3 fw-bold">{{ item.max_uses_user }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <strong class="fs-4 fw-bolder mb-1 d-block">Expiry:</strong>
                                        <span class="fs-3 fw-bold">{{ dateFormat(item.expires_at, 'DD.MM.YYYY') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <strong class="fs-4 fw-bolder mb-1 d-block">Date: </strong>
                                        <span class="fs-3 fw-bold">{{ dateFormat(item.created_at, 'DD/MM/YYYY') }}</span>
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
import Breadcrumb from '@/admin/Components/Breadcrumb.vue';
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
        Breadcrumb
    },

    data() {
        return {
            user: this.$cookies.get("lxf-user"),
            token: this.$cookies.get("lxf-token"),
            item: {},
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

        async getItem() {
            this.loader = true;
            await axios.get(route('api.coupon.get.single', [this.$page.props.id]), {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                this.item = $response.data;

                this.loader = false;
            }).catch((error) => {
                this.loader = false;
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
                    self.delete(item);
                }
            });
        },

        async delete(item) {
            this.loader = true;
            await axios.post(route('api.coupon.delete', [item.id]), {}, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                toast.success($response.message);
                this.$cookies.set('lxf-success-msg', $response.message, 10);
                this.$inertia.visit(route('app.admin.coupon.index'));

                this.loader = false;
            }).catch((error) => {
                this.loader = false;
                toast.error(error.response.data.message);
            });
        },
    },

    created() {
        this.getItem();
    },

    mounted() {
        this.$refs.app_layout.loadScript();
    }
}
</script>