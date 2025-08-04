<template>

    <Head title="Edit Block Keywords" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template #title>Edit Block</template>
                <li class="breadcrumb-item text-muted">Customer</li>
                <li class="breadcrumb-item active" aria-current="page">
                    Edit Block
                </li>
            </Breadcrumb>

            <div class="card">
                <div class="card-body p-4">
                    <form @submit.prevent="submit">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Website</label>
                                    <select class="form-control" v-model="form.website_id" disabled>
                                        <option :value="block.website?.id">
                                            {{
                                                block.website?.website_name ??
                                                "No website found"
                                            }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Form</label>
                                    <select class="form-control" v-model="form.form_id" disabled>
                                        <option :value="block.form?.id">
                                            {{
                                                block.form?.form_name ??
                                                "No form found"
                                            }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label class="form-label">Blocked Keywords</label>
                                    <div class="form-control">
                                        <span v-for="(id, index) in form.keywords" :key="id"
                                            class="badge bg-danger text-white me-2" style="cursor:pointer;"
                                            @click="removeKeyword(index)">
                                            {{ getKeywordText(id) }} &times;
                                        </span>
                                    </div>
                                </div>

                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="form-label">Available Keywords</label>
                                    <div class="form-control">
                                        <template v-if="filteredAvailableKeywords.length > 0">
                                            <span v-for="keyword in filteredAvailableKeywords" :key="keyword.id"
                                                class="badge bg-info text-white me-2" style="cursor: pointer"
                                                @click="addKeyword(keyword.id)">
                                                {{ keyword.keyword }} +
                                            </span>
                                        </template>
                                        <template v-else>
                                            <span class="text-muted">No keywords available</span>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2 mt-4">
                                <button type="submit" class="btn btn-light-primary text-primary">
                                    <i class="ti ti-refresh"></i> Update
                                </button>
                                <Link :href="route('app.customer.block-keyword.index')
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
                id: null,
                website_id: null,
                form_id: null,
                keywords: [],
                user_id: null,
                is_blocked: 0,
            },
            errors: {},
            loader: false,
        };
    },
    computed: {
        selected_keyword_ids() {
            return this.form.keywords;
        },
        mergedKeywords() {
            const selectedIds = this.selected_keyword_ids;
            const extraKeywords = this.allKeywords.filter(
                (k) => !selectedIds.includes(k.id)
            );
            return [...this.block.keywords, ...extraKeywords];
        },
        selected_keyword_ids() {
            return this.form.keywords;
        },
        filteredAvailableKeywords() {
            return this.allKeywords.filter(k => !this.form.keywords.includes(k.id));
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
    },
    methods: {
        getKeywordText(id) {
            const all = [...this.keywords, ...this.allKeywords];
            const found = all.find(k => Number(k.id) === Number(id));
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
    },
};
</script>
