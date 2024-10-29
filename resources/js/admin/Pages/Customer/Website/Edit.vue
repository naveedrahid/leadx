<template>
    <Head title="Edit Website" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template v-slot:title>Edit Website</template>
                <li class="breadcrumb-item"><Link :href="route('app.customer.website.index')" class="text-dark">Website</Link></li>
                <li class="breadcrumb-item text-muted" aria-current="page">Edit</li>
            </Breadcrumb>
            <div class="card">
                <div class="card-body p-4">
                    <form @submit.prevent="updateItem()">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="website_name" class="form-label">Website Name <span class="text-danger">*</span></label>
                                            <input type="text" id="website_name" class="form-control rounded" v-model="form.website_name">
                                            <div class="text-danger" v-if="errors.website_name">
                                                <small>{{ errors.website_name }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="website_url" class="form-label">Website Url <span class="text-danger">*</span></label>
                                            <input type="url" id="website_url" class="form-control rounded" v-model="form.website_url">
                                            <div class="text-danger" v-if="errors.website_url">
                                                <small>{{ errors.website_url }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>    
                            </div>
                            <div class="col-md-12">
                                <div class="d-flex align-items-center gap-2 mt-5">
                                    <button type="submit" class="btn btn-light-primary text-primary">
                                        <i class="ti ti-device-floppy"></i> Save Changes
                                    </button>
                                    <a :href="route('app.customer.website.index')" class="btn btn-light-danger text-danger">
                                        <i class="ti ti-x"></i> Cancel
                                    </a>
                                </div>
                            </div>
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
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

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
            form: {
                website_name: '',
                website_url: ''
            },
            errors: {},
            loader: false
        };
    },

    methods: {
        async getItem() {
            this.loader = true;
            await axios.get(route('api.website.get.single', [this.$page.props.id]), {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                this.item = $response.data;

                this.form.website_name = this.item.website_name ? this.item.website_name : '';
                this.form.website_url = this.item.website_url ? this.item.website_url : '';

                this.loader = false;
            }).catch((error) => {
                this.loader = false;
                toast.error(error.response.data.message);
            });
        },

        async updateItem() {
            this.errors = {};
            this.loader = true;

            await axios.post(route('api.website.update', [this.$page.props.id]), this.form, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token
                }
            }).then((response) => {
                let $response = response.data;
                this.$cookies.set('ay-success-msg', $response.message, 10);
                this.$inertia.visit(route('app.customer.website.index'));    
                this.loader = false;     
            }).catch((error) => {
                this.loader = false;
                if(error.response.status == 422) {
                    for (const key in error.response.data.data) {
                        this.errors[key] = error.response.data.data[key][0];
                    }
                }

                toast.error(error.response.data.message);
            });
        }
    },

    created() {
        this.getItem();
    },

    mounted() {
        this.$refs.app_layout.loadScript();
    }
}
</script>