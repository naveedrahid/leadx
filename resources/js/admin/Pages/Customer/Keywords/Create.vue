<template>

    <Head title="Create Keyword" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template #title>Create Keyword</template>
                <li class="breadcrumb-item text-muted">Customer</li>
                <li class="breadcrumb-item active" aria-current="page">Create Keyword</li>
            </Breadcrumb>

            <div class="card">
                <div class="card-body p-4">
                    <form @submit.prevent="submit">
                        <div class="form-group mb-3">
                            <label class="form-label">Keyword <span class="text-danger">*</span></label>
                            <input type="text" class="form-control rounded" v-model="form.keyword"
                                :class="{ 'is-invalid': errors.keyword }" />
                            <div class="invalid-feedback" v-if="errors.keyword">
                                {{ errors.keyword[0] }}
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 mt-5">
                            <button type="submit" class="btn btn-light-primary text-primary">
                                <i class="ti ti-device-floppy"></i> Save
                            </button>
                            <a :href="route('app.customer.keyword.index')" class="btn btn-light-danger text-danger">
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
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/admin/Layouts/AppLayout.vue';
import Breadcrumb from '@/admin/Components/Breadcrumb.vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

export default {
    components: {
        Head,
        AppLayout,
        Breadcrumb,
    },

    data() {
        return {
            user: this.$cookies.get('lxf-user'),
            token: this.$cookies.get('lxf-token'),
            form: {
                keyword: '',
            },
            errors: {},
            loader: false,
        };
    },

    methods: {
        async submit() {
            this.errors = {};
            if (!this.form.keyword.trim()) {
                toast.error("Keyword is required.");
                return;
            }

            this.loader = true;

            await axios.post(route('api.keyword.store'), this.form, {
                headers: {
                    Authorization: `Bearer ${this.token}`,
                    "Content-Type": "application/json",
                }
            }).then((response) => {
                this.$cookies.set('lxf-success-msg', 'Keyword Created Successfully', 10);
                this.form.keyword = '';
                toast.success('Keyword Created Successfully');
            }).catch((error) => {
                if (error.response) {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    } else {
                        toast.error(error.response.data.message || "Something went wrong.");
                    }
                } else {
                    toast.error("Unable to connect to the server. Please try again.");
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