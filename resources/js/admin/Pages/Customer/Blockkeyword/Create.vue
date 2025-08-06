<template>

    <Head title="Block Keywords" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template #title>Block Keywords</template>
                <li class="breadcrumb-item text-muted"><Link :href="route('app.customer.block-keyword.index')">Block Keywords</Link></li>
                <li class="breadcrumb-item active" aria-current="page">Create</li>
            </Breadcrumb>

            <div class="card">
                <div class="card-body p-4">
                    <form @submit.prevent="submit">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Select Website <span class="text-danger">*</span></label>
                                    <select class="form-control" v-model="form.website_id">
                                        <option value="">-- Select Website --</option>
                                        <option v-for="w in websites" :key="w.id" :value="w.id">
                                            {{ w.name }}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">Select Form <span class="text-danger">*</span></label>
                                    <select class="form-control" v-model="form.form_id">
                                        <option value="">-- Select Form --</option>
                                        <option v-for="f in forms" :key="f.id" :value="f.id">
                                            {{ f.form_name }}
                                        </option>
                                    </select>
                                </div>
                                <label class="form-label">Selected Keywords</label>
                                <div class="form-control">
                                    <template v-if="selectedKeywordObjects.length > 0">
                                        <span v-for="keyword in selectedKeywordObjects" :key="keyword.id"
                                            class="fs-2 fw-bold text-capitalize d-inline-block mb-1 rounded-1 me-1 py-1 px-2 bg-light-danger text-danger cursor-pointer"
                                            @click="toggleKeyword(keyword.id)">
                                            {{ keyword.keyword }} &times;
                                        </span>
                                    </template>
                                    <template v-else>
                                        <span class="text-muted">No keywords selected</span>
                                    </template>
                                    <!-- <div v-for="keyword in selectedKeywordObjects" :key="keyword.id" class="mb-1">
                                        <span class="badge bg-primary text-white me-2 cursor-pointer"
                                            @click="toggleKeyword(keyword.id)">
                                            {{ keyword.keyword }} x
                                        </span>
                                    </div> -->
                                </div>
                            </div>
                            <!-- <div class="col-12 col-md-6">
                            </div>
                            <div class="col-md-6">
                            </div> -->
                            <div class="col-md-6">
                                <nav>
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                        <button class="nav-link active" id="nav-suggested-tab" data-bs-toggle="tab"
                                            data-bs-target="#nav-suggested" type="button" role="tab"
                                            aria-controls="nav-suggested" aria-selected="true">Suggested
                                            Keywords</button>
                                        <button class="nav-link" id="your-keywords-tab" data-bs-toggle="tab"
                                            data-bs-target="#your-keywords" type="button" role="tab"
                                            aria-controls="your-keywords" aria-selected="false">Your Keywords</button>
                                    </div>
                                </nav>
                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="nav-suggested" role="tabpanel"
                                        aria-labelledby="nav-suggested-tab">
                                        <div class="mt-4">
                                            <!-- <label class="form-label">Suggested Keywords</label> -->
                                            <div class="form-control">
                                                <template v-if="filteredSuggestedKeywords.length > 0">
                                                    <span v-for="keyword in filteredSuggestedKeywords" :key="keyword.id"
                                                        class="fs-2 fw-bold me-1 mb-1 d-inline-block text-capitalize rounded-1 py-1 px-2 bg-light-info text-info cursor-pointer"
                                                        @click="addSuggestedKeyword(keyword.id)">
                                                        {{ keyword.keyword }} +</span>
                                                </template>
                                                <template v-else>
                                                    <span class="text-muted">No suggested keywords</span>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="your-keywords" role="tabpanel"
                                        aria-labelledby="your-keywords-tab">
                                        <div class="mt-4">
                                            <!-- <label class="form-label">Available Keywords</label> -->
                                            <div class="form-control">
                                                <template v-if="availableKeywords.length > 0">
                                                    <span v-for="keyword in availableKeywords" :key="keyword.id"
                                                        class="fs-2 fw-bold me-1 mb-1 d-inline-block text-capitalize rounded-1 py-1 px-2 bg-light-info text-info cursor-pointer me-1"
                                                        @click="toggleKeyword(keyword.id)">
                                                        {{ keyword.keyword }} +
                                                    </span>
                                                </template>
                                                <template v-else>
                                                    <span class="text-muted">No keywords available</span>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="position-relative addKeyword mt-4">
                                    <input v-model="newKeyword" class="form-control" placeholder="Add new keyword" />
                                    <button @click="addKeyword" type="button" class="btn btn-primary btn-sm"><i class="ti ti-plus"></i> Add New</button>
                                </div>
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
            newKeyword: '',
            selectedKeywordIds: [],
            suggestedKeywords: [],
            websites: [],
            allForms: [],
            allKeywords: [],
            errors: {},
        }
    },

    computed: {
        forms() {
            return this.allForms.filter(f => f.website_id === this.form.website_id);
        },
        keywords() {
            return [...this.allKeywords, ...this.suggestedKeywords];
        },
        availableKeywords() {
            return this.keywords.filter(k => !this.selectedKeywordIds.includes(k.id));
        },
        selectedKeywordObjects() {
            return this.keywords.filter(k => this.selectedKeywordIds.includes(k.id));
        },
        filteredSuggestedKeywords() {
            return this.suggestedKeywords.filter(k => !this.selectedKeywordIds.includes(k.id));
        }

    },

    mounted() {

        this.loader = true;
        this.form.user_id = this.user.id;

        axios.get(route('api.block-keyword.init-data'), {
            headers: { Authorization: `Bearer ${this.token}` }
        })
            .then(res => {
                this.websites = res.data.websites || [];
                this.allForms = res.data.forms || [];
                this.allKeywords = res.data.keywords || [];
                this.suggestedKeywords = res.data.suggested_keywords || [];

                this.form.website_id = res.data.default_website_id || '';
                this.form.form_id = res.data.default_form_id || '';
            })
            .catch(error => {
                toast.error("Failed to load initial data");
                console.error(error);
            })
            .finally(() => {
                this.loader = false;
                this.$refs.app_layout.loadScript();
            });
    },

    methods: {
        toggleKeyword(id) {
            const index = this.selectedKeywordIds.indexOf(id);
            if (index === -1) {
                this.selectedKeywordIds.push(id);
            } else {
                this.selectedKeywordIds.splice(index, 1);
            }
        },

        async submit() {
            this.errors = {};
            this.form.keywords = this.selectedKeywordIds;

            if (!this.form.user_id || !this.form.website_id || !this.form.form_id || this.form.keywords.length === 0) {
                toast.error("All fields are required.");
                return;
            }

            this.loader = true;
            try {
                await axios.post(route('api.block-keyword.store'), {
                    ...this.form,
                    keywords: this.selectedKeywordIds
                }, {
                    headers: { Authorization: `Bearer ${this.token}` }
                });

                toast.success("Blocked Successfully!");
                this.$inertia.visit(route('app.customer.block-keyword.index'));
            } catch (error) {
                if (error.response?.status === 422) {
                    this.errors = error.response.data.errors;
                    if (error.response.data.message) {
                        toast.error(error.response.data.message);
                    }
                } else {
                    toast.error(error.response?.data?.message || "Something went wrong.");
                }
            } finally {
                this.loader = false;
            }
        },

        addSuggestedKeyword(id) {
            if (!this.selectedKeywordIds.includes(id)) {
                this.selectedKeywordIds.push(id);
            }
        },

        addKeyword() {
            if (!this.newKeyword.trim()) {
                toast.error("Keyword is required");
                return;
            }

            axios.post(route('api.keyword.store'), {
                keyword: this.newKeyword,
                website_id: this.form.website_id,
                form_id: this.form.form_id,
            }, {
                headers: {
                    Authorization: `Bearer ${this.token}`
                }
            })
                .then(() => {
                    this.newKeyword = '';
                    this.loadKeywords();
                    toast.success('Keyword added successfully');
                })
                .catch((error) => {
                    toast.error(
                        error.response?.data?.errors?.keyword?.[0] || 'Failed to add keyword'
                    );
                });
        },

        loadKeywords() {
            axios.get(route('api.keyword.index'), {
                params: {
                    website_id: this.form.website_id,
                    form_id: this.form.form_id,
                },
                headers: { Authorization: `Bearer ${this.token}` }
            })
                .then(res => {
                    this.allKeywords = res.data.data;
                })
                .catch(error => {
                    toast.error("Failed to load keywords");
                    console.error(error);
                });
        }
    }
}
</script>
