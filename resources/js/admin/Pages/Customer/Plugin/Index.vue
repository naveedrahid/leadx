<template>
    <Head title="Your Downloads" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template v-slot:title>Your Downloads</template>
                <li class="breadcrumb-item text-muted" aria-current="page">Your Downloads</li>
            </Breadcrumb>
            <div class="card">
                <div class="card-body">
                    <div v-if="renderPaginate" class="text-end fs-1 fw-bold text-capitalize py-2">Showing {{ paginate.from ? paginate.from : 0 }} to {{ paginate.to ? paginate.to : 0 }} of {{ paginate.total ? paginate.total : 0 }} records</div>
                    <div class="table-responsive rounded-2 mb-4">
                        <table class="table border text-nowrap customize-table mb-0 align-middle">
                            <thead class="text-dark fs-3">
                                <tr>
                                    <th>Plugin</th>
                                    <th>Version</th>
                                    <th>Documentation</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <TransitionGroup tag="tbody" name="fade" class="text-dark fs-3">
                                <template v-if="collection.length>0">
                                    <template v-for="(item, index) in collection" :key="item.id">
                                        <tr>
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
                                                    <a :href="item.documentation" target="_blank"><i class="ti ti-notebook"></i> Documentation</a>
                                                </template>
                                                <template v-else>
                                                    None
                                                </template>
                                            </td>
                                            <td class="align-middle"><a :href="item.plugin_file_url" class="text-danger fw-bolder"><i class="ti ti-download"></i> Download</a></td>
                                        </tr>
                                    </template>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td colspan="4">
                                            <div class="py-5 d-flex justify-content-center aling-items-center">
                                                <strong class="fs-4">No Record Found!</strong>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </TransitionGroup>
                        </table>
                    </div>
                    <Pagination v-if="renderPaginate" :paginate="paginate" :url="route('app.customer.plugin.index')" :current="page" @items="getData"></Pagination>
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
import '@vuepic/vue-datepicker/dist/main.css';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import Swal from 'sweetalert2/dist/sweetalert2.js';
import 'sweetalert2/dist/sweetalert2.css';

export default {
    components: {
        Head,
        Link,
        AppLayout,
        Breadcrumb,
        Pagination
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
            renderPaginate: true,
            loader: false
        };
    },

    methods: {
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
                status: 'active'
            };

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
    },

    created() {
        this.getData();
    },

    mounted() {
        this.$refs.app_layout.loadScript();
    }
}
</script>