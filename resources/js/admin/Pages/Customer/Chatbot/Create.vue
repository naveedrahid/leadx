<template>
    <AppLayout ref="app_layout" :loader="loader">

        <Head title="Create Chatbot" />
        <div class="container-fluid">
            <Breadcrumb><template #title>Chatbot</template><span class="breadcrumb-item active">Create</span>
            </Breadcrumb>

            <!-- Stepper -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div v-for="(s, i) in steps" :key="s.key" class="text-center flex-fill">
                            <div class="rounded-circle border d-inline-flex align-items-center justify-content-center"
                                :class="stepClass(i)" style="width:40px;height:40px">{{ i + 1 }}</div>
                            <div class="small mt-1">{{ s.label }}</div>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height:6px">
                        <div class="progress-bar" :style="{ width: ((activeStep + 1) / steps.length * 100) + '%' }">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 col-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div v-show="isStep('website')">
                                <h5 class="mb-3">Website</h5>
                                <label class="form-label">Select Website <span class="text-danger">*</span></label>
                                <select class="form-select" v-model="form.website_id">
                                    <option :value="null" disabled>Choose website…</option>
                                    <option v-for="w in websites" :key="w.id" :value="w.id">{{ w.website_url }}
                                    </option>
                                </select>
                            </div>
                            <div v-show="isStep('configure')">
                                <h5 class="mb-3">Configure</h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Chatbot Title <span
                                                class="text-danger">*</span></label>
                                        <input class="form-control" v-model.trim="form.name" placeholder="Leadx" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Welcome Message</label>
                                        <input class="form-control" v-model.trim="form.welcome_message"
                                            placeholder="Hi, how can I help you?" />
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">Bubble Message</label>
                                        <input class="form-control" v-model.trim="form.bubble_message"
                                            placeholder="Hey there, How can I help you?" />
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">Chatbot Instructions <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="4" v-model="form.system_prompt"
                                            placeholder="Explain chatbot role"></textarea>
                                        <div class="small text-danger" v-if="!form.system_prompt?.trim()">The
                                            instructions field is required.</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label d-block">Do Not Go Beyond Instructions</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                v-model="form.do_not_go_beyond" id="dngb">
                                            <label class="form-check-label" for="dngb"></label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Language</label>
                                        <select class="form-select" v-model="form.language">
                                            <option v-for="opt in languageOptions" :key="opt.value" :value="opt.value">
                                                {{ opt.label }}</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Interaction type</label>
                                        <select class="form-select" v-model.number="form.interaction_type">
                                            <option :value="0">AI & Live Chat</option>
                                            <option :value="1">Only AI</option>
                                            <option :value="2">Only Live Chat</option>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Connect Message</label>
                                        <input class="form-control" v-model.trim="form.connect_message"
                                            placeholder="I've forwarded your request to a human agent. An agent will con…" />
                                    </div>
                                </div>
                                <!-- <div class="mt-3">
                                    <button class="btn btn-success" :disabled="savingBot" @click="createBot">
                                        <i class="ti ti-device-floppy"></i>
                                        {{ savingBot ? 'Saving…' : (chatbotId ? 'Update Bot' : 'Save Bot') }}
                                    </button>
                                </div> -->
                            </div>
                            <div v-show="isStep('customize')">
                                <h5 class="mb-3">Customize</h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Upload Logo</label>
                                        <input type="file" class="form-control" @change="onLogoPick" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Footer Link</label>
                                        <input class="form-control" v-model.trim="form.footer_link"
                                            placeholder="http://demo.magicproject.ai" />
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label d-block">Avatar</label>
                                        <div class="d-flex gap-2 flex-wrap">
                                            <button v-for="a in avatars" :key="a.key" type="button"
                                                class="btn btn-light border"
                                                :class="{ 'border-primary': form.avatar_key === a.key }"
                                                @click="form.avatar_key = a.key">
                                                <img :src="a.src" style="width:32px;height:32px" />
                                            </button>
                                            <button type="button" class="btn btn-light border"
                                                @click="form.avatar_key = 'custom'">+</button>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label d-block">Color</label>
                                        <div class="d-flex gap-3 flex-wrap">
                                            <button v-for="c in colorPalette" :key="c" type="button"
                                                class="btn rounded-circle" style="width:28px;height:28px"
                                                :style="{ background: c, border: c === form.color_accent ? '3px solid #000' : '1px solid #ddd' }"
                                                @click="form.color_accent = c"></button>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" v-model="form.show_logo"
                                                id="show_logo">
                                            <label class="form-check-label" for="show_logo">Show Logo</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" v-model="form.show_datetime"
                                                id="show_dt">
                                            <label class="form-check-label" for="show_dt">Show Date and Time</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                v-model="form.transparent_trigger" id="transparent_trigger">
                                            <label class="form-check-label" for="transparent_trigger">Transparent
                                                Trigger</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Trigger Avatar Size</label>
                                        <input type="range" class="form-range" min="40" max="80"
                                            v-model.number="form.trigger_avatar_size" />
                                        <div class="small text-muted">{{ form.trigger_avatar_size }}px</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label d-block">Position</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" value="left"
                                                    v-model="form.position" id="pos_left">
                                                <label class="form-check-label" for="pos_left">Left</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" value="right"
                                                    v-model="form.position" id="pos_right">
                                                <label class="form-check-label" for="pos_right">Right</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-show="isStep('train')">
                                <h5 class="mb-3">Chatbot Training</h5>

                                <ul class="nav nav-pills mb-3">
                                    <li class="nav-item">
                                        <button class="nav-link" :class="{ active: trainTab === 'website' }"
                                            @click="trainTab = 'website'">Website</button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" :class="{ active: trainTab === 'pdf' }"
                                            @click="trainTab = 'pdf'" disabled>PDF</button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" :class="{ active: trainTab === 'text' }"
                                            @click="trainTab = 'text'" disabled>Text</button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" :class="{ active: trainTab === 'qa' }"
                                            @click="trainTab = 'qa'">Q&A</button>
                                    </li>
                                </ul>

                                <div v-if="trainTab === 'website'">
                                    <label class="form-label">Add URL</label>
                                    <div class="input-group mb-2">
                                        <input class="form-control" v-model.trim="train.website_url"
                                            placeholder="https://example.com">
                                        <button class="btn btn-outline-secondary" type="button"
                                            @click="train.website_url = ''">
                                            <i class="ti ti-refresh"></i>
                                        </button>
                                    </div>
                                    <button v-if="activeStep < steps.length - 1" class="btn btn-primary"
                                        @click="nextStep" :disabled="!canProceed">
                                        Next
                                    </button>
                                </div>

                                <div v-else-if="trainTab === 'qa'">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="m-0">Q&A (Manual)</h6>
                                        <button type="button" class="btn btn-sm btn-outline-primary" @click="addQA"
                                            :disabled="!canAddQA">
                                            <i class="ti ti-plus"></i> Add More
                                        </button>
                                    </div>
                                    <div class="accordion" id="qaAccordion">
                                        <div class="accordion-item mb-3" v-for="(qa, idx) in qaForms"
                                            :key="'qa-acc-' + idx">
                                            <h2 class="accordion-header" :id="'qah-' + idx">
                                                <button class="accordion-button"
                                                    :class="{ collapsed: activeAcc !== idx }" type="button"
                                                    data-bs-toggle="collapse" :data-bs-target="'#qac-' + idx"
                                                    :aria-expanded="activeAcc === idx ? 'true' : 'false'"
                                                    @click="activeAcc = (activeAcc === idx ? null : idx)">
                                                    {{ qa.title?.trim() ? qa.title : ('Question ' + (idx + 1)) }}
                                                </button>
                                            </h2>
                                            <div :id="'qac-' + idx" class="accordion-collapse collapse"
                                                :class="{ show: activeAcc === idx }">
                                                <div class="accordion-body">
                                                    <div class="mb-2">
                                                        <label class="form-label">Question</label>
                                                        <input class="form-control" v-model="qa.title"
                                                            @input="clearFieldErr(idx, 'question')" />
                                                        <div class="text-danger" v-if="fieldErr(idx, 'question')">
                                                            <small>{{ fieldErr(idx, 'question') }}</small>
                                                        </div>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label class="form-label">Answer</label>
                                                        <Editor :id="'qa-editor-' + idx" v-model="qa.content"
                                                            :init="tinymceInit" />
                                                        <div class="text-danger" v-if="fieldErr(idx, 'answer')">
                                                            <small>{{
                                                                fieldErr(idx, 'answer') }}</small>
                                                        </div>
                                                    </div>
                                                    <div class="text-end">
                                                        <button class="btn btn-sm btn-outline-danger" v-if="idx !== 0"
                                                            @click="removeQA(idx)">Remove</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-show="isStep('embed')">
                                <h5 class="mb-3">Test and Embed</h5>
                                <label class="form-label">Embed Code</label>
                                <textarea class="form-control" rows="5" readonly :value="embedSnippet"></textarea>

                                <div class="row g-3 mt-2">
                                    <div class="col-md-6">
                                        <label class="form-label">Width</label>
                                        <input type="range" class="form-range" min="320" max="900"
                                            v-model.number="form.iframe_width" />
                                        <div class="small text-muted">{{ form.iframe_width }}px</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Height</label>
                                        <input type="range" class="form-range" min="500" max="1200"
                                            v-model.number="form.iframe_height" />
                                        <div class="small text-muted">{{ form.iframe_height }}px</div>
                                    </div>
                                </div>

                                <button class="btn btn-light-primary mt-2" @click="copyEmbed"><i class="ti ti-copy"></i>
                                    Copy to Clipboard</button>

                                <div class="mt-3 small text-muted">
                                    Paste this code just before the closing <code>&lt;/body&gt;</code> tag in your HTML
                                    file…
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <button class="btn btn-outline-secondary" @click="prevStep"
                                    :disabled="activeStep === 0">Back</button>
                                <div>
                                    <button v-if="activeStep < steps.length - 1" class="btn btn-primary"
                                        @click="nextStep" :disabled="!canProceed">
                                        Next
                                    </button>
                                    <button v-else class="btn btn-success" @click="finishWizard" :disabled="finishing">
                                        <i class="ti ti-check"></i> {{ finishing ? 'Finishing…' : 'Finish' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Preview -->
                <div class="col-lg-4 col-12">
                    <div class="card sticky-top" style="top:80px">
                        <div class="card-body">
                            <h6 class="mb-3">Live Preview</h6>
                            <div class="border rounded p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <img v-if="logoPreview && form.show_logo" :src="logoPreview" alt="logo"
                                        style="height:24px" class="me-2" />
                                    <strong>{{ form.name || 'Leadx' }}</strong>
                                    <small class="ms-auto" v-if="form.show_datetime">{{ now }}</small>
                                </div>
                                <div class="p-2 rounded mb-2" :style="{ background: form.color_accent, color: '#fff' }">
                                    {{ form.bubble_message || 'Hey there, How can I help you?' }}
                                </div>
                                <div class="small text-muted">Position: {{ form.position }} • Lang: {{ form.language }}
                                </div>
                            </div>

                            <div class="mt-3">
                                <div class="small text-muted mb-1">Embed Size</div>
                                <code>{{ form.iframe_width }} × {{ form.iframe_height }}</code>
                            </div>

                            <hr />
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                                    :style="triggerStyle">?</div>
                                <div class="ms-2 small">Trigger button preview</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import { Head } from "@inertiajs/vue3";
import AppLayout from "@/admin/Layouts/AppLayout.vue";
import Breadcrumb from "@/admin/Components/Breadcrumb.vue";
import Editor from '@tinymce/tinymce-vue'
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import axios from "axios";
import 'tinymce/tinymce'
import 'tinymce/icons/default'
import 'tinymce/themes/silver'
import 'tinymce/models/dom'
import 'tinymce/plugins/link'
import 'tinymce/plugins/table'
import 'tinymce/plugins/lists'
import 'tinymce/plugins/code'
import 'tinymce/plugins/wordcount'
import 'tinymce/skins/ui/oxide/skin.min.css'
import 'tinymce/skins/content/default/content.min.css'

export default {
    components: {
        Head,
        AppLayout,
        Breadcrumb,
        Editor
    },

    data() {
        return {
            loader: false,
            loadingText: '',
            steps: [{
                key: "website",
                label: "Website"
            },
            {
                key: "configure",
                label: "Configure"
            },
            {
                key: "customize",
                label: "Customize"
            },
            {
                key: "train",
                label: "Train"
            },
            {
                key: "embed",
                label: "Embed"
            },
            ],
            activeStep: 0,
            token: this.$cookies.get("lxf-token"),

            languageOptions: [{
                label: "Auto",
                value: "auto"
            },
            {
                label: "German",
                value: "de"
            },
            {
                label: "English",
                value: "en"
            },
            {
                label: "Spanish",
                value: "es"
            },
            {
                label: "French",
                value: "fr"
            },
            {
                label: "Arabic",
                value: "ar"
            },
            ],
            colorPalette: [
                "#111111",
                "#2ecc71",
                "#f1c40f",
                "#9b59b6",
                "#3498db",
                "#ff4dd2",
            ],
            avatars: [{
                key: "a1",
                src: "https://i.pravatar.cc/40?img=1"
            },
            {
                key: "a2",
                src: "https://i.pravatar.cc/40?img=2"
            },
            {
                key: "a3",
                src: "https://i.pravatar.cc/40?img=3"
            },
            {
                key: "a4",
                src: "https://i.pravatar.cc/40?img=4"
            },
            {
                key: "a5",
                src: "https://i.pravatar.cc/40?img=5"
            },
            ],

            form: {
                website_id: null,
                name: "",
                welcome_message: "Hi, how can I help you?",
                bubble_message: "Hey there, How can I help you?",
                system_prompt: "",
                do_not_go_beyond: false,
                language: "auto",
                interaction_type: 1,
                connect_message: "",
                color_accent: "#111111",
                position: "right",
                show_logo: false,
                show_datetime: false,
                transparent_trigger: false,
                trigger_avatar_size: 60,
                footer_link: "",
                custom_css: "",
                avatar_key: "a1",
                iframe_width: 420,
                iframe_height: 745,
                model: "gpt-4o-mini",
                temperature: 0.2,
                top_k: 3,
                public_token: "temp-uuid-demo",
            },

            websites: [],
            train: {
                website_url: ""
            },
            trainTab: "website",
            qaForms: [{
                title: "",
                content: ""
            }],
            activeAcc: 0,
            errors: {},
            logoPreview: "",
            init: {
                height: 200,
                min_height: 200,
                menubar: false,
                plugins: 'link table lists',
                toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright | bullist numlist | link ',
                skin: false,
                content_css: false,
                license_key: 'gpl',
                promotion: false,
                setup: (editor) => {
                    editor.on('init', () => {
                        const c = editor.getContainer?.()
                        if (c) c.style.visibility = 'inherit'
                    })
                }
            },
            logoFile: null,
            finishing: false,
            chatbotId: null,
            savingBot: false,
            savingQA: false,
        };
    },

    computed: {
        now() {
            return new Date().toLocaleString();
        },

        tinymceInit() {
            return this.init
        },

        triggerStyle() {
            const s = `${this.form.trigger_avatar_size || 60}px`;
            const bg = this.form.transparent_trigger ?
                "transparent" :
                this.form.color_accent || "#111";
            const brd = this.form.transparent_trigger ?
                `2px solid ${this.form.color_accent || "#111"}` :
                "none";
            return {
                width: s,
                height: s,
                background: bg,
                color: this.form.transparent_trigger ? "#333" : "#fff",
                borderRadius: "50%",
                border: brd,
                textAlign: "center",
            };
        },

        canAddQA() {
            const last = this.qaForms.at(-1);
            return (
                last &&
                last.title?.trim()?.length &&
                this.stripHtml(last.content).length
            );
        },

        canSaveQA() {
            return this.qaForms.some(
                (q) => q.title?.trim()?.length || this.stripHtml(q.content).length
            );
        },

        embedSnippet() {
            const lang = this.form.language || "auto";
            return `<script defer src="https://demo.magicproject.ai/vendor/chatbot/js/external-chatbot.js" data-chatbot-uuid="${this.form.public_token}" data-iframe-width="${this.form.iframe_width}" data-iframe-height="${this.form.iframe_height}" data-language="${lang}"></` + `script>`;
        },

        canProceed() {
            if (this.isStep('website')) {
                return !!this.form.website_id;
            }

            if (this.isStep('configure')) {
                const titleOk = (this.form.name || '').trim().length > 0;
                const instrOk = this.stripHtml(this.form.system_prompt).length > 0;
                return titleOk && instrOk;
            }

            if (this.isStep('customize')) {
                const hasLogo = !!(this.logoPreview || (this.form.logo_url || '').trim());
                const link = (this.form.footer_link || '').trim();
                return hasLogo && this.isValidUrl(link);
            }
            return true;
        },

        async finishWizard() {
            try {
                this.finishing = true;

                if (!this.form.website_id) {
                    this.err('Please select a website first.');
                    this.activeStep = 0;
                    return;
                }
                const titleOk = (this.form.name || '').trim().length > 0;
                const instrOk = this.stripHtml(this.form.system_prompt).length > 0;
                if (!titleOk || !instrOk) {
                    this.err('Chatbot Title & Instructions are required.');
                    this.activeStep = 1;
                    return;
                }
                const hasLogo = !!(this.logoPreview || (this.form.logo_url || '').trim());
                const link = (this.form.footer_link || '').trim();
                if (!hasLogo) {
                    this.err('Please upload a logo.');
                    this.activeStep = 2;
                    return;
                }
                if (!link || !this.isValidUrl(link)) {
                    this.err('Valid Footer Link is required.');
                    this.activeStep = 2;
                    return;
                }

                if ((this.form.logo_url || '').startsWith('data:') || this.logoFile) {
                    const fd = new FormData();
                    if (this.logoFile) {
                        fd.append('file', this.logoFile);
                    } else {
                        const blob = await fetch(this.form.logo_url).then(r => r.blob());
                        fd.append('file', blob, 'logo.png');
                    }
                    try {
                        this.setLoader('Uploading logo…');
                        const res = await axios.post('/api/v1/uploads/logo', fd, {
                            headers: {
                                Authorization: `Bearer ${this.token}`,
                                'Content-Type': 'multipart/form-data'
                            },
                        });
                        this.form.logo_url = res?.data?.url || res?.data?.path || '';
                        if (!this.form.logo_url) throw new Error('upload-no-url');
                    } catch {
                        this.err('Logo upload failed.');
                        this.activeStep = 2;
                        return;
                    }
                } else if ((this.form.logo_url || '').length > 255) {
                    this.form.logo_url = '';
                }

                this.setLoader('Saving Chatbot…');
                const okBot = await this.createBot();
                if (!okBot) {
                    this.activeStep = 1;
                    return;
                }

                if (this.canSaveQA) {
                    this.setLoader('Saving Q/A…');
                    await this.saveAllQA();
                }

                this.clearLoader();
                this.ok('All set!');
                this.$inertia.visit('/app/customer/chatbots');
            } finally {
                this.finishing = false;
                this.clearLoader();
            }
        }
    },

    methods: {
        ok(m) {
            toast.success(m);
        },
        err(m) {
            toast.error(m);
        },

        isStep(k) {
            return this.steps[this.activeStep].key === k
        },

        isValidUrl(u) {
            try {
                new URL(u);
                return true
            } catch {
                return false
            }
        },

        async nextStep() {
            if (this.isStep('website') && !this.form.website_id) {
                this.err('Please select a website first.');
                return;
            }

            if (this.isStep('configure') && !this.canProceed) {
                const titleOk = (this.form.name || '').trim().length > 0;
                const instrOk = this.stripHtml(this.form.system_prompt).length > 0;
                if (!titleOk || !instrOk) {
                    this.err('Chatbot Title & Instructions are required.');
                    return;
                }
            }

            if (this.isStep('customize')) {
                const hasLogo = !!(this.logoPreview || (this.form.logo_url || '').trim());
                const link = (this.form.footer_link || '').trim();
                if (!hasLogo) {
                    this.err('Please upload a logo.');
                    return;
                }
                if (!link || !this.isValidUrl(link)) {
                    this.err('Valid Footer Link is required.');
                    return;
                }
            }

            const next = this.activeStep + 1;
            const goingKey = this.steps[next]?.key;
            if (goingKey === 'train' && !this.chatbotId) {
                const ok = await this.ensureChatbot();
                if (!ok) return;
            }
            if (next < this.steps.length) this.activeStep = next;
        },

        prevStep() {
            if (this.activeStep > 0) this.activeStep--;
        },

        stepClass(i) {
            return {
                "bg-primary text-white border-primary": i <= this.activeStep,
                "bg-light text-muted": i > this.activeStep,
            };
        },

        async ensureChatbot() {
            if (this.chatbotId) return true;
            return await this.createBot();
        },

        api() {
            return {
                base: '/api/v1/chatbots',
                auth: {
                    headers: {
                        Authorization: `Bearer ${this.token}`
                    }
                }
            }
        },

        setLoader(msg) {
            this.loadingText = msg || '';
            this.loader = true;
            this.$refs?.app_layout?.setLoaderText?.(this.loadingText);
        },
        clearLoader() {
            this.loader = false;
            this.loadingText = '';
            this.$refs?.app_layout?.setLoaderText?.('');
        },

        async loadWebsites() {
            try {
                const {
                    data
                } = await axios.get(route('api.website.get.all'), {
                    headers: {
                        Authorization: `Bearer ${this.token}`
                    },
                });
                this.websites = Array.isArray(data?.data) ? data.data : [];
            } catch {
                this.websites = [];
            }
        },

        addQA() {
            if (!this.canAddQA) return;
            this.qaForms.push({
                title: "",
                content: ""
            });
            this.$nextTick(() => (this.activeAcc = this.qaForms.length - 1));
        },

        removeQA(i) {
            if (i === 0 && this.qaForms.length === 1) return;
            this.qaForms.splice(i, 1);
            if (this.activeAcc === i) this.activeAcc = Math.max(0, i - 1);
        },

        fieldErr(idx, key) {
            return this.errors?.[`qa_pairs.${idx}.${key}`] || null;
        },

        clearFieldErr(idx, key) {
            const k = `qa_pairs.${idx}.${key}`;
            if (this.errors[k]) delete this.errors[k];
        },

        stripHtml(html) {
            return (html || '')
                .replace(/<style[\s\S]*?<\/style>/gi, '')
                .replace(/<script[\s\S]*?<\/script>/gi, '')
                .replace(/<[^>]*>/g, '')
                .replace(/&nbsp;/g, ' ')
                .trim()
        },

        copyEmbed() {
            navigator.clipboard?.writeText(this.embedSnippet);
        },

        onLogoPick(e) {
            // const f = e.target.files?.[0];
            // if (!f) return;
            // const r = new FileReader();
            // r.onload = v => {
            //     this.logoPreview = v.target.result;
            //     this.form.logo_url = this.logoPreview;
            // };
            // r.readAsDataURL(f);
            const f = e.target.files?.[0];
            if (!f) return;
            this.logoFile = f;
            this.logoPreview = URL.createObjectURL(f);
        },

        async uploadLogoIfNeeded() {
            if (!this.logoFile) return true;
            const fd = new FormData();
            fd.append('file', this.logoFile);
            try {
                const {
                    data
                } = await axios.post('/api/v1/chatbots/uploads/logo', fd, {
                    headers: {
                        Authorization: `Bearer ${this.token}`,
                        'Content-Type': 'multipart/form-data',
                    },
                });

                this.form.logo_url = data?.url || '';
                return !!this.form.logo_url;
            } catch (e) {
                this.err('Logo upload failed');
                return false;
            }
        },

        async createBot() {
            try {
                this.savingBot = true;
                const p = {
                    website_id: this.form.website_id,
                    name: this.form.name,
                    is_active: true,
                    system_prompt: this.form.system_prompt,
                    bubble_message: this.form.bubble_message,
                    welcome_message: this.form.welcome_message,
                    connect_message: this.form.connect_message,
                    interaction_type: this.form.interaction_type,
                    language: this.form.language,
                    do_not_go_beyond: this.form.do_not_go_beyond,
                    model: this.form.model,
                    temperature: this.form.temperature,
                    top_k: this.form.top_k,
                    color_accent: this.form.color_accent,
                    logo_url: this.form.logo_url,
                    show_logo: this.form.show_logo,
                    show_datetime: this.form.show_datetime,
                    transparent_trigger: this.form.transparent_trigger,
                    trigger_avatar_size: this.form.trigger_avatar_size,
                    position: this.form.position,
                    footer_link: this.form.footer_link,
                    iframe_width: this.form.iframe_width,
                    iframe_height: this.form.iframe_height,
                };
                if (!p.website_id) {
                    this.err('Please select website');
                    return false;
                }
                if (!p.name?.trim()) {
                    this.err('Chatbot name required');
                    return false;
                }

                const {
                    base,
                    auth
                } = this.api();
                const url = this.chatbotId ? `${base}/${this.chatbotId}` : base;
                const method = this.chatbotId ? 'put' : 'post';
                const {
                    data
                } = await axios[method](url, p, auth);

                const bot = data?.data || data;
                this.chatbotId = bot.id;
                if (bot.public_token) this.form.public_token = bot.public_token;
                this.ok('Chatbot saved');
                return true;
            } catch (e) {
                if (e.response?.status === 422) {
                    const errs = e.response.data.errors || {};
                    this.errors = errs;
                    this.err(Object.values(errs)[0]?.[0] || 'Validation failed');
                } else this.err('Failed to save chatbot');
                return false;
            } finally {
                this.savingBot = false;
            }
        },

        async saveAllQA() {
            if (!this.canSaveQA) {
                this.err('Add at least one Q/A');
                return
            }
            const ok = await this.ensureChatbot();
            if (!ok) return;

            this.savingQA = true;
            this.errors = {};
            try {
                const pairs = this.qaForms
                    .filter(q => (q.title?.trim()?.length && this.stripHtml(q.content).length))
                    .map(q => ({
                        question: q.title,
                        answer: q.content
                    }));
                const {
                    base,
                    auth
                } = this.api();
                await axios.post(`${base}/${this.chatbotId}/qa/bulk`, {
                    qa_pairs: pairs
                }, auth);

                this.ok('Q/A saved');
                this.qaForms = [{
                    title: '',
                    content: ''
                }];
                this.activeAcc = 0;
            } catch (e) {
                if (e.response?.status === 422) {
                    this.errors = e.response.data.errors || {};
                    this.err('Validation failed');
                } else this.err('Failed to save Q/A');
            } finally {
                this.savingQA = false
            }
        },
    },

    mounted() {
        this.$refs?.app_layout?.loadScript?.();
        this.loadWebsites();
    },
};

</script>