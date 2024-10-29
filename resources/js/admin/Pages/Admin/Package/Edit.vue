<template>
    <Head title="Edit Package" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template v-slot:title>Edit Package</template>
                <li class="breadcrumb-item"><Link :href="route('app.admin.package.index')" class="text-dark">Packages</Link></li>
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
                                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                            <input type="text" id="title" class="form-control rounded" v-model="form.title" placeholder="e.g. 3 Month Plan">
                                            <div class="text-danger" v-if="errors.title">
                                                <small>{{ errors.title }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <div class="form-check form-check-inline">
                                                <input type="checkbox" class="form-check-input primary check-outline outline-primary" id="recommended" v-model="form.recommended">
                                                <label class="form-check-label" for="recommended">Make this plan recommended.</label>
                                            </div>
                                            <div class="text-danger" v-if="errors.recommended">
                                                {{ errors.recommended }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group my-5 border p-3 rounded">
                                            <div class="d-flex align-items-center justify-content-between gap-3" :class="{
                                                'mb-4': form.features.length>0
                                            }">
                                                <label class="form-label" for="features">Features</label>
                                                <button type="button" class="btn btn-primary btn-sm" @click.prevent="form.features.push('')">
                                                    <i class="bi bi-plus-lg"></i> Add Feature
                                                </button>
                                            </div>
                                            <template v-if="form.features.length>0">
                                                <div class="d-flex flex-column align-items-start gap-5">
                                                    <div class="d-flex flex-column gap-3 w-100">
                                                        <template v-for="(feature, index) in form.features">
                                                            <div class="fv-row">
                                                                <div class="input-group">
                                                                    <input type="text" v-model="form.features[index]" :id="'features-'+index" class="form-control" placeholder="e.g. Standard Support">
                                                                    <button type="button" class="input-group-text btn btn-light-danger text-danger btn-sm d-flex align-items-center gap-2" @click="form.features.splice(index, 1)">
                                                                        <i class="ti ti-x fs-4"></i> Remove
                                                                    </button>
                                                                </div>
                                                                <div class="text-danger" v-if="errors['features.'+index]">
                                                                    {{ (errors['features.'+index]).replace('features.'+index, 'feature') }}
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea id="description" class="form-control rounded" rows="5" v-model="form.description" placeholder="e.g. Write something about package..."></textarea>
                                            <div class="text-danger" v-if="errors.description">
                                                <small>{{ errors.description }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="sort" class="form-label">Sort</label>
                                            <input type="number" min="0" class="form-control" id="sort" v-model="form.sort" placeholder="e.g. 0">
                                            <div class="text-danger" v-if="errors.sort">
                                                <small>{{ errors.sort }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>    
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="website_limit" class="form-label">Website Limit <span class="text-danger">*</span></label>
                                            <div class="mb-1">
                                                <div class="form-check form-check-inline">
                                                    <input type="checkbox" class="form-check-input primary check-outline outline-primary" id="unlimited_websites" @change.prevent="form.unlimited_websites == true ? form.website_limit = '' : ''" v-model="form.unlimited_websites">
                                                    <label class="form-check-label" for="unlimited_websites">Unlimited Website</label>
                                                </div>
                                                <div class="text-danger" v-if="errors.unlimited_websites">
                                                    <small>{{ errors.unlimited_websites }}</small>
                                                </div>
                                            </div>
                                            <input type="number" min="0" class="form-control" id="website_limit" v-model="form.website_limit" placeholder="e.g. 5" :disabled="form.unlimited_websites" :readonly="form.unlimited_websites">
                                            <div class="text-danger" v-if="errors.website_limit">
                                                <small>{{ errors.website_limit }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="lead_limit" class="form-label">Lead Limit <span class="text-danger">*</span></label>
                                            <div class="mb-1">
                                                <div class="form-check form-check-inline">
                                                    <input type="checkbox" class="form-check-input primary check-outline outline-primary" id="unlimited_leads" @change.prevent="form.unlimited_leads == true ? form.lead_limit = '' : ''" v-model="form.unlimited_leads">
                                                    <label class="form-check-label" for="unlimited_leads">Unlimited Leads</label>
                                                </div>
                                                <div class="text-danger" v-if="errors.unlimited_leads">
                                                    <small>{{ errors.unlimited_leads }}</small>
                                                </div>
                                            </div>
                                            <input type="number" min="0" class="form-control" id="lead_limit" v-model="form.lead_limit" placeholder="e.g. 10" :disabled="form.unlimited_leads" :readonly="form.unlimited_leads">
                                            <div class="text-danger" v-if="errors.lead_limit">
                                                <small>{{ errors.lead_limit }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <div class="form-check form-check-inline">
                                                <input type="checkbox" class="form-check-input primary check-outline outline-primary" id="app_access" v-model="form.app_access">
                                                <label class="form-check-label" for="app_access">Add Access</label>
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
                                    <Link :href="route('app.admin.package.index')" class="btn btn-light-danger text-danger">
                                        <i class="ti ti-x"></i> Cancel
                                    </Link>
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
                title: '',
                features: [],
                description: '',
                recommended: false,
                sort: 0,
                website_limit: '',
                unlimited_websites: false,
                lead_limit: '',
                unlimited_leads: false,
                app_access: false
            },
            errors: {},
            loader: false
        };
    },

    methods: {
        async getItem() {
            this.loader = true;
            await axios.get(route('api.package.get.single', [this.$page.props.id]), {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                this.item = $response.data;

                this.form.title = this.item.title ? this.item.title : '';
                this.form.features = this.item.features ? JSON.parse(this.item.features) : [];
                this.form.description = this.item.description ? this.item.description : '';
                this.form.recommended = this.item.recommended == 1 ? true : false;
                this.form.sort = this.item.sort ? this.item.sort : '';
                this.form.website_limit = this.item.website_limit ? this.item.website_limit : '';
                this.form.lead_limit = this.item.lead_limit ? this.item.lead_limit : '';
                this.form.app_access = this.item.app_access == 1 ? true : false;
                if(!this.item.website_limit) {
                    this.form.unlimited_websites = true;
                }

                if(!this.item.lead_limit) {
                    this.form.unlimited_leads = true;
                }

                this.loader = false;
            }).catch((error) => {
                this.loader = false;
                toast.error(error.response.data.message);
            });
        },

        async updateItem() {
            this.errors = {};
            this.loader = true;

            await axios.post(route('api.package.update', [this.$page.props.id]), this.form, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                }
            }).then((response) => {
                let $response = response.data;
                this.$cookies.set('lxf-success-msg', $response.message, 10);
                this.$inertia.visit(route('app.admin.package.index'));
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