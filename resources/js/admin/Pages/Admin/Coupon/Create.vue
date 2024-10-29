<template>
    <Head title="Create Coupon" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template v-slot:title>Create Coupon</template>
                <li class="breadcrumb-item"><Link :href="route('app.admin.coupon.index')" class="text-dark">Coupons</Link></li>
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
                                            <input type="text" id="title" class="form-control rounded" v-model="form.title" placeholder="e.g. 25% off">
                                            <div class="text-danger" v-if="errors.title">
                                                <small>{{ errors.title }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
                                            <input type="text" id="code" class="form-control rounded" v-model="form.code" placeholder="e.g. TLPOLZK4" @input="form.code = couponCode(form.code)">
                                            <div class="text-danger" v-if="errors.code">
                                                <small>{{ errors.code }}</small>
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
                                            <label for="type" class="form-label">Discount Type <span class="text-danger">*</span></label>
                                            <select class="form-select" id="type" v-model="form.type">
                                                <option value="">Select Discount Type</option>
                                                <option value="fixed">Fixed</option>
                                                <option value="percent">Percent</option>
                                            </select>
                                            <div class="text-danger" v-if="errors.type">
                                                <small>{{ errors.type }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3" v-if="form.type != ''">
                                            <label for="amount" class="form-label">Discount Amount <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-text" v-if="form.type == 'fixed'">
                                                    <span class="justify-content-center">{{ $page.props.currency_symbol }}</span>
                                                </div>
                                                <input type="number" min="0" class="form-control" id="amount" v-model="form.amount" placeholder="e.g. 25">
                                                <div class="input-group-text" v-if="form.type == 'percent'">
                                                    <span class="justify-content-center">%</span>
                                                </div>
                                            </div>
                                            <div class="text-danger" v-if="errors.amount">
                                                <small>{{ errors.amount }}</small>
                                            </div>
                                        </div>
                                    </div>
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
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="duration" class="form-label">Duration <span class="text-danger">*</span></label>
                                            <select class="form-select" id="duration" v-model="form.duration">
                                                <option value="">Select Duration</option>
                                                <option value="once">Once</option>
                                                <option value="repeating">Repeating</option>
                                            </select>
                                            <div class="text-danger" v-if="errors.duration">
                                                <small>{{ errors.duration }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3" v-if="form.duration == 'repeating'">
                                            <label for="duration_month" class="form-label">Duration Month <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" min="0" class="form-control" id="duration_month" v-model="form.duration_month" placeholder="e.g. 12">
                                                <div class="input-group-text">
                                                    <span class="justify-content-center">Month</span>
                                                </div>
                                            </div>
                                            <div class="text-danger" v-if="errors.duration_month">
                                                <small>{{ errors.duration_month }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="expires_at" class="form-label">Expires at <span class="text-danger">*</span></label>
                                            <VueDatePicker v-model="expires_at" id="expires_at" class="form-control-datepicker" @update:model-value="expiryDateChange" :enable-time-picker="false" placeholder="MM/DD/YYYY"></VueDatePicker>
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
                                        <i class="ti ti-device-floppy"></i> Save
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
import moment from 'moment';

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
            user: this.$cookies.get('lxf-user'),
            token: this.$cookies.get('lxf-token'),
            form: {
                title: '',
                code: '',
                description: '',
                type: 'fixed',
                amount: '',
                max_uses: 10,
                max_uses_user: 1,
                duration: 'once',
                duration_month: '',
                expires_at: ''
            },
            expires_at: '',
            errors: {},
            loader: false
        };
    },

    methods: {
        expiryDateChange() {
            let date = this.expires_at;
            if(date != null) {
                date = moment(date).format('YYYY-MM-DD');
            }

            this.form.expires_at = date;
        },

        couponCode(inputText) {
            const upperCaseText = inputText.toUpperCase();
            const sanitizedText = upperCaseText.replace(/[^\w\s]/g, '');
            const finalText = sanitizedText.replace(/\s+/g, '_');            
            return finalText;
        },

        async addItem() {
            this.errors = {};
            this.loader = true;

            await axios.post(route('api.coupon.create'), this.form, {
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

    mounted() {
        this.$refs.app_layout.loadScript();
    }
}
</script>