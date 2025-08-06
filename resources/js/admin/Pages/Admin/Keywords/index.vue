<template>

    <Head title="Keywords" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template #title>Keywords</template>
                <li class="breadcrumb-item text-muted">Customer</li>
                <li class="breadcrumb-item active" aria-current="page">Keywords</li>
            </Breadcrumb>

            <div class="mb-3">
                <div class="d-flex align-items-center justify-content-end gap-2">
                    <Link :href="route('app.admin.keyword.create')" class="btn btn-primary btn-sm">
                    <i class="ti ti-plus"></i> Add New
                    </Link>
                    <button @click="getData" class="btn btn-dark btn-sm">
                        <i class="ti ti-refresh"></i> Reload
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
                                            <input type="number" v-model="perpage" min="1" id="perpage"
                                                class="form-control form-control-sm" placeholder="10"
                                                @input="perPageSet()">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="status" class="fs-1 mb-1 fw-bold">Status</label>
                                            <select v-model="status" id="status" class="form-select form-select-sm"
                                                @change="getData()">
                                                <option value="">All</option>
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="search" class="fs-1 mb-1">Search</label>
                                            <input type="search" v-model="search" id="search"
                                                class="form-control form-control-sm" placeholder="Search"
                                                @input="getData()">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive rounded-2 mb-4">
                        <table class="table border text-nowrap customize-table mb-0 align-middle">
                            <thead class="text-dark fs-3">
                                <tr>
                                    <th width="50px">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" v-model="checkAll"
                                                @click="selectAll()">
                                        </div>
                                    </th>
                                    <th>Status Name</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <TransitionGroup tag="tbody" name="fade" class="text-dark fs-3">
                                <template v-if="collection.length > 0">
                                    <template v-for="(item, index) in collection" :key="item.id">
                                        <tr>
                                            <td class="align-middle">
                                                <template v-if="!item.has_leads">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input"
                                                            v-model="selectedItems" :value="item.id">
                                                    </div>
                                                </template>
                                                <template v-else>
                                                    <div class="form-check form-check-sm">
                                                        <input type="checkbox" class="form-check-input" disabled>
                                                    </div>
                                                </template>
                                            </td>
                                            <td>{{ item.keyword }}</td>
                                            <td>
                                            <td class="align-middle">
                                                <button type="button" class="btn btn-sm"
                                                    :class="item.status === 'active' ? 'btn-primary' : 'btn-danger'"
                                                    @click="toggleStatus($event, item)">
                                                    {{ item.status === 'active' ? 'Active' : 'Inactive' }}
                                                </button>
                                            </td>
                                            </td>
                                            <td>{{ dateFormat(item.created_at, 'DD-MM-YYYY') }}</td>
                                            <td class="align-middle">
                                                <div class="action-btn d-flex align-items-center gap-2">
                                                    <Link :href="route('app.admin.keyword.edit', [item.id])"
                                                        class="btn btn-sm btn-primary">
                                                    <i class="fs-4 ti ti-edit"></i>
                                                    Edit</Link>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </template>
                                <tr v-else>
                                    <td colspan="6" class="text-center">No Record Found</td>
                                </tr>
                            </TransitionGroup>
                        </table>
                    </div>
                    <Pagination v-if="renderPaginate" :paginate="paginate" :url="route('app.admin.keyword.index')"
                        :current="page" @items="getData" />
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
import { toast } from 'vue3-toastify';
import moment from 'moment';
import axios from 'axios';

export default {
    components: {
        Head,
        Link,
        AppLayout,
        Breadcrumb,
        Pagination,
    },

    data() {
        return {
            user: this.$cookies.get('lxf-user'),
            token: this.$cookies.get('lxf-token'),
            loader: false,
            selectedItems: [],
            collection: [],
            paginate: {},
            renderPaginate: true,
            checkAll: false,
            search: '',
            status: '',
            perpage: 10,
            page: 1,
        };
    },

    methods: {

        async toggleStatus(event = null, item) {
            let ele;

            if (event) {
                ele = $(event.target);
                if (ele.prop("tagName")?.toLowerCase() !== 'button') {
                    ele = ele.closest('button');
                }
                ele.prop('disabled', true);
                ele.html('<i class="ti ti-loader rotate"></i> Loading...');
            }

            this.loader = true;

            const newStatus = item.status === 'active' ? 'inactive' : 'active';

            try {
                const response = await axios.patch(route('api.keyword.toggle-status', [item.id]), {
                    status: newStatus
                }, {
                    headers: { Authorization: `Bearer ${this.token}` }
                });

                toast.success(response.data.message);

                // ✅ Update item status locally
                item.status = newStatus;

            } catch (error) {
                toast.error(error?.response?.data?.message || "Failed to update status");
            } finally {
                if (ele) {
                    ele.prop('disabled', false);
                    ele.html(item.status === 'active' ? 'Active' : 'Inactive');
                }
                this.loader = false;
            }
        },

        dateFormat(date, format = 'DD-MM-YYYY', cformat = null) {
            if (cformat) {
                return moment.utc(date, cformat).format(format);
            }

            return moment.utc(date).format(format);
        },

        perPageSet() {
            if (this.perpage > 100) this.perpage = 100;
            if (this.perpage <= 0 || this.perpage === '') this.perpage = 1;
            this.getData();
        },

        selectAll() {
            this.checkAll = !this.checkAll;
            this.selectedItems = this.checkAll ? this.collection.map(i => i.id) : [];
        },

        async getData(page = 1) {
            this.page = page;
            this.loader = true;
            this.renderPaginate = false;

            const params = {
                search: this.search,
                status: this.status,
                perpage: this.perpage,
                page: this.page,
                dates: this.dates,
            };

            try {
                const res = await axios.get(route('api.keyword.index'), {
                    params,
                    headers: { Authorization: `Bearer ${this.token}` },
                });
                this.collection = res.data.data;
                this.paginate = res.data.meta;
                this.page = this.paginate.current_page;
                this.renderPaginate = true;
            } catch (err) {
                console.log(err);

                toast.error('Failed to load data');
            } finally {
                this.loader = false;
            }
        },

    },

    mounted() {
        this.getData();
        this.$refs.app_layout.loadScript();
    }
}
</script>