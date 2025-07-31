<template>

    <Head title="Block Keywords" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template #title>Block Keywords</template>
                <li class="breadcrumb-item text-muted">Customer</li>
                <li class="breadcrumb-item active" aria-current="page">Create Block</li>
            </Breadcrumb>

            <div class="card">
                <div class="card-body p-4">
                    <form @submit.prevent="submit">
                        <!-- Website Selection -->
                        <div class="form-group mb-3">
                            <label class="form-label">Select Website <span class="text-danger">*</span></label>
                            <select class="form-control" v-model="form.website_id" @change="fetchForms">
                                <option value="">-- Select Website --</option>
                                <option v-for="w in websites" :key="w.id" :value="w.id">{{ w.name }}</option>
                            </select>
                        </div>


                        <!-- Form Selection -->
                        <div class="form-group mb-3">
                            <label class="form-label">Select Form <span class="text-danger">*</span></label>
                            <select class="form-control" v-model="form.form_id" @change="fetchKeywords">
                                <option value="">-- Select Form --</option>
                                <option v-for="f in forms" :key="f.id" :value="f.id">{{ f.form_name }}</option>
                            </select>
                        </div>

                        <!-- Keyword Multi-select -->
                        <div class="form-group mb-3">
                            <label class="form-label">Keywords to Block <span class="text-danger">*</span></label>
                            <select class="form-control" multiple v-model="form.keywords">
                                <option v-for="k in keywords" :key="k.id" :value="k.id">{{ k.keyword }}</option>
                            </select>
                        </div>

                        <div class="d-flex align-items-center gap-2 mt-4">
                            <button type="submit" class="btn btn-light-primary text-primary">
                                <i class="ti ti-device-floppy"></i> Save
                            </button>
                            <a :href="route('app.customer.block-keyword.index')"
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
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/admin/Layouts/AppLayout.vue'
import Breadcrumb from '@/admin/Components/Breadcrumb.vue'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'

export default {
    components: { Head, Link, AppLayout, Breadcrumb },

    data() {
        return {
            user: this.$cookies.get('lxf-user'),
            token: this.$cookies.get('lxf-token'),
            loader: false,
            form: {
                user_id: '',
                website_id: '',
                form_id: '',
                keywords: [],
            },
            users: [],
            websites: [],
            forms: [],
            keywords: [],
            errors: {},
        }
    },

    mounted() {
        this.form.user_id = this.user.id
        this.fetchWebsites()
        this.$refs.app_layout.loadScript()
    },

    methods: {
        // async fetchUsers() {
        //   this.loader = true
        //   try {
        //     const res = await axios.get(route('api.block-keyword.users'), {
        //       headers: { Authorization: `Bearer ${this.token}` }
        //     })
        //     this.users = res.data.users
        //   } catch (e) {
        //     toast.error("Failed to load users.")
        //   } finally {
        //     this.loader = false
        //   }
        // },

        async fetchWebsites() {
            this.reset(['website_id', 'form_id', 'keywords'])
            if (!this.form.user_id) return

            this.loader = true
            try {
                const res = await axios.get(route('api.block-keyword.websites.by-user', this.form.user_id), {
                    headers: { Authorization: `Bearer ${this.token}` }
                })
                this.websites = res.data.websites
            } catch (e) {
                toast.error("Failed to load websites.")
            } finally {
                this.loader = false
            }
        },

        async fetchForms() {
            this.reset(['form_id', 'keywords'])
            if (!this.form.website_id) return

            this.loader = true
            try {
                const res = await axios.get(route('api.block-keyword.forms.by-website', this.form.website_id), {
                    headers: { Authorization: `Bearer ${this.token}` }
                })
                this.forms = res.data.forms
            } catch (e) {
                toast.error("Failed to load forms.")
            } finally {
                this.loader = false
            }
        },

        async fetchKeywords() {
            this.reset(['keywords'])
            if (!this.form.form_id) return

            this.loader = true
            try {
                const res = await axios.get(route('api.block-keyword.keywords.by-form', this.form.form_id), {
                    headers: { Authorization: `Bearer ${this.token}` }
                })
                this.keywords = res.data.keywords
            } catch (e) {
                toast.error("Failed to load keywords.")
            } finally {
                this.loader = false
            }
        },

        reset(fields) {
            if (fields.includes('website_id')) {
                this.websites = []
                this.form.website_id = ''
            }
            if (fields.includes('form_id')) {
                this.forms = []
                this.form.form_id = ''
            }
            if (fields.includes('keywords')) {
                this.keywords = []
                this.form.keywords = []
            }
        },

        async submit() {
            this.errors = {}

            if (!this.form.user_id || !this.form.website_id || !this.form.form_id || this.form.keywords.length === 0) {
                toast.error("All fields are required.")
                return
            }

            this.loader = true
            try {
                await axios.post(route('api.block-keyword.store'), this.form, {
                    headers: { Authorization: `Bearer ${this.token}` }
                })

                toast.success("Blocked Successfully!")
                this.$inertia.visit(route('app.customer.block-keyword.index'))
            } catch (error) {
                if (error.response?.status === 422) {
                    this.errors = error.response.data.errors
                } else {
                    toast.error(error.response?.data?.message || "Something went wrong.")
                }
            } finally {
                this.loader = false
            }
        }
    }
}
</script>
