<template>

    <Head title="Lead Statuses" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template #title>Lead Statuses</template>
                <li class="breadcrumb-item text-muted">Customer</li>
                <li class="breadcrumb-item active" aria-current="page">Lead Statuses</li>
            </Breadcrumb>

            <div class="mb-3">
                <div class="d-flex align-items-center justify-content-end gap-2">
                    <Link :href="route('app.customer.lead-statuses.create')" class="btn btn-primary btn-sm"><i
                        class="ti ti-plus"></i> Add
                    New</Link>
                    <Link :href="route('app.customer.lead-statuses.index')" class="btn btn-dark btn-sm"><i
                        class="ti ti-refresh"></i>
                    Reload</Link>
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
                            <div class="col-md-3">
                                <div class="mb-3 form-input-sm">
                                    <label for="dates" class="fs-1 mb-1">Search By Dates</label>
                                    <VueDatePicker v-model="dates" @update:model-value="datesChange" range
                                        multi-calendars :enable-time-picker="false" placeholder="Select Date">
                                    </VueDatePicker>
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
                                        <button class="btn btn-light text-dark input-group-text" id="bulk-action-apply"
                                            @click="bulkActionApply()">Apply</button>
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
                                    <th>No.</th>
                                    <th>Status Name</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <TransitionGroup tag="tbody" name="fade" class="text-dark fs-3">
                                <template v-if="collection.length">
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
                                            <td>{{ getItemNum(index) }}</td>
                                            <td>{{ item.name }}</td>
                                            <td>
                                            <td class="align-middle">
                                                <template v-if="item.status == 'active'">
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        @click.prevent="updateItemStatus($event, item, 'inactive')">Active</button>
                                                </template>
                                                <template v-if="item.status == 'inactive'">
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        @click.prevent="updateItemStatus($event, item, 'active')">Inactive</button>
                                                </template>
                                            </td>
                                            </td>
                                            <td>{{ dateFormat(item.created_at, 'DD-MM-YYYY') }}</td>
                                            <td class="align-middle">
                                                <div class="action-btn d-flex align-items-center gap-2">
                                                    <Link :href="route('app.customer.lead-statuses.edit', [item.id])"
                                                        class="btn btn-sm btn-primary"><i class="fs-4 ti ti-edit"></i>
                                                    Edit</Link>
                                                    <button class="btn btn-sm btn-danger"
                                                        @click="deleteItem($event, item)"><i
                                                            class="fs-4 ti ti-trash"></i> Delete</button>
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

                    <!-- Pagination -->
                    <Pagination v-if="renderPaginate" :paginate="paginate"
                        :url="route('app.customer.lead-statuses.index')" :current="page" @items="getData" />
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
import Swal from 'sweetalert2';
import moment from 'moment';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import '@vuepic/vue-datepicker/dist/main.css';

export default {
    components: { Head, Link, AppLayout, Breadcrumb, Pagination, VueDatePicker },

    data() {
        return {
            user: this.$cookies.get('lxf-user'),
            token: this.$cookies.get('lxf-token'),
            loader: false,
            checkAll: false,
            selectedItems: [],
            collection: [],
            paginate: {},
            renderPaginate: true,
            search: '',
            status: '',
            perpage: 10,
            page: 1,
            dates: null,
            bulkAction: '',
        };
    },

    methods: {
        getItemNum(index) {
            return ((this.page - 1) * this.perpage + index + 1).toString().padStart(2, '0');
        },

        dateFormat(date, format = 'DD-MM-YYYY', cformat = null) {
            if (cformat) {
                return moment.utc(date, cformat).format(format);
            }

            return moment.utc(date).format(format);
        },

        selectAll() {
            this.checkAll = !this.checkAll;
            this.selectedItems = this.checkAll ? this.collection.map(i => i.id) : [];
        },

        async updateItemStatus(event, item, status) {
            let ele = $(event.target);
            if (ele.prop("tagName").toLowerCase() != 'button') {
                ele = ele.closest('button');
            }

            ele.prop('disabled', true);
            ele.html('<i class="ti ti-loader rotate"></i> Loading...');
            await axios.post(route('api.lead-statuses.status', [item.id]), {
                status: status
            }, {
                headers: { Authorization: `Bearer ${this.token}` },
            }).then((response) => {
                let $response = response.data;
                toast.success($response.message);
                this.collection.map((value, index) => {
                    if (value.id == item.id) {
                        this.collection[index].status = status;
                    }
                });

                ele.prop('disabled', false);
                ele.html((status == 'active' ? 'Active' : 'Inactive'));
            }).catch((error) => {
                ele.prop('disabled', false);
                ele.html((status == 'active' ? 'Active' : 'Inactive'));
                toast.error(error.response.data.message);
            });
        },

        datesChange() {
            if (this.dates) {
                this.dates = this.dates.map(d => moment(d).format('YYYY-MM-DD'));
            }
            this.getData();
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
                const res = await axios.get(route('api.lead-statuses.index'), {
                    params,
                    headers: { Authorization: `Bearer ${this.token}` },
                });
                this.collection = res.data.data;
                this.paginate = res.data.meta;
                this.page = this.paginate.current_page;
                this.renderPaginate = true;
            } catch (err) {
                toast.error('Failed to load data');
            } finally {
                this.loader = false;
            }
        },

        perPageSet() {
            if (this.perpage > 100) this.perpage = 100;
            if (this.perpage <= 0 || this.perpage === '') this.perpage = 1;
            this.getData();
        },

        async deleteItem(event, item) {
            const confirm = await Swal.fire({
                html: "Please confirm if you want delete.",
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
            });

            if (confirm.isConfirmed) {
                await axios.delete(route('api.lead-statuses.destroy', [item.id]), {
                    headers: { Authorization: `Bearer ${this.token}` },
                });
                toast.success('Deleted successfully');
                this.getData();
            }
        },

        bulkActionApply() {
            let self = this;
            if (self.bulkAction == '') {
                toast.warning('Please choose a bulk action.');
                return false;
            }

            if (self.selectedItems.length == 0) {
                toast.warning('Please select an item for bulk action.');
                return false;
            }

            if (self.bulkAction == 'delete') {
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
                        self.bulkDelete();
                    }
                });
            }
        },

        async bulkDelete() {
            this.loader = true;
            document.getElementById('bulk-action-apply').disabled = true;

            try {
                await axios.post(route('api.lead-statuses.bulk-delete'), {
                    ids: this.selectedItems,
                }, {
                    headers: {
                        Authorization: `Bearer ${this.token}`,
                    },
                });

                toast.success('Selected items deleted!');
                this.selectedItems = [];
                this.bulkAction = '';
                this.getData();
            } catch (error) {
                toast.error('Something went wrong.');
            } finally {
                this.loader = false;
                document.getElementById('bulk-action-apply').disabled = false;
            }
        }

    },

    created() {
        this.getData();
    },

    mounted() {
        const successMsg = this.$cookies.get('lxf-success-msg');
        if (successMsg) {
            toast.success(successMsg);
            this.$cookies.remove('lxf-success-msg');
        }
        this.$refs.app_layout.loadScript();
    },
};
</script>
<style>
.fade-enter-active,
.fade-leave-active {
    transition: opacity .5s;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}
</style>