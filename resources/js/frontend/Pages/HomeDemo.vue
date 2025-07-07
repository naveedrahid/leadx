<template>

    <Head title="Home" />
    <MasterLayout :loader="loader">
        <div class="leadxhome">
            <section class="banner">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="banner-title d-flex justify-content-center">
                                <p class="inter-text d-inline border rounded-pill">
                                    <span class="px-2 py-1 border rounded-pill me-3">
                                        UltimateAI</span>
                                    #1 ai text to audio platform
                                </p>
                            </div>
                            <h1 class="outfit-heading banner-heading text-center">
                                Welcome To <br />{{ $page.props.app.name }}
                            </h1>
                            <p class="inter-text text-center">
                                The Unlimited solution for creating custom forms
                                and flows to <br />connect users and enhance
                                engagement and broaden your <br />online
                                presence.
                            </p>
                            <div class="d-flex justify-content-center py-2 gap-3">
                                <a href="#pricing" class="btn d-flex align-items-center text-white px-4" style="
                                        background-color: #008744;
                                        border-radius: 10px;
                                        padding-top: 12px;
                                        padding-bottom: 15px;
                                    ">
                                    Explore All Tools
                                    <span class="fs-5 fw-bold ms-2 lh-1">&#10141;</span>
                                </a>
                                <a href="#pricing" class="btn btn-outline-dark px-4" style="
                                        border-radius: 10px;
                                        padding-top: 12px;
                                        padding-bottom: 15px;
                                    ">
                                    Start Creating
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="image-section">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-10 col-md-12 col-sm-12">
                            <img src="/_public_assets/img/welcome-to-leadxforms.png" class="img-fluid rounded-top" alt="" />
                            <p class="text-center mt-5">
                                For a more efficient workflow, view all of your
                                leads in one location.
                            </p>
                        </div>
                    </div>
                </div>
            </section>
            <section>
                <div class="container">
                    <div class="row py-5">
                        <div class="slider" v-if="brands.length > 0">
                            <div class="slide-track">
                                <div class="slide" v-for="(brand, index) in brands" :key="index">
                                    <img class="img-gray" :src="brand.image" height="100" width="250"
                                        :alt="brand.name" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="icon-boxes">
                <div class="container rounded-container py-5 my-5">
                    <div class="row align-items-center">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="banner-title d-flex justify-content-center mb-3">
                                <span class="px-4 py-1 border rounded-pill me-3">{{ $page.props.app.name }}
                                    Features</span>
                            </div>
                            <!-- <h1 class="outfit-heading banner-heading text-center">Turn text to audio, <br>in minutes.</h1> -->
                            <h2 class="text-center mb-3">
                                WordPress
                                <span class="text-primary">{{
                                    $page.props.app.name
                                    }}</span>
                                Plugin that's Easy to Create
                            </h2>
                            <!-- <p class="inter-text text-center mb-3">
                                Streamline your operations, simplify management
                                with admin tools.
                            </p> -->
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 px-4">
                            <div class="icon-content pt-4">
                                <div class="row" v-if="features.length > 0">
                                    <div v-for="(feature, index) in features" :key="index"
                                        class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="icon-box p-4 border bg-white rounded-3">
                                            <div class="p-2 d-inline-flex rounded-3 mb-4" style="
                                                    background: #f6f6f7;
                                                    border-radius: 0.5rem !important;
                                                ">
                                                <img :src="feature.image" :alt="feature.title" class="feature-icon" />
                                            </div>
                                            <h5 class="text-black pb-2">
                                                {{ feature.title }}
                                            </h5>
                                            <p class="text-muted small">
                                                {{ feature.description }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="customer-trusted linear-gradient-bg">
                <div class="container py-5 my-5">
                    <div class="row align-items-center">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="banner-title d-flex justify-content-center mb-3">
                                <span class="px-4 py-1 border rounded-pill me-3">
                                    Customer Feedback</span>
                            </div>
                            <!-- <h1 class="outfit-heading banner-heading text-center">Turn text to audio, <br>in minutes.</h1> -->
                            <h2 class="text-center mb-3">
                                Our
                                <span class="text-primary">Customer</span> says
                            </h2>
                            <!-- <p class="inter-text text-center mb-3">
                                Discover how our customers transformed their
                                narratives into tangible returns.
                            </p> -->
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 px-4">
                            <div class="icon-content pt-4">
                                <div class="row" v-if="tesimonials.length > 0">
                                    <!-- START BOX -->
                                    <div class="col-lg-4 col-md-4 col-sm-12" v-for="(
tesimonial, index
                                        ) in tesimonials" :key="index">
                                        <div
                                            class="trusted-box justify-content-between p-4 border d-flex flex-column gap-2 bg-white rounded-3">
                                            <div class="trusted-content">
                                                <p>{{ tesimonial.text }}</p>
                                            </div>
                                            <div class="d-flex gap-3 mb-4">
                                                <div class="trusted-img">
                                                    <img :src="tesimonial.image" width="60" height="60"
                                                        class="rounded-pill" :alt="tesimonial.author" />
                                                </div>
                                                <div class="trusted-title d-flex flex-column justify-content-center">
                                                    <h6>
                                                        {{ tesimonial.author }}
                                                    </h6>
                                                    <p class="trusted-designation m-0">
                                                        Creative Director -
                                                        Stripe
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END BOX -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="pricing-plan linear-gradient-bg my-5 py-5" id="pricing">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-12 col-md-12 col-sm-12 mb-5">
                            <div class="banner-title d-flex justify-content-center mb-3">
                                <span class="px-4 py-1 border rounded-pill me-3">Our Pricing</span>
                            </div>
                            <!-- <h1 class="outfit-heading banner-heading text-center">Turn text to audio, <br>in minutes.</h1> -->
                            <h2 class="text-center mb-3">Pricing plan</h2>
                            <p class="inter-text text-center mb-3">
                                Our plans are simple and transparent, they are
                                based on the number<br />
                                of generated words on a rolling monthly basis.
                            </p>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="icon-content pt-4">
                                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                                    <!-- START BOX -->
                                    <div class="col-lg-4 col-md-4 col-sm-12 p-0 bg-white border"
                                        style="border-top-left-radius: 10px">
                                        <div class="pricing_box d-flex flex-column gap-2"
                                            style="border-top-left-radius: 10px">
                                            <div
                                                class="pricing-content d-flex flex-column align-items-center gap-3 pb-4">
                                                <h3 class="text-center">
                                                    Free
                                                </h3>
                                                <p class="text-center m-0">
                                                    Get peace of mind, try
                                                    UltimateAI for free
                                                </p>
                                                <span class="plan-price text-black">$0.00<sup class="text-muted"
                                                        style="font-size: 20px">
                                                        /limited</sup></span>
                                                <button class="button button-primary button-s2 px-4 fw-semibold" style="
                                                        width: 100%;
                                                        border-radius: 10px;
                                                        padding-top: 12px;
                                                        padding-bottom: 15px;
                                                    ">
                                                    Start Free trial
                                                </button>
                                            </div>
                                            <div class="py-1">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 26 26" fill="none">
                                                        <circle cx="14" cy="14" r="10" fill="#284C4E"></circle>
                                                        <circle cx="13" cy="13" r="10" fill="#284C4E"></circle>
                                                        <path
                                                            d="M16.9568 9.87678L16.9568 9.8768L16.9597 9.87376C17.3418 9.47853 17.346 8.85248 16.9568 8.46322C16.5691 8.07559 15.9309 8.07559 15.5432 8.46322L10.58 13.4264L8.45678 11.3032C8.06915 10.9156 7.43085 10.9156 7.04322 11.3032C6.65559 11.6909 6.65559 12.3291 7.04322 12.7168L9.87322 15.5468C10.0596 15.7331 10.3129 15.84 10.58 15.84C10.8471 15.84 11.1004 15.7331 11.2868 15.5468L16.9568 9.87678ZM2.25 12C2.25 6.62807 6.62807 2.25 12 2.25C17.3719 2.25 21.75 6.62807 21.75 12C21.75 17.3719 17.3719 21.75 12 21.75C6.62807 21.75 2.25 17.3719 2.25 12Z"
                                                            fill="white" stroke="#284C4E" stroke-width="0.5"></path>
                                                    </svg>
                                                    <span class="px-2">
                                                        1500 Word (one
                                                        time)</span>
                                                </span>
                                            </div>
                                            <div class="py-1">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 26 26" fill="none">
                                                        <circle cx="14" cy="14" r="10" fill="#284C4E"></circle>
                                                        <circle cx="13" cy="13" r="10" fill="#284C4E"></circle>
                                                        <path
                                                            d="M16.9568 9.87678L16.9568 9.8768L16.9597 9.87376C17.3418 9.47853 17.346 8.85248 16.9568 8.46322C16.5691 8.07559 15.9309 8.07559 15.5432 8.46322L10.58 13.4264L8.45678 11.3032C8.06915 10.9156 7.43085 10.9156 7.04322 11.3032C6.65559 11.6909 6.65559 12.3291 7.04322 12.7168L9.87322 15.5468C10.0596 15.7331 10.3129 15.84 10.58 15.84C10.8471 15.84 11.1004 15.7331 11.2868 15.5468L16.9568 9.87678ZM2.25 12C2.25 6.62807 6.62807 2.25 12 2.25C17.3719 2.25 21.75 6.62807 21.75 12C21.75 17.3719 17.3719 21.75 12 21.75C6.62807 21.75 2.25 17.3719 2.25 12Z"
                                                            fill="white" stroke="#284C4E" stroke-width="0.5"></path>
                                                    </svg>
                                                    <span class="px-2">
                                                        20 Image Generate (one
                                                        time)</span>
                                                </span>
                                            </div>
                                            <div class="py-1">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 26 26" fill="none">
                                                        <circle cx="14" cy="14" r="10" fill="#284C4E"></circle>
                                                        <circle cx="13" cy="13" r="10" fill="#284C4E"></circle>
                                                        <path
                                                            d="M16.9568 9.87678L16.9568 9.8768L16.9597 9.87376C17.3418 9.47853 17.346 8.85248 16.9568 8.46322C16.5691 8.07559 15.9309 8.07559 15.5432 8.46322L10.58 13.4264L8.45678 11.3032C8.06915 10.9156 7.43085 10.9156 7.04322 11.3032C6.65559 11.6909 6.65559 12.3291 7.04322 12.7168L9.87322 15.5468C10.0596 15.7331 10.3129 15.84 10.58 15.84C10.8471 15.84 11.1004 15.7331 11.2868 15.5468L16.9568 9.87678ZM2.25 12C2.25 6.62807 6.62807 2.25 12 2.25C17.3719 2.25 21.75 6.62807 21.75 12C21.75 17.3719 17.3719 21.75 12 21.75C6.62807 21.75 2.25 17.3719 2.25 12Z"
                                                            fill="white" stroke="#284C4E" stroke-width="0.5"></path>
                                                    </svg>
                                                    <span class="px-2">
                                                        100+ AI Templates</span>
                                                </span>
                                            </div>
                                            <div class="py-1">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 26 26" fill="none">
                                                        <circle cx="14" cy="14" r="10" fill="#284C4E"></circle>
                                                        <circle cx="13" cy="13" r="10" fill="#284C4E"></circle>
                                                        <path
                                                            d="M16.9568 9.87678L16.9568 9.8768L16.9597 9.87376C17.3418 9.47853 17.346 8.85248 16.9568 8.46322C16.5691 8.07559 15.9309 8.07559 15.5432 8.46322L10.58 13.4264L8.45678 11.3032C8.06915 10.9156 7.43085 10.9156 7.04322 11.3032C6.65559 11.6909 6.65559 12.3291 7.04322 12.7168L9.87322 15.5468C10.0596 15.7331 10.3129 15.84 10.58 15.84C10.8471 15.84 11.1004 15.7331 11.2868 15.5468L16.9568 9.87678ZM2.25 12C2.25 6.62807 6.62807 2.25 12 2.25C17.3719 2.25 21.75 6.62807 21.75 12C21.75 17.3719 17.3719 21.75 12 21.75C6.62807 21.75 2.25 17.3719 2.25 12Z"
                                                            fill="white" stroke="#284C4E" stroke-width="0.5"></path>
                                                    </svg>
                                                    <span class="px-2">
                                                        Blog Wizard</span>
                                                </span>
                                            </div>
                                            <div class="py-1">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 26 26" fill="none">
                                                        <circle cx="14" cy="14" r="10" fill="#284C4E"></circle>
                                                        <circle cx="13" cy="13" r="10" fill="#284C4E"></circle>
                                                        <path
                                                            d="M16.9568 9.87678L16.9568 9.8768L16.9597 9.87376C17.3418 9.47853 17.346 8.85248 16.9568 8.46322C16.5691 8.07559 15.9309 8.07559 15.5432 8.46322L10.58 13.4264L8.45678 11.3032C8.06915 10.9156 7.43085 10.9156 7.04322 11.3032C6.65559 11.6909 6.65559 12.3291 7.04322 12.7168L9.87322 15.5468C10.0596 15.7331 10.3129 15.84 10.58 15.84C10.8471 15.84 11.1004 15.7331 11.2868 15.5468L16.9568 9.87678ZM2.25 12C2.25 6.62807 6.62807 2.25 12 2.25C17.3719 2.25 21.75 6.62807 21.75 12C21.75 17.3719 17.3719 21.75 12 21.75C6.62807 21.75 2.25 17.3719 2.25 12Z"
                                                            fill="white" stroke="#284C4E" stroke-width="0.5"></path>
                                                    </svg>
                                                    <span class="px-2">
                                                        Plagiarism Checker</span>
                                                </span>
                                            </div>
                                            <div class="py-1">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 26 26" fill="none">
                                                        <circle cx="14" cy="14" r="10" fill="#284C4E"></circle>
                                                        <circle cx="13" cy="13" r="10" fill="#284C4E"></circle>
                                                        <path
                                                            d="M16.9568 9.87678L16.9568 9.8768L16.9597 9.87376C17.3418 9.47853 17.346 8.85248 16.9568 8.46322C16.5691 8.07559 15.9309 8.07559 15.5432 8.46322L10.58 13.4264L8.45678 11.3032C8.06915 10.9156 7.43085 10.9156 7.04322 11.3032C6.65559 11.6909 6.65559 12.3291 7.04322 12.7168L9.87322 15.5468C10.0596 15.7331 10.3129 15.84 10.58 15.84C10.8471 15.84 11.1004 15.7331 11.2868 15.5468L16.9568 9.87678ZM2.25 12C2.25 6.62807 6.62807 2.25 12 2.25C17.3719 2.25 21.75 6.62807 21.75 12C21.75 17.3719 17.3719 21.75 12 21.75C6.62807 21.75 2.25 17.3719 2.25 12Z"
                                                            fill="white" stroke="#284C4E" stroke-width="0.5"></path>
                                                    </svg>
                                                    <span class="px-2">
                                                        80+ Languages</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END BOX -->
                                    <!-- START BOX -->
                                    <div class="col-lg-4 col-md-4 col-sm-12 p-0 bg-white border" style="
                                            margin-top: -20px;
                                            border-top-left-radius: 10px;
                                            border-top-right-radius: 10px;
                                        ">
                                        <div class="pricing_box d-flex flex-column gap-2 bg-white" style="
                                                border-top-left-radius: 10px;
                                                border-top-right-radius: 10px;
                                            ">
                                            <div
                                                class="pricing-content d-flex flex-column align-items-center gap-3 pb-4">
                                                <h3 class="text-center">
                                                    Regular
                                                </h3>
                                                <p class="text-center m-0">
                                                    Essential for freelancers
                                                    and content writers
                                                </p>
                                                <span class="plan-price text-black">$35.00<sup class="text-muted"
                                                        style="font-size: 20px">
                                                        /lifetime</sup></span>
                                                <button class="button button-primary button-s2 px-4 fw-semibold" style="
                                                        width: 100%;
                                                        border-radius: 10px;
                                                        padding-top: 12px;
                                                        padding-bottom: 15px;
                                                    ">
                                                    Get Started
                                                </button>
                                            </div>
                                            <p class="fw-bold">
                                                Everything in Free
                                            </p>
                                            <div class="py-1">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 26 26" fill="none">
                                                        <circle cx="14" cy="14" r="10" fill="#284C4E"></circle>
                                                        <circle cx="13" cy="13" r="10" fill="#284C4E"></circle>
                                                        <path
                                                            d="M16.9568 9.87678L16.9568 9.8768L16.9597 9.87376C17.3418 9.47853 17.346 8.85248 16.9568 8.46322C16.5691 8.07559 15.9309 8.07559 15.5432 8.46322L10.58 13.4264L8.45678 11.3032C8.06915 10.9156 7.43085 10.9156 7.04322 11.3032C6.65559 11.6909 6.65559 12.3291 7.04322 12.7168L9.87322 15.5468C10.0596 15.7331 10.3129 15.84 10.58 15.84C10.8471 15.84 11.1004 15.7331 11.2868 15.5468L16.9568 9.87678ZM2.25 12C2.25 6.62807 6.62807 2.25 12 2.25C17.3719 2.25 21.75 6.62807 21.75 12C21.75 17.3719 17.3719 21.75 12 21.75C6.62807 21.75 2.25 17.3719 2.25 12Z"
                                                            fill="white" stroke="#284C4E" stroke-width="0.5"></path>
                                                    </svg>
                                                    <span class="px-2">
                                                        1500 Word (one
                                                        time)</span>
                                                </span>
                                            </div>
                                            <div class="py-1">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 26 26" fill="none">
                                                        <circle cx="14" cy="14" r="10" fill="#284C4E"></circle>
                                                        <circle cx="13" cy="13" r="10" fill="#284C4E"></circle>
                                                        <path
                                                            d="M16.9568 9.87678L16.9568 9.8768L16.9597 9.87376C17.3418 9.47853 17.346 8.85248 16.9568 8.46322C16.5691 8.07559 15.9309 8.07559 15.5432 8.46322L10.58 13.4264L8.45678 11.3032C8.06915 10.9156 7.43085 10.9156 7.04322 11.3032C6.65559 11.6909 6.65559 12.3291 7.04322 12.7168L9.87322 15.5468C10.0596 15.7331 10.3129 15.84 10.58 15.84C10.8471 15.84 11.1004 15.7331 11.2868 15.5468L16.9568 9.87678ZM2.25 12C2.25 6.62807 6.62807 2.25 12 2.25C17.3719 2.25 21.75 6.62807 21.75 12C21.75 17.3719 17.3719 21.75 12 21.75C6.62807 21.75 2.25 17.3719 2.25 12Z"
                                                            fill="white" stroke="#284C4E" stroke-width="0.5"></path>
                                                    </svg>
                                                    <span class="px-2">
                                                        20 Image Generate (one
                                                        time)</span>
                                                </span>
                                            </div>
                                            <div class="py-1">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 26 26" fill="none">
                                                        <circle cx="14" cy="14" r="10" fill="#284C4E"></circle>
                                                        <circle cx="13" cy="13" r="10" fill="#284C4E"></circle>
                                                        <path
                                                            d="M16.9568 9.87678L16.9568 9.8768L16.9597 9.87376C17.3418 9.47853 17.346 8.85248 16.9568 8.46322C16.5691 8.07559 15.9309 8.07559 15.5432 8.46322L10.58 13.4264L8.45678 11.3032C8.06915 10.9156 7.43085 10.9156 7.04322 11.3032C6.65559 11.6909 6.65559 12.3291 7.04322 12.7168L9.87322 15.5468C10.0596 15.7331 10.3129 15.84 10.58 15.84C10.8471 15.84 11.1004 15.7331 11.2868 15.5468L16.9568 9.87678ZM2.25 12C2.25 6.62807 6.62807 2.25 12 2.25C17.3719 2.25 21.75 6.62807 21.75 12C21.75 17.3719 17.3719 21.75 12 21.75C6.62807 21.75 2.25 17.3719 2.25 12Z"
                                                            fill="white" stroke="#284C4E" stroke-width="0.5"></path>
                                                    </svg>
                                                    <span class="px-2">
                                                        100+ AI Templates</span>
                                                </span>
                                            </div>
                                            <div class="py-1">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 26 26" fill="none">
                                                        <circle cx="14" cy="14" r="10" fill="#284C4E"></circle>
                                                        <circle cx="13" cy="13" r="10" fill="#284C4E"></circle>
                                                        <path
                                                            d="M16.9568 9.87678L16.9568 9.8768L16.9597 9.87376C17.3418 9.47853 17.346 8.85248 16.9568 8.46322C16.5691 8.07559 15.9309 8.07559 15.5432 8.46322L10.58 13.4264L8.45678 11.3032C8.06915 10.9156 7.43085 10.9156 7.04322 11.3032C6.65559 11.6909 6.65559 12.3291 7.04322 12.7168L9.87322 15.5468C10.0596 15.7331 10.3129 15.84 10.58 15.84C10.8471 15.84 11.1004 15.7331 11.2868 15.5468L16.9568 9.87678ZM2.25 12C2.25 6.62807 6.62807 2.25 12 2.25C17.3719 2.25 21.75 6.62807 21.75 12C21.75 17.3719 17.3719 21.75 12 21.75C6.62807 21.75 2.25 17.3719 2.25 12Z"
                                                            fill="white" stroke="#284C4E" stroke-width="0.5"></path>
                                                    </svg>
                                                    <span class="px-2">
                                                        Blog Wizard</span>
                                                </span>
                                            </div>
                                            <div class="py-1">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 26 26" fill="none">
                                                        <circle cx="14" cy="14" r="10" fill="#284C4E"></circle>
                                                        <circle cx="13" cy="13" r="10" fill="#284C4E"></circle>
                                                        <path
                                                            d="M16.9568 9.87678L16.9568 9.8768L16.9597 9.87376C17.3418 9.47853 17.346 8.85248 16.9568 8.46322C16.5691 8.07559 15.9309 8.07559 15.5432 8.46322L10.58 13.4264L8.45678 11.3032C8.06915 10.9156 7.43085 10.9156 7.04322 11.3032C6.65559 11.6909 6.65559 12.3291 7.04322 12.7168L9.87322 15.5468C10.0596 15.7331 10.3129 15.84 10.58 15.84C10.8471 15.84 11.1004 15.7331 11.2868 15.5468L16.9568 9.87678ZM2.25 12C2.25 6.62807 6.62807 2.25 12 2.25C17.3719 2.25 21.75 6.62807 21.75 12C21.75 17.3719 17.3719 21.75 12 21.75C6.62807 21.75 2.25 17.3719 2.25 12Z"
                                                            fill="white" stroke="#284C4E" stroke-width="0.5"></path>
                                                    </svg>
                                                    <span class="px-2">
                                                        Plagiarism Checker</span>
                                                </span>
                                            </div>
                                            <div class="py-1">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 26 26" fill="none">
                                                        <circle cx="14" cy="14" r="10" fill="#284C4E"></circle>
                                                        <circle cx="13" cy="13" r="10" fill="#284C4E"></circle>
                                                        <path
                                                            d="M16.9568 9.87678L16.9568 9.8768L16.9597 9.87376C17.3418 9.47853 17.346 8.85248 16.9568 8.46322C16.5691 8.07559 15.9309 8.07559 15.5432 8.46322L10.58 13.4264L8.45678 11.3032C8.06915 10.9156 7.43085 10.9156 7.04322 11.3032C6.65559 11.6909 6.65559 12.3291 7.04322 12.7168L9.87322 15.5468C10.0596 15.7331 10.3129 15.84 10.58 15.84C10.8471 15.84 11.1004 15.7331 11.2868 15.5468L16.9568 9.87678ZM2.25 12C2.25 6.62807 6.62807 2.25 12 2.25C17.3719 2.25 21.75 6.62807 21.75 12C21.75 17.3719 17.3719 21.75 12 21.75C6.62807 21.75 2.25 17.3719 2.25 12Z"
                                                            fill="white" stroke="#284C4E" stroke-width="0.5"></path>
                                                    </svg>
                                                    <span class="px-2">
                                                        80+ Languages</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END BOX -->
                                    <!-- START BOX -->
                                    <div class="col-lg-4 col-md-4 col-sm-12 p-0 bg-white border"
                                        style="border-top-right-radius: 10px">
                                        <div class="pricing_box d-flex flex-column gap-2" style="
                                                border-top-right-radius: 10px;
                                            ">
                                            <span class="popular-badge">Most Popular</span>
                                            <div
                                                class="pricing-content d-flex flex-column align-items-center gap-3 pb-4">
                                                <h3 class="text-center">
                                                    Extended
                                                </h3>
                                                <p class="text-center m-0">
                                                    Ideal for large businesses
                                                    and enterprises
                                                </p>
                                                <span class="plan-price text-black">$109.00<sup class="text-muted"
                                                        style="font-size: 20px">
                                                        /lifetime</sup></span>
                                                <button class="button button-primary button-s2 px-4 fw-semibold" style="
                                                        width: 100%;
                                                        border-radius: 10px;
                                                        padding-top: 12px;
                                                        padding-bottom: 15px;
                                                    ">
                                                    Get Started
                                                </button>
                                            </div>
                                            <p class="fw-bold">
                                                Everything in Regular, plus:
                                            </p>
                                            <div class="py-1">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 26 26" fill="none">
                                                        <circle cx="14" cy="14" r="10" fill="#284C4E"></circle>
                                                        <circle cx="13" cy="13" r="10" fill="#284C4E"></circle>
                                                        <path
                                                            d="M16.9568 9.87678L16.9568 9.8768L16.9597 9.87376C17.3418 9.47853 17.346 8.85248 16.9568 8.46322C16.5691 8.07559 15.9309 8.07559 15.5432 8.46322L10.58 13.4264L8.45678 11.3032C8.06915 10.9156 7.43085 10.9156 7.04322 11.3032C6.65559 11.6909 6.65559 12.3291 7.04322 12.7168L9.87322 15.5468C10.0596 15.7331 10.3129 15.84 10.58 15.84C10.8471 15.84 11.1004 15.7331 11.2868 15.5468L16.9568 9.87678ZM2.25 12C2.25 6.62807 6.62807 2.25 12 2.25C17.3719 2.25 21.75 6.62807 21.75 12C21.75 17.3719 17.3719 21.75 12 21.75C6.62807 21.75 2.25 17.3719 2.25 12Z"
                                                            fill="white" stroke="#284C4E" stroke-width="0.5"></path>
                                                    </svg>
                                                    <span class="px-2">
                                                        1500 Word (one
                                                        time)</span>
                                                </span>
                                            </div>
                                            <div class="py-1">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 26 26" fill="none">
                                                        <circle cx="14" cy="14" r="10" fill="#284C4E"></circle>
                                                        <circle cx="13" cy="13" r="10" fill="#284C4E"></circle>
                                                        <path
                                                            d="M16.9568 9.87678L16.9568 9.8768L16.9597 9.87376C17.3418 9.47853 17.346 8.85248 16.9568 8.46322C16.5691 8.07559 15.9309 8.07559 15.5432 8.46322L10.58 13.4264L8.45678 11.3032C8.06915 10.9156 7.43085 10.9156 7.04322 11.3032C6.65559 11.6909 6.65559 12.3291 7.04322 12.7168L9.87322 15.5468C10.0596 15.7331 10.3129 15.84 10.58 15.84C10.8471 15.84 11.1004 15.7331 11.2868 15.5468L16.9568 9.87678ZM2.25 12C2.25 6.62807 6.62807 2.25 12 2.25C17.3719 2.25 21.75 6.62807 21.75 12C21.75 17.3719 17.3719 21.75 12 21.75C6.62807 21.75 2.25 17.3719 2.25 12Z"
                                                            fill="white" stroke="#284C4E" stroke-width="0.5"></path>
                                                    </svg>
                                                    <span class="px-2">
                                                        20 Image Generate (one
                                                        time)</span>
                                                </span>
                                            </div>
                                            <div class="py-1">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 26 26" fill="none">
                                                        <circle cx="14" cy="14" r="10" fill="#284C4E"></circle>
                                                        <circle cx="13" cy="13" r="10" fill="#284C4E"></circle>
                                                        <path
                                                            d="M16.9568 9.87678L16.9568 9.8768L16.9597 9.87376C17.3418 9.47853 17.346 8.85248 16.9568 8.46322C16.5691 8.07559 15.9309 8.07559 15.5432 8.46322L10.58 13.4264L8.45678 11.3032C8.06915 10.9156 7.43085 10.9156 7.04322 11.3032C6.65559 11.6909 6.65559 12.3291 7.04322 12.7168L9.87322 15.5468C10.0596 15.7331 10.3129 15.84 10.58 15.84C10.8471 15.84 11.1004 15.7331 11.2868 15.5468L16.9568 9.87678ZM2.25 12C2.25 6.62807 6.62807 2.25 12 2.25C17.3719 2.25 21.75 6.62807 21.75 12C21.75 17.3719 17.3719 21.75 12 21.75C6.62807 21.75 2.25 17.3719 2.25 12Z"
                                                            fill="white" stroke="#284C4E" stroke-width="0.5"></path>
                                                    </svg>
                                                    <span class="px-2">
                                                        100+ AI Templates</span>
                                                </span>
                                            </div>
                                            <div class="py-1">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 26 26" fill="none">
                                                        <circle cx="14" cy="14" r="10" fill="#284C4E"></circle>
                                                        <circle cx="13" cy="13" r="10" fill="#284C4E"></circle>
                                                        <path
                                                            d="M16.9568 9.87678L16.9568 9.8768L16.9597 9.87376C17.3418 9.47853 17.346 8.85248 16.9568 8.46322C16.5691 8.07559 15.9309 8.07559 15.5432 8.46322L10.58 13.4264L8.45678 11.3032C8.06915 10.9156 7.43085 10.9156 7.04322 11.3032C6.65559 11.6909 6.65559 12.3291 7.04322 12.7168L9.87322 15.5468C10.0596 15.7331 10.3129 15.84 10.58 15.84C10.8471 15.84 11.1004 15.7331 11.2868 15.5468L16.9568 9.87678ZM2.25 12C2.25 6.62807 6.62807 2.25 12 2.25C17.3719 2.25 21.75 6.62807 21.75 12C21.75 17.3719 17.3719 21.75 12 21.75C6.62807 21.75 2.25 17.3719 2.25 12Z"
                                                            fill="white" stroke="#284C4E" stroke-width="0.5"></path>
                                                    </svg>
                                                    <span class="px-2">
                                                        Blog Wizard</span>
                                                </span>
                                            </div>
                                            <div class="py-1">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 26 26" fill="none">
                                                        <circle cx="14" cy="14" r="10" fill="#284C4E"></circle>
                                                        <circle cx="13" cy="13" r="10" fill="#284C4E"></circle>
                                                        <path
                                                            d="M16.9568 9.87678L16.9568 9.8768L16.9597 9.87376C17.3418 9.47853 17.346 8.85248 16.9568 8.46322C16.5691 8.07559 15.9309 8.07559 15.5432 8.46322L10.58 13.4264L8.45678 11.3032C8.06915 10.9156 7.43085 10.9156 7.04322 11.3032C6.65559 11.6909 6.65559 12.3291 7.04322 12.7168L9.87322 15.5468C10.0596 15.7331 10.3129 15.84 10.58 15.84C10.8471 15.84 11.1004 15.7331 11.2868 15.5468L16.9568 9.87678ZM2.25 12C2.25 6.62807 6.62807 2.25 12 2.25C17.3719 2.25 21.75 6.62807 21.75 12C21.75 17.3719 17.3719 21.75 12 21.75C6.62807 21.75 2.25 17.3719 2.25 12Z"
                                                            fill="white" stroke="#284C4E" stroke-width="0.5"></path>
                                                    </svg>
                                                    <span class="px-2">
                                                        Plagiarism Checker</span>
                                                </span>
                                            </div>
                                            <div class="py-1">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 26 26" fill="none">
                                                        <circle cx="14" cy="14" r="10" fill="#284C4E"></circle>
                                                        <circle cx="13" cy="13" r="10" fill="#284C4E"></circle>
                                                        <path
                                                            d="M16.9568 9.87678L16.9568 9.8768L16.9597 9.87376C17.3418 9.47853 17.346 8.85248 16.9568 8.46322C16.5691 8.07559 15.9309 8.07559 15.5432 8.46322L10.58 13.4264L8.45678 11.3032C8.06915 10.9156 7.43085 10.9156 7.04322 11.3032C6.65559 11.6909 6.65559 12.3291 7.04322 12.7168L9.87322 15.5468C10.0596 15.7331 10.3129 15.84 10.58 15.84C10.8471 15.84 11.1004 15.7331 11.2868 15.5468L16.9568 9.87678ZM2.25 12C2.25 6.62807 6.62807 2.25 12 2.25C17.3719 2.25 21.75 6.62807 21.75 12C21.75 17.3719 17.3719 21.75 12 21.75C6.62807 21.75 2.25 17.3719 2.25 12Z"
                                                            fill="white" stroke="#284C4E" stroke-width="0.5"></path>
                                                    </svg>
                                                    <span class="px-2">
                                                        80+ Languages</span>
                                                </span>
                                            </div>
                                            <div class="py-1">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 26 26" fill="none">
                                                        <circle cx="14" cy="14" r="10" fill="#284C4E"></circle>
                                                        <circle cx="13" cy="13" r="10" fill="#284C4E"></circle>
                                                        <path
                                                            d="M16.9568 9.87678L16.9568 9.8768L16.9597 9.87376C17.3418 9.47853 17.346 8.85248 16.9568 8.46322C16.5691 8.07559 15.9309 8.07559 15.5432 8.46322L10.58 13.4264L8.45678 11.3032C8.06915 10.9156 7.43085 10.9156 7.04322 11.3032C6.65559 11.6909 6.65559 12.3291 7.04322 12.7168L9.87322 15.5468C10.0596 15.7331 10.3129 15.84 10.58 15.84C10.8471 15.84 11.1004 15.7331 11.2868 15.5468L16.9568 9.87678ZM2.25 12C2.25 6.62807 6.62807 2.25 12 2.25C17.3719 2.25 21.75 6.62807 21.75 12C21.75 17.3719 17.3719 21.75 12 21.75C6.62807 21.75 2.25 17.3719 2.25 12Z"
                                                            fill="white" stroke="#284C4E" stroke-width="0.5"></path>
                                                    </svg>
                                                    <span class="px-2">
                                                        80+ Languages</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END BOX -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="back-guarantee container border py-4 px-5 linear-gradient-bg" style="
                        border-bottom-left-radius: 10px;
                        border-bottom-right-radius: 10px;
                    ">
                    <div class="row align-items-center">
                        <div class="col-lg-3 col-md-5 col-sm-12">
                            <img src="/_public_assets/testImg/Money-Back-Guarantee-1.svg" width="270" height="270"
                                alt="Back-Guarantee img-fluid" />
                        </div>
                        <div class="col-lg-9 col-md-7 col-sm-12">
                            <h3>Our 100% No-Risk money back guarantee!</h3>
                            <p class="inter-text text-muted">
                                We’re excited to have you experience UltimateAI
                                Pro. Over the next 30 days, if UltimateAI isn’t
                                the best fit, simply reach out! We’ll happily
                                refund 100% of your money. No questions asked.
                            </p>
                            <img src="/_public_assets/testImg/payment2-1.webp" width="350" alt="payment cards" />
                        </div>
                    </div>
                </div>
            </section>
            <section class="faq-section my-5 linear-gradient-bg">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="faq-content col-lg-5 col-md-6 col-sm-12">
                            <div>
                                <span class="px-4 py-1 border rounded-pill me-3 my-2">FAQS</span>
                                <h2 class="my-2">Frequently asked questions</h2>
                                <p class="mt-3 text-muted p-0">
                                    Here are some questions we get all the time,
                                    rest<br />
                                    assured, you’re in good hands!
                                </p>
                            </div>
                            <div class="faq-image d-flex justify-content-end">
                                <img src="_public_assets/testImg/faq.webp" width="260" alt="faq image" />
                            </div>
                        </div>
                        <div class="faqs col-lg-7 col-md-6 col-sm-12">
                            <div class="accordion accordion-flush" id="accordionExample">
                                <!-- 1: coll1 -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <!--   data-bs-target="#coll1",  controls="coll1", id="coll1", aria-expanded="true"      -->
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#coll1" aria-expanded="true" aria-controls="coll1">
                                            <h5>
                                                What is Semantic HTML and how
                                                does it work?
                                            </h5>
                                        </button>
                                    </h2>
                                    <!-- show : by default Always open -->
                                    <div id="coll1" class="accordion-collapse collapse show"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            Semantic HTML refers to a method of
                                            coding where HTML markup is used to
                                            emphasise the meaning or semantics
                                            of the existing content.
                                        </div>
                                    </div>
                                </div>
                                <!-- 2: coll2 -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <!--       collapsed,   aria-expanded="false"   -->
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#coll2" aria-expanded="false"
                                            aria-controls="coll2">
                                            <h5>
                                                What do you know about the Box
                                                Sizing property?
                                            </h5>
                                        </button>
                                    </h2>
                                    <div id="coll2" class="accordion-collapse collapse"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            Semantic HTML refers to a method of
                                            coding where HTML markup is used to
                                            emphasise the meaning or semantics
                                            of the existing content.
                                        </div>
                                    </div>
                                </div>
                                <!-- 3: coll3 -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#coll3" aria-expanded="false"
                                            aria-controls="coll3">
                                            <h5>
                                                Define the ways in which you can
                                                hide an element using CSS.
                                            </h5>
                                        </button>
                                    </h2>
                                    <div id="coll3" class="accordion-collapse collapse"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            There are three ways to hide
                                            elements with CSS. display:none can
                                            hide the content so that it doesn’t
                                            get stored in the DOM.
                                            visibility:hidden adds an element to
                                            the DOM and occupies space. However,
                                            the user cannot see it.
                                            position:absolute makes the element
                                            appear outside the screen, not on
                                            the screen.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#coll4" aria-expanded="false"
                                            aria-controls="coll4">
                                            <h5>
                                                Define the ways in which you can
                                                hide an element using CSS.
                                            </h5>
                                        </button>
                                    </h2>
                                    <div id="coll4" class="accordion-collapse collapse"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            There are three ways to hide
                                            elements with CSS. display:none can
                                            hide the content so that it doesn’t
                                            get stored in the DOM.
                                            visibility:hidden adds an element to
                                            the DOM and occupies space. However,
                                            the user cannot see it.
                                            position:absolute makes the element
                                            appear outside the screen, not on
                                            the screen.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="latest-blog linear-gradient-bg">
                <div class="container py-5 my-5">
                    <div class="row align-items-center">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="banner-title d-flex justify-content-center mb-3">
                                <span class="px-4 py-1 border rounded-pill me-3">Resources</span>
                            </div>
                            <h2 class="text-center mb-3">Our latest blogs</h2>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 px-4">
                            <div class="pt-4">
                                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 pb-4">
                                    <!-- START BOX -->
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="border bg-white rounded-3">
                                            <div class="d-inline-flex rounded-3 mb-4" style="background: #f6f6f7">
                                                <img src="_public_assets/testImg/blog-2.jpg" style="
                                                        width: 100%;
                                                        border-top-left-radius: 8px;
                                                        border-top-right-radius: 8px;
                                                    " alt="" />
                                            </div>
                                            <a href="#">
                                                <h4 class="text-black pb-2 px-3">
                                                    How to Find Your
                                                    Competitors’ Keywords: 9
                                                    Practical Strategies
                                                </h4>
                                            </a>
                                            <p class="text-muted ps-3 pe-3">
                                                Lorem ipsum is placeholder text
                                                commonly used in the graphic,
                                                print, and publishing...
                                            </p>
                                            <p class="ps-3 pe-3 fw-semibold">
                                                May 30, 2024
                                            </p>
                                        </div>
                                    </div>
                                    <!-- END BOX -->
                                    <!-- START BOX -->
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="border bg-white rounded-3">
                                            <div class="d-inline-flex rounded-3 mb-4" style="background: #f6f6f7">
                                                <img src="_public_assets/testImg/blog-1.jpg" style="
                                                        width: 100%;
                                                        border-top-left-radius: 8px;
                                                        border-top-right-radius: 8px;
                                                    " alt="" />
                                            </div>
                                            <a href="#">
                                                <h4 class="text-black pb-2 px-3">
                                                    How to Find Your
                                                    Competitors’ Keywords: 9
                                                    Practical Strategies
                                                </h4>
                                            </a>
                                            <p class="text-muted ps-3 pe-3">
                                                Lorem ipsum is placeholder text
                                                commonly used in the graphic,
                                                print, and publishing...
                                            </p>
                                            <p class="ps-3 pe-3 fw-semibold">
                                                May 30, 2024
                                            </p>
                                        </div>
                                    </div>
                                    <!-- END BOX -->
                                    <!-- START BOX -->
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="border bg-white rounded-3">
                                            <div class="d-inline-flex rounded-3 mb-4" style="background: #f6f6f7">
                                                <img src="_public_assets/testImg/gpt.jpg" style="
                                                        width: 100%;
                                                        border-top-left-radius: 8px;
                                                        border-top-right-radius: 8px;
                                                    " alt="" />
                                            </div>
                                            <a href="#">
                                                <h4 class="text-black pb-2 px-3">
                                                    How to Find Your
                                                    Competitors’ Keywords: 9
                                                    Practical Strategies
                                                </h4>
                                            </a>
                                            <p class="text-muted ps-3 pe-3">
                                                Lorem ipsum is placeholder text
                                                commonly used in the graphic,
                                                print, and publishing...
                                            </p>
                                            <p class="ps-3 pe-3 fw-semibold">
                                                May 30, 2024
                                            </p>
                                        </div>
                                    </div>
                                    <!-- END BOX -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </MasterLayout>
</template>

<script>
import { Head, Link } from "@inertiajs/vue3";
import MasterLayout from "@/frontend/Layouts/MasterLayout.vue";
import BrandLogos from "@/frontend/Components/BrandLogos.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import { Carousel, Slide, Pagination, Navigation } from "vue3-carousel";
import "vue3-carousel/dist/carousel.css";

export default {
    components: {
        Head,
        Link,
        // Carousel,
        // Slide,
        Pagination,
        Navigation,
        MasterLayout,
        BrandLogos,
    },

    data() {
        return {
            user: this.$cookies.get("lxf-user"),
            token: this.$cookies.get("lxf-token"),
            features: [
                {
                    title: "User-Friendly Interface",
                    image: "/_public_assets/img/features-icons/user-friendly-interface.png",
                    description:
                        this.$page.props.app.name +
                        " is fully mobile, tablet, and desktop responsive.",
                },
                {
                    title: "Entry Management",
                    image: "/_public_assets/img/features-icons/entry-management.png",
                    description:
                        "For a more efficient workflow, view all of your leads in one location.",
                },
                {
                    title: "Spam Protection",
                    image: "/_public_assets/img/features-icons/spam-protection.png",
                    description:
                        "Spam submissions are immediately stopped by our clever captcha and anti-spam technology.",
                },
                {
                    title: "Easy to Embed",
                    image: "/_public_assets/img/features-icons/easy-to-embed.png",
                    description:
                        "You may quickly insert your forms into blog posts, web pages, sidebar widgets, the footer, etc.",
                },
                {
                    title: "Instant Notifications",
                    image: "/_public_assets/img/features-icons/instant-notifications.png",
                    description:
                        "With our fast form notification function for your team, respond to leads promptly.",
                },
                {
                    title: "Customizable Forms",
                    image: "/_public_assets/img/features-icons/customizable-forms.png",
                    description:
                        "By adding and organizing fields as needed, you can construct forms that are highly customized. Text, email, checkboxes, and other common field kinds include.",
                },
            ],
            tesimonials: [
                {
                    rating: 5,
                    text: "LeadX has completely transformed how we manage our customer interactions. The intuitive form builder made it easy to create custom forms that fit our needs perfectly. Our lead generation has increased by 40% since we started using LeadX!",
                    author: "Sarah J.",
                    image: "/_public_assets/testImg/Crystal Raminez.webp",
                    verified: true,
                },
                {
                    rating: 5,
                    text: "Thanks to LeadX, our workflow has become much more efficient. The pre-built form templates saved us a ton of time, and the mobile-friendly forms ensure our clients can reach us from any device. Truly a fantastic tool!",
                    author: "Emily R.",
                    image: "/_public_assets/testImg/Crystal Raminez.webp",
                    verified: true,
                },
                {
                    rating: 5,
                    text: "We've been using LeadX for a few months now, and it's been a game-changer for our business. The smart conditional logic feature allows us to personalize our forms, leading to higher engagement and better data collection. Highly recommend!",
                    author: "John D.",
                    image: "/_public_assets/testImg/Crystal Raminez.webp",
                    verified: true,
                },
            ],
            // tesimonials_slider_settings: {
            //     itemsToShow: 3,
            //     autoplay: 5000,
            //     wrapAround: true,
            // },
            // tesimonials_slider_breakpoints: {
            //     360: {
            //         itemsToShow: 1,
            //     },
            //     992: {
            //         itemsToShow: 3,
            //     },
            // },
            faqs: [
                {
                    question: "Who is " + this.$page.props.app.name + " for?",
                    answer:
                        "" +
                        this.$page.props.app.name +
                        " is ideal for business owners, bloggers, designers, developers, photographers, and anyone looking to create custom WordPress forms.",
                    show: false,
                },
                {
                    question:
                        "Do I need coding skills to use " +
                        this.$page.props.app.name +
                        "?",
                    answer:
                        "No, " +
                        this.$page.props.app.name +
                        " is designed to be user-friendly for anyone, regardless of coding skills.",
                    show: false,
                },
                {
                    question:
                        "What are the requirements to use " +
                        this.$page.props.app.name +
                        "?",
                    answer: "You need a WordPress site and basic knowledge of form creation.",
                    show: false,
                },
                {
                    question:
                        "Is " +
                        this.$page.props.app.name +
                        " translation-ready?",
                    answer:
                        "Yes, " +
                        this.$page.props.app.name +
                        " supports multiple languages and is fully translation-ready.",
                    show: false,
                },
                {
                    question:
                        "Will " +
                        this.$page.props.app.name +
                        " affect my website’s speed?",
                    answer:
                        "" +
                        this.$page.props.app.name +
                        " is optimized for performance and will not affect your website’s speed.",
                    show: false,
                },
            ],
            brands: [
                {
                    name: "Hostinger",
                    image: "/_public_assets/img/brands/hostinger.png",
                },
                {
                    name: "GoDaddy",
                    image: "/_public_assets/img/brands/godaddy.png",
                },
                {
                    name: "DreamHost",
                    image: "/_public_assets/img/brands/dreamhost.png",
                },
                {
                    name: "BlueHost",
                    image: "/_public_assets/img/brands/bluehost.png",
                },
                {
                    name: "HubSpot",
                    image: "/_public_assets/img/brands/hubspot.png",
                },
                {
                    name: "Zapier",
                    image: "/_public_assets/img/brands/zapier.png",
                },
                {
                    name: "SAP",
                    image: "/_public_assets/img/brands/sap.png",
                },
                {
                    name: "Hostinger",
                    image: "/_public_assets/img/brands/hostinger.png",
                },
                {
                    name: "GoDaddy",
                    image: "/_public_assets/img/brands/godaddy.png",
                },
                {
                    name: "DreamHost",
                    image: "/_public_assets/img/brands/dreamhost.png",
                },
                {
                    name: "BlueHost",
                    image: "/_public_assets/img/brands/bluehost.png",
                },
                {
                    name: "HubSpot",
                    image: "/_public_assets/img/brands/hubspot.png",
                },
                {
                    name: "Zapier",
                    image: "/_public_assets/img/brands/zapier.png",
                },
                {
                    name: "SAP",
                    image: "/_public_assets/img/brands/sap.png",
                },
            ],
            packages: [],
            contact_form: {
                name: "",
                email: "",
                subject: "",
                message: "",
            },
            errors: {},
            contactFormLoader: false,
            loader: false,
        };
    },

    methods: {
        priceFormat(price, symbol = true) {
            price = parseInt(price).toFixed(0);
            return symbol ? this.$page.props.currency_symbol + price : price;
        },

        faqsToggle(index) {
            this.faqs[index].show = !this.faqs[index].show;
        },

        async getPackages() {
            await axios
                .get(route("api.guest.get.packages"), {
                    params: {
                        orderby: "sort",
                        order: "ASC",
                        status: "active",
                        limit: 3,
                    },
                    headers: {
                        "Content-Type": "application/json",
                    },
                })
                .then((response) => {
                    let $response = response.data;
                    this.packages = $response.data;
                })
                .catch((error) => {
                    console.log(error.response.data.message);
                });
        },

        async sendFeedBack() {
            this.contactFormLoader = true;
            this.errors = {};
            await axios
                .post(route("api.guest.create.feedback"), this.contact_form, {
                    headers: {
                        "Content-Type": "application/json",
                    },
                })
                .then((response) => {
                    this.contactFormLoader = false;
                    let $response = response.data;
                    this.contact_form.name = "";
                    this.contact_form.subject = "";
                    this.contact_form.email = "";
                    this.contact_form.message = "";
                    toast.success($response.message);
                })
                .catch((error) => {
                    this.contactFormLoader = false;
                    if (error.response.status == 422) {
                        for (const key in error.response.data.data) {
                            this.errors[key] = error.response.data.data[key][0];
                        }
                    }

                    toast.error(error.response.data.message);
                });
        },
    },

    mounted() { },

    created() {
        this.getPackages();
    },
};
</script>
