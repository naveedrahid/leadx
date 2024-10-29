<template>
    <Head title="Create Package" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template v-slot:title>Create Package</template>
                <li class="breadcrumb-item"><Link :href="route('app.admin.package.index')" class="text-dark">Packages</Link></li>
                <li class="breadcrumb-item text-muted" aria-current="page">Add New</li>
            </Breadcrumb>
            <div class="card">
                <div class="card-body p-4">
                    <form @submit.prevent="addItem()">
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
                                                <input type="checkbox" class="form-check-input primary check-outline outline-primary" id="is_private" v-model="form.is_private">
                                                <label class="form-check-label" for="is_private">Make this plan private. (Not Visible to Customers)</label>
                                            </div>
                                            <div class="text-danger" v-if="errors.is_private">
                                                {{ errors.recommended }}
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
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <div class="form-check form-check-inline">
                                                <input type="checkbox" class="form-check-input primary check-outline outline-primary" id="free_package" v-model="form.free_package" @change.prevent="setPackageFree()">
                                                <label class="form-check-label" for="free_package">Set Package to Free</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="regular_price">Regular Price <span class="text-danger" v-if="!form.free_package">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">{{ $page.props.currency_symbol }}</span>
                                                <input type="number" min="0" v-model="form.regular_price" id="regular_price" class="form-control" placeholder="e.g. 100" :disabled="form.free_package" :readonly="form.free_package">
                                                <span class="input-group-text">{{ $page.props.currency_code }}</span>
                                            </div>
                                            <div class="text-danger" v-if="errors.regular_price">
                                                {{ errors.regular_price }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="fv-row mb-5">
                                            <label class="form-label mb-1" for="sale_price">Sale Price</label>
                                            <div class="input-group">
                                                <span class="input-group-text">{{ $page.props.currency_symbol }}</span>
                                                <input type="number" min="0" v-model="form.sale_price" id="sale_price" class="form-control" placeholder="e.g. 80" :disabled="form.free_package" :readonly="form.free_package">
                                                <span class="input-group-text">{{ $page.props.currency_code }}</span>
                                            </div>
                                            <div class="text-danger" v-if="errors.sale_price">
                                                {{ errors.sale_price }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label mb-1" for="duration">Duration <span class="text-danger" v-if="!form.duration_lifetime">*</span></label>
                                            <input type="number" min="0" v-model="form.duration" id="duration" class="form-control" placeholder="e.g. 3" :disabled="form.duration_lifetime" :readonly="form.duration_lifetime">
                                            <div class="text-danger" v-if="errors.duration">
                                                {{ errors.duration }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label mb-1" for="duration_type">Duration Type <span class="text-danger" v-if="!form.duration_lifetime">*</span></label>
                                            <select v-model="form.duration_type" id="duration_type" class="form-select" :disabled="form.duration_lifetime">
                                                <option value="day">Day</option>
                                                <option value="week">Week</option>
                                                <option value="month">Month</option>
                                                <option value="year">Year</option>
                                            </select>
                                            <div class="text-danger" v-if="errors.duration_type">
                                                {{ errors.duration_type }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <div class="form-check form-check-inline">
                                                <input type="checkbox" class="form-check-input primary check-outline outline-primary" id="duration_lifetime" v-model="form.duration_lifetime" @change.prevent="durationLifeTimeToggle()">
                                                <label class="form-check-label" for="duration_lifetime">Package Duration Set to Lifetime</label>
                                            </div>
                                            <div class="text-danger" v-if="errors.duration_lifetime">
                                                <small>{{ errors.duration_lifetime }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <div class="form-check form-check-inline">
                                                <input type="checkbox" class="form-check-input primary check-outline outline-primary" id="free_trial" @change.prevent="form.free_trial == true ? form.trial_period_days = '' : ''" v-model="form.free_trial" :disabled="form.free_package || form.duration_lifetime">
                                                <label class="form-check-label" for="free_trial">Free Trial</label>
                                            </div>
                                            <div class="text-danger" v-if="errors.free_trial">
                                                <small>{{ errors.free_trial }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <template v-if="form.free_trial">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="trial_period_days" class="form-label">Trial Period Days <span class="text-danger" v-if="!form.free_package && !form.duration_lifetime">*</span></label>
                                                <input type="number" min="0" class="form-control" id="trial_period_days" v-model="form.trial_period_days" placeholder="e.g. 7" :disabled="form.free_package || form.duration_lifetime" :readonly="form.free_package || form.duration_lifetime">
                                                <div class="text-danger" v-if="errors.trial_period_days">
                                                    <small>{{ errors.trial_period_days }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
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
                                        <i class="ti ti-device-floppy"></i> Save
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
            form: {
                title: '',
                duration: '',
                duration_type: 'month',
                duration_lifetime: false,
                free_trial: false,
                trial_period_days: '',
                free_package: false,
                regular_price: '',
                sale_price: '',
                features: [],
                description: '',
                recommended: false,
                sort: 0,
                website_limit: '',
                unlimited_websites: false,
                lead_limit: '',
                unlimited_leads: false,
                app_access: false,
                is_private: false
            },
            errors: {},
            loader: false
        };
    },

    methods: {
        setPackageFree() {
            if(this.form.free_package) {
                this.form.regular_price = 0;
                this.form.sale_price = '';
                this.form.free_trial = false;
                this.form.trial_period_days = '';
            } else {
                this.form.regular_price = '';
            }
        },

        durationLifeTimeToggle() {
            if(this.form.duration_lifetime) {
                this.form.duration = '';
                this.form.duration_type = '';
                this.form.free_trial = false;
                this.form.trial_period_days = '';
            }
        },

        async addItem() {
            this.errors = {};
            this.loader = true;

            await axios.post(route('api.package.create'), this.form, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token
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

    mounted() {
        this.$refs.app_layout.loadScript();
    }
}
</script>