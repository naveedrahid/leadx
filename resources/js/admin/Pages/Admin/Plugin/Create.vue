<template>
    <Head title="Add Plugin" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template v-slot:title>Add Plugin</template>
                <li class="breadcrumb-item"><Link :href="route('app.admin.plugin.index')" class="text-dark">Plugins</Link></li>
                <li class="breadcrumb-item text-muted" aria-current="page">Add New</li>
            </Breadcrumb>
            <div class="card">
                <div class="card-body p-4">
                    <form @submit.prevent="addItem()">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="plugin_name" class="form-label">Plugin Name <span class="text-danger">*</span></label>
                                            <input type="text" id="plugin_name" class="form-control rounded" v-model="form.plugin_name" placeholder="">
                                            <div class="text-danger" v-if="errors.plugin_name">
                                                <small>{{ errors.plugin_name }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="plugin_url" class="form-label">Plugin Url</label>
                                            <input type="text" id="plugin_url" class="form-control rounded" v-model="form.plugin_url" placeholder="">
                                            <div class="text-danger" v-if="errors.plugin_url">
                                                <small>{{ errors.plugin_url }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="version" class="form-label">Version <span class="text-danger">*</span></label>
                                            <input type="text" id="version" class="form-control rounded" v-model="form.version" placeholder="">
                                            <div class="text-danger" v-if="errors.version">
                                                <small>{{ errors.version }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="documentation" class="form-label">Documentation</label>
                                            <input type="text" id="documentation" class="form-control rounded" v-model="form.documentation" placeholder="">
                                            <div class="text-danger" v-if="errors.documentation">
                                                <small>{{ errors.documentation }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group mb-3">
                                            <label for="plugin_file" class="form-label">Plugin File <span class="text-danger">*</span></label>
                                            <input type="file" id="plugin_file" ref="plugin_file" class="form-control" @change="handlePliuginFile">
                                            <div class="text-danger fs-2" v-if="errors.plugin_file">
                                                {{ errors.plugin_file }}
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
                                    <Link :href="route('app.admin.plugin.index')" class="btn btn-light-danger text-danger">
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
                plugin_name: '',
                plugin_url: '',
                version: '',
                documentation: ''
            },
            plugin_file: '',
            errors: {},
            loader: false
        };
    },

    methods: {
        handlePliuginFile(e) {
            var files = e.target.files || e.dataTransfer.files;
            if (!files.length) return;
    
            this.plugin_file = files[0];
        },

        async addItem() {
            this.errors = {};
            this.loader = true;

            var fd = new FormData();
            fd.append('plugin_file', this.plugin_file);
            _.forEach(this.form, function(value, key) {
                fd.append(key, value);
            });

            await axios.post(route('api.plugin.create'), fd, {
                headers: {
                    "Content-Type": "multipart/form-data",
                    Authorization: "Bearer " + this.token
                }
            }).then((response) => {
                let $response = response.data;
                this.$cookies.set('ay-success-msg', $response.message, 10);
                this.$inertia.visit(route('app.admin.plugin.index'));
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