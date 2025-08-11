<template>

    <Head title="Edit Block Keywords" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template #title>Block Keywords</template>
                <li class="breadcrumb-item text-muted">
                    <Link :href="route('app.customer.block-keyword.index')">Block Keywords</Link>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </Breadcrumb>

            <div class="card">
                <div class="card-body p-4">
                    <form @submit.prevent="submit">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Website</label>
                                    <select class="form-control" v-model="form.website_id" disabled>
                                        <option :value="block.website?.id || block.website_id || null">
                                            {{ block.website?.website_url || block.website?.website_name ||
                                                block.website_url ||
                                                block.website_name || "No website found" }}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">Form</label>
                                    <select class="form-control" v-model="form.form_id" disabled>
                                        <option :value="form.form_id">
                                            {{ block.form?.form_name || block.form_name || "No form found" }}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Blocked Keywords</label>
                                    <div class="form-control">
                                        <template v-if="form.keywords.length > 0">
                                            <span v-for="(id, index) in form.keywords" :key="id"
                                                class="fs-2 fw-bold me-1 mb-1 d-inline-block text-capitalize rounded-1 py-1 px-2 bg-light-danger text-danger cursor-pointer me-1"
                                                @click="removeKeyword(index)">
                                                {{ getKeywordText(id) }} &times;
                                            </span>
                                        </template>
                                        <template v-else>
                                            <span class="text-muted">No selected keywords</span>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <nav>
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                        <button class="nav-link active" id="nav-suggested-tab" data-bs-toggle="tab"
                                            data-bs-target="#nav-suggested" type="button" role="tab"
                                            aria-controls="nav-suggested" aria-selected="true">
                                            Suggested Keywords
                                        </button>
                                        <button class="nav-link" id="your-keywords-tab" data-bs-toggle="tab"
                                            data-bs-target="#your-keywords" type="button" role="tab"
                                            aria-controls="your-keywords" aria-selected="false">
                                            Your Keywords
                                        </button>
                                    </div>
                                </nav>
                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="nav-suggested" role="tabpanel"
                                        aria-labelledby="nav-suggested-tab">
                                        <div class="mt-4">
                                            <label class="form-label">Suggested Keywords</label>
                                            <div class="form-control">
                                                <template v-if="filteredSuggestedKeywords.length > 0">
                                                    <span v-for="keyword in filteredSuggestedKeywords" :key="keyword.id"
                                                        class="fs-2 fw-bold me-1 mb-1 d-inline-block text-capitalize rounded-1 py-1 px-2 bg-light-info text-info cursor-pointer me-1"
                                                        @click="
                                                            addSuggestedKeyword(keyword.id)">
                                                        {{ keyword.keyword }} +
                                                    </span>
                                                </template>
                                                <template v-else>
                                                    <span class="text-muted">No suggested
                                                        keywords</span>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="your-keywords" role="tabpanel"
                                        aria-labelledby="your-keywords-tab">
                                        <div class="mt-4">
                                            <label class="form-label">Available Keywords</label>
                                            <div class="form-control">
                                                <template v-if="filteredAvailableKeywords.length > 0">
                                                    <span v-for="keyword in filteredAvailableKeywords" :key="keyword.id"
                                                        class="fs-2 fw-bold me-1 mb-1 d-inline-block text-capitalize rounded-1 py-1 px-2 bg-light-info text-info cursor-pointer me-1"
                                                        style="cursor: pointer" @click="
                                                            addKeyword(keyword.id)">
                                                        {{ keyword.keyword }}
                                                        +</span>
                                                </template>
                                                <template v-else>
                                                    <span class="text-muted">No keywords
                                                        available</span>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="position-relative addKeyword mt-4">
                                    <input v-model="newKeyword" class="form-control" placeholder="Add new keyword" />
                                    <button @click="addNewKeyword" type="button" class="btn btn-primary btn-sm">
                                        <i class="ti ti-plus"></i> Add New
                                    </button>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-4">
                                <button type="submit" class="btn btn-light-primary text-primary">
                                    <i class="ti ti-refresh"></i> Update
                                </button>
                                <Link :href="route(
                                    'app.customer.block-keyword.index'
                                )
                                    " class="btn btn-light-danger text-danger">
                                <i class="ti ti-x"></i> Cancel
                                </Link>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import { Head, Link } from "@inertiajs/vue3";
import AppLayout from "@/admin/Layouts/AppLayout.vue";
import Breadcrumb from "@/admin/Components/Breadcrumb.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

export default {
    props: {
        block: {
            type: Object,
            required: true,
        },
        keywords: {
            type: Array,
            required: true,
        },
        allKeywords: {
            type: Array,
            required: true,
        },
        suggestedKeywords: Array,
    },
    components: {
        Head,
        Link,
        AppLayout,
        Breadcrumb,
    },
    data() {
        return {
            user: this.$cookies.get("lxf-user"),
            token: this.$cookies.get("lxf-token"),
            form: {
                id: this.block.id,
                website_id: this.block.website?.id || this.block.website_id || null,
                form_id: this.block.form?.id || this.block.form_id || null,
                keywords: this.block.keywords.map((k) => k.id),
                user_id: this.block?.user_id ?? null,
                is_blocked: this.block?.is_blocked ?? 0,
            },
            newKeyword: "",
            localAllKeywords: [...this.allKeywords],
            errors: {},
            loader: false,
        };
    },
    computed: {
        selected_keyword_ids() {
            return this.form.keywords;
        },
        filteredAvailableKeywords() {
            const selectedIds = this.form.keywords;

            const all = [...this.keywords, ...this.localAllKeywords];

            const unique = [];
            const map = new Map();
            for (const item of all) {
                if (!map.has(item.id)) {
                    map.set(item.id, true);
                    unique.push(item);
                }
            }

            return unique.filter((k) => !selectedIds.includes(k.id));
        },
        filteredSuggestedKeywords() {
            return this.suggestedKeywords.filter(
                (k) => !this.form.keywords.includes(k.id)
            );
        },
        keywordMap() {
            const all = [
                ...this.keywords,
                ...this.localAllKeywords,
                ...this.suggestedKeywords,
            ];
            const map = {};
            all.forEach(k => {
                map[k.id] = k.keyword;
            });
            return map;
        },
    },
    mounted() {
        this.$refs.app_layout.loadScript();
        this.form = {
            id: this.block.id,
            website_id: this.block.website?.id || null,
            form_id: this.block.form?.id || null,
            keywords: this.block.keywords.map((k) => k.id),
            user_id: this.block?.user_id ?? null,
            is_blocked: this.block?.is_blocked ?? 0,
        };
        this.loadKeywords();
    },
    methods: {
        addSuggestedKeyword(id) {
            if (!this.form.keywords.includes(id)) {
                this.form.keywords.push(id);
            }
        },
        getKeywordText(id) {
            const all = [
                ...this.keywords,
                ...this.localAllKeywords,
                ...this.suggestedKeywords,
            ];
            const found = all.find(k => k.id === id);
            return found?.keyword || 'Unknown';
        },
        addKeyword(id) {
            if (!this.form.keywords.includes(id)) {
                this.form.keywords.push(id);
            }
        },
        removeKeyword(index) {
            this.form.keywords.splice(index, 1);
        },
        async submit() {
            this.errors = {};

            if (
                !this.form.website_id ||
                !this.form.form_id ||
                this.form.keywords.length === 0
            ) {
                toast.error("All fields are required.");
                return;
            }

            this.loader = true;
            try {
                await axios.put(
                    route("api.block-keyword.update", [this.form.id]),
                    this.form,
                    {
                        headers: { Authorization: `Bearer ${this.token}` },
                    }
                );

                toast.success("Updated Successfully!");
                this.$inertia.visit(route("app.customer.block-keyword.index"));
            } catch (error) {
                if (error.response?.status === 422) {
                    this.errors = error.response.data.errors;
                } else {
                    toast.error(
                        error.response?.data?.message || "Something went wrong."
                    );
                }
            } finally {
                this.loader = false;
            }
        },

        addNewKeyword() {
            if (!this.newKeyword.trim()) {
                toast.error("Keyword is required");
                return;
            }

            axios
                .post(
                    route("api.keyword.store"),
                    {
                        keyword: this.newKeyword,
                        website_id: this.form.website_id,
                        form_id: this.form.form_id,
                    },
                    {
                        headers: {
                            Authorization: `Bearer ${this.token}`,
                        },
                    }
                )
                .then((res) => {
                    console.log('test', res);

                    this.newKeyword = "";
                    this.loadKeywords();
                    toast.success("Keyword added successfully");
                })
                .catch((error) => {
                    toast.error(
                        error.response?.data?.errors?.keyword?.[0] ||
                        "Failed to add keyword"
                    );
                });
        },

        loadKeywords() {
            axios
                .get(route("api.keyword.index"), {
                    params: {
                        website_id: this.form.website_id,
                        form_id: this.form.form_id,
                    },
                    headers: { Authorization: `Bearer ${this.token}` },
                })
                .then((res) => {
                    this.localAllKeywords = res.data.data;
                })
                .catch((error) => {
                    toast.error("Failed to load keywords");
                    console.error(error);
                });
        },
    },
};
</script>
