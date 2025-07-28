<template>

    <Head title="Create Lead Status" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template #title>Create Lead Status</template>
                <li class="breadcrumb-item text-muted">Customer</li>
                <li class="breadcrumb-item active" aria-current="page">Create Lead Status</li>
            </Breadcrumb>

            <div class="card">
                <div class="card-body p-4">
                    <form @submit.prevent="submit">
                        <div class="form-group mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control rounded" v-model="form.name"
                                :class="{ 'is-invalid': errors.name }" />
                            <div class="invalid-feedback" v-if="errors.name">
                                {{ errors.name[0] }}
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 mt-5">
                            <button type="submit" class="btn btn-light-primary text-primary">
                                <i class="ti ti-device-floppy"></i> Save
                            </button>
                            <a :href="route('app.customer.lead-statuses.index')"
                                class="btn btn-light-danger text-danger">
                                <i class="ti ti-x"></i> Cancel
                            </a>
                        </div>
                    </form>
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

export default {
    components: {
        Head,
        Link,
        AppLayout,
        Breadcrumb,
    },

    data() {
        return {
            user: this.$cookies.get('lxf-user'),
            token: this.$cookies.get('lxf-token'),
            form: {
                name: '',
                status: 'inactive',
            },
            errors: {},
            loader: false,
        };
    },

    methods: {
        async submit() {
            this.errors = {};

            if (!this.form.name.trim()) {
                toast.error("Name is required.");
                return;
            }

            this.loader = true;

            await axios.post(route('api.lead-statuses.store'), this.form, {
                headers: {
                    Authorization: `Bearer ${this.token}`,
                    "Content-Type": "application/json",
                }
            }).then((response) => {
                let $response = response.data;
                this.$cookies.set('lxf-success-msg', 'Lead Status Created Successfully', 10);
                this.$inertia.visit(route('app.customer.lead-statuses.index'));
            }).catch((error) => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors;
                } else {
                    toast.error(error.response.data.message || "Something went wrong.");
                }
            }).finally(() => {
                this.loader = false;
            });
        }
    },

    mounted() {
        this.$refs.app_layout.loadScript();
    }
}
</script>