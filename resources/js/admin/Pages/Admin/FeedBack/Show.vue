<template>
    <Head title="Feedback Details" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template v-slot:title>Feedback Details</template>
                <li class="breadcrumb-item"><Link :href="route('app.admin.feedback.index')" class="text-dark">Feedbacks</Link></li>
                <li class="breadcrumb-item text-muted" aria-current="page">Feedback Details</li>
            </Breadcrumb>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-end gap-2 mb-5">
                        <button class="btn btn-danger" @click="deleteItem($event, item)">Delete</button>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <span class="fs-2 fw-bolder mb-1 d-block">Name:</span>
                                        <span class="fs-3 fw-bold">{{ item.name }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <span class="fs-2 fw-bolder mb-1 d-block">Email:</span>
                                        <span class="fs-3 fw-bold">{{ item.email }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <span class="fs-2 fw-bolder mb-1 d-block">Subject:</span>
                                        <span class="fs-3 fw-bold">{{ item.subject }}</span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <span class="fs-2 fw-bolder mb-1">Message: </span>
                                        <span class="fs-3 fw-bold">{{ item.message }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <span class="fs-2 fw-bolder mb-1">Date: </span>
                                        <span class="fs-3 fw-bold">{{ dateFormat(item.created_at, 'DD.MM.YYYY') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <span class="fs-2 fw-bolder mb-1 d-block">IP Address:</span>
                                        <span class="fs-3 fw-bold">{{ item.ip_address }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <span class="fs-2 fw-bolder mb-1 d-block">Country Code:</span>
                                        <span class="fs-3 fw-bold">{{ item.country_code }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <span class="fs-2 fw-bolder mb-1 d-block">Country:</span>
                                        <span class="fs-3 fw-bold">{{ item.country_name }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <span class="fs-2 fw-bolder mb-1 d-block">City:</span>
                                        <span class="fs-3 fw-bold">{{ item.city }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <span class="fs-2 fw-bolder mb-1 d-block">Region:</span>
                                        <span class="fs-3 fw-bold">{{ item.region }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <span class="fs-2 fw-bolder mb-1 d-block">Latitude:</span>
                                        <span class="fs-3 fw-bold">{{ item.latitude }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <span class="fs-2 fw-bolder mb-1 d-block">Longitude:</span>
                                        <span class="fs-3 fw-bold">{{ item.longitude }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <span class="fs-2 fw-bolder mb-1 d-block">Timezone:</span>
                                        <span class="fs-3 fw-bold">{{ item.timezone }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <span class="fs-2 fw-bolder mb-1 d-block">Continent Code:</span>
                                        <span class="fs-3 fw-bold">{{ item.continent_code }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <span class="fs-2 fw-bolder mb-1 d-block">Continent:</span>
                                        <span class="fs-3 fw-bold">{{ item.continent_name }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <span class="fs-2 fw-bolder mb-1 d-block">Currency Code:</span>
                                        <span class="fs-3 fw-bold">{{ item.currency_code }}</span>
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
        dateFormat(date, format, cformat = null) {
            if(cformat) {
                return moment(date, cformat).format(format);
            }

            return moment(date).format(format);
        },

        async getItem() {
            this.loader = true;
            await axios.get(route('api.feedback.get.single', [this.$page.props.id]), {
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
            await axios.post(route('api.feedback.delete', [item.id]), {}, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                toast.success($response.message);
                this.$cookies.set('lxf-success-msg', $response.message, 10);
                this.$inertia.visit(route('app.admin.feedback.index'));

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