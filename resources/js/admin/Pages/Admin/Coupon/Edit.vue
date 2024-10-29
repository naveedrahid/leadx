<template>
    <Head title="Edit Coupon" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template v-slot:title>Edit Coupon</template>
                <li class="breadcrumb-item"><Link :href="route('app.admin.coupon.index')" class="text-dark">Coupons</Link></li>
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
                                            <input type="text" id="title" class="form-control rounded" v-model="form.title" placeholder="e.g. 25% off">
                                            <div class="text-danger" v-if="errors.title">
                                                <small>{{ errors.title }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea id="description" class="form-control rounded" rows="5" v-model="form.description" placeholder="e.g. Write something about coupon..."></textarea>
                                            <div class="text-danger" v-if="errors.description">
                                                <small>{{ errors.description }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>    
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="max_uses" class="form-label">Max Uses <span class="text-danger">*</span></label>
                                            <input type="number" min="0" class="form-control" id="max_uses" v-model="form.max_uses" placeholder="e.g. 10">
                                            <div class="text-danger" v-if="errors.max_uses">
                                                <small>{{ errors.max_uses }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="max_uses_user" class="form-label">Max Uses Per User <span class="text-danger">*</span></label>
                                            <input type="number" min="0" class="form-control" id="max_uses_user" v-model="form.max_uses_user" placeholder="e.g. 10">
                                            <div class="text-danger" v-if="errors.max_uses_user">
                                                <small>{{ errors.max_uses_user }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="expires_at" class="form-label">Expires at <span class="text-danger">*</span></label>
                                            <VueDatePicker v-model="form.expires_at" id="expires_at" class="form-control-datepicker" @update:model-value="expiryDateChange" :enable-time-picker="false" placeholder="MM/DD/YYYY"></VueDatePicker>
                                            <div class="text-danger" v-if="errors.expires_at">
                                                {{ errors.expires_at }}
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
                                    <Link :href="route('app.admin.coupon.index')" class="btn btn-light-danger text-danger">
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
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

export default {
    components: {
        Head,
        Link,
        AppLayout,
        Breadcrumb,
        VueDatePicker
    },

    data() {
        return {
            user: this.$cookies.get("lxf-user"),
            token: this.$cookies.get("lxf-token"),
            item: {},
            form: {
                title: '',
                description: '',
                max_uses: '',
                max_uses_user: '',
                expires_at: ''
            },
            expires_at: '',
            errors: {},
            loader: false
        };
    },

    methods: {
        expiryDateChange() {
            let date = this.form.expires_at;
            if(this.form.expires_at != null) {
                date = moment(this.form.expires_at).format('YYYY-MM-DD');
            }

            this.form.expires_at = date;
        },

        couponCode(inputText) {
            const upperCaseText = inputText.toUpperCase();
            const sanitizedText = upperCaseText.replace(/[^\w\s]/g, '');
            const finalText = sanitizedText.replace(/\s+/g, '_');            
            return finalText;
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

                this.form.title = this.item.title ? this.item.title : '';
                this.form.description = this.item.description ? this.item.description : '';
                this.form.max_uses = this.item.max_uses ? this.item.max_uses : '';
                this.form.max_uses_user = this.item.max_uses_user ? this.item.max_uses_user : '';
                this.form.expires_at = this.item.expires_at ? this.item.expires_at : '';

                this.loader = false;
            }).catch((error) => {
                this.loader = false;
                toast.error(error.response.data.message);
            });
        },

        async updateItem() {
            this.errors = {};
            this.loader = true;

            await axios.post(route('api.coupon.update', [this.$page.props.id]), this.form, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token
                }
            }).then((response) => {
                let $response = response.data;
                this.$cookies.set('ay-success-msg', $response.message, 10);
                this.$inertia.visit(route('app.admin.coupon.index'));    
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