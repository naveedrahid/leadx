<template>

    <Head>
        <title>Best WordPress Form Builder Plugin for Lead Generation</title>
        <meta name="description"
            content="Create high-converting WordPress forms with LeadX Forms. Drag-and-drop builder, spam protection, SMTP, custom redirects & more. Try it free today!" />
    </Head>
    <MasterLayout :loader="loader">
        <section class="hero-section overflow-hidden" id="hero">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-10 col-md-8">
                        <div class="hero-content text-center">
                            <h1>Build High-Converting WordPress Forms <span class="text-primary">in-Minutes</span> with
                                <span class="text-primary">{{ $page.props.app.name }}</span>
                            </h1>
                            <div class="row justify-content-center">
                                <div class="col-lg-10">
                                    <p>Create high-converting forms in minutes - no coding needed. LeadX Forms makes it
                                        easy to capture leads, grow your business, and automate communication.</p>
                                    <!-- <p>The Unlimited solution for creating custom forms and flows to connect users and
                                        enhance engagement and broaden your online presence.</p> -->
                                    <div class="my-5 banner_btn__mobile">
                                        <a href="#pricing" class="button me-xl-2 me-lg-2 me-md-2 me-0 button-s2 w-auto button-primary">
                                            Get {{ $page.props.app.name }} Now →</a>
                                        <Link :href="route('featured')" class="ms-xl-2 ms-lg-2 ms-md-2 ms-0 leadX-Ulink">
                                        See All Features →</Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-6">
                        <div class="hero-image text-center">
                            <img src="/_public_assets/img/welcome-to-leadxforms.png"
                                :alt="'Welcome To' + $page.props.app.name" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- <section class="hero-section overflow-hidden" id="hero">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-12 col-md-9 col-lg-9">
                    <div class="hero__wrapper text-center">
                        <div class="hero__info__box">
                            <span>UltimateAI</span>
                            <span>#1 ai text to audio platform</span>
                        </div>
                        <div class="hero-content">
                            <h1>Welcome To {{ $page.props.app.name }}</h1>
                            <p>The Unlimited solution for creating custom forms and flows to connect users and enhance engagement and broaden your online presence.</p>
                            <div class="mt-5">
                                <a href="#pricing" class="button button-s2 button-primary me-lg-2 me-xl-2 me-md-2 me-0">Buy Now</a>
                                <a href="javascript:;" class="button button-s2  button-outline ms-lg-2 ms-xl-2 ms-md-2 ms-0">Explore All Tools</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="hero-image mt-5 pt-5">
                        <img src="/_app_assets/images/banner/tts.png" :alt="'Welcome To' + $page.props.app.name" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section> -->

        <!-- BEGIN::BRAND LOGOs COMPONENT -->
        <BrandLogos></BrandLogos>
        <!-- END::BRAND LOGOs COMPONENT -->

        <section class="main-section features-section overflow-hidden" id="features">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-8">
                        <div class="main-head text-center mb-2">
                            <h4 class="subheading">{{ $page.props.app.name }} Features</h4>
                            <h2 class="heading">Why <span class="text-primary">{{ $page.props.app.name }}</span> Is Your
                                New Favorite WordPress Form Plugin</h2>
                        </div>
                        <p class="text-center mb-5">LeadXForms is a powerful WordPress form plugin that lets you build
                            high-converting forms - no coding needed. With SMTP, spam protection, 2000+ templates, and
                            responsive design, it’s built for speed, security, and simplicity.</p>
                    </div>
                </div>
                <div class="features-area" v-if="features.length > 0">
                    <div class="row g-4">
                        <div v-for="(feature, index) in features" :key="index" class="col-lg-4 col-md-6 equal-height">
                            <div class="feature-box">
                                <div class="feature-icon-box">
                                    <img :src="feature.image" :alt="feature.title" class="feature-icon">
                                    <h3 class="feature-title">{{ feature.title }}</h3>
                                    <p>{{ feature.description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5 text-center banner_btn__mobile">
                    <a href="#pricing" class="button me-xl-2 me-lg-2 me-md-2 me-0 button-s2 w-auto button-primary">
                        Get {{ $page.props.app.name }} Now →</a>
                    <Link :href="route('featured')" class="ms-xl-2 ms-lg-2 ms-md-2 ms-0 leadX-Ulink">
                    See All Features →</Link>
                </div>
            </div>
        </section>
        <section class="main-section overflow-hidden d-none">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-5 col-md-6 col-sm-12">
                        <img src="/_public_assets/testImg/Assistants.png" class="img-fluid rounded-top" alt="">
                    </div>
                    <div class="col-lg-7 col-md-6 col-sm-12 ps-4">
                        <h2 class="heading companion_mobile">Fully customizable AI companion - Anytime you need</h2>
                        <p>Conversational experiences that 3x Efficiency with even better quality.</p>
                        <div class="icon-content pt-4 companion_box__mobile">
                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <div class="pb-3 d-flex gap-3">
                                        <div class="icon">
                                            <span>
                                                <i class="rounded-3 d-inline-flex bg-black p-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                                        viewBox="0 0 24 24" fill="none">
                                                        <path
                                                            d="M22 22H2C1.59 22 1.25 21.66 1.25 21.25C1.25 20.84 1.59 20.5 2 20.5H22C22.41 20.5 22.75 20.84 22.75 21.25C22.75 21.66 22.41 22 22 22Z"
                                                            fill="white"></path>
                                                        <path
                                                            d="M9.75 4V22H14.25V4C14.25 2.9 13.8 2 12.45 2H11.55C10.2 2 9.75 2.9 9.75 4Z"
                                                            fill="white"></path>
                                                        <path opacity="0.4"
                                                            d="M3 10V22H7V10C7 8.9 6.6 8 5.4 8H4.6C3.4 8 3 8.9 3 10Z"
                                                            fill="white"></path>
                                                        <path opacity="0.4"
                                                            d="M17 15V22H21V15C21 13.9 20.6 13 19.4 13H18.6C17.4 13 17 13.9 17 15Z"
                                                            fill="white"></path>
                                                    </svg>
                                                </i>
                                            </span>
                                        </div>
                                        <div class="icon-content">
                                            <h5 class="icon-title text-black mb-0">Real-Time Data with GPT-4</h5>
                                            <p class="pt-2 inter-text">Lorem ipsum is a placeholder text commonly used
                                                to demo.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <div class="pb-3 d-flex gap-3">
                                        <div class="icon">
                                            <span>
                                                <i class="rounded-3 d-inline-flex bg-black p-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                                        viewBox="0 0 24 24" fill="none">
                                                        <path opacity="0.4"
                                                            d="M12 17V19.38C12 21.25 11.25 22 9.37 22H4.62C2.75 22 2 21.25 2 19.38V14.63C2 12.75 2.75 12 4.62 12H7V14.37C7 16.25 7.75 17 9.62 17H12Z"
                                                            fill="white"></path>
                                                        <path opacity="0.6"
                                                            d="M17 12V14.37C17 16.25 16.25 17 14.37 17H9.62C7.75 17 7 16.25 7 14.37V9.62C7 7.75 7.75 7 9.62 7H12V9.37C12 11.25 12.75 12 14.62 12H17Z"
                                                            fill="white"></path>
                                                        <path
                                                            d="M22 4.62V9.37C22 11.25 21.25 12 19.37 12H14.62C12.75 12 12 11.25 12 9.37V4.62C12 2.75 12.75 2 14.62 2H19.37C21.25 2 22 2.75 22 4.62Z"
                                                            fill="white"></path>
                                                    </svg>
                                                </i>
                                            </span>
                                        </div>
                                        <div class="icon-content">
                                            <h5 class="icon-title text-black mb-0">Customizations</h5>
                                            <p class="pt-2 inter-text">Lorem ipsum is a placeholder text commonly used
                                                to demo.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <div class="pb-3 d-flex gap-3">
                                        <div class="icon">
                                            <span>
                                                <i class="rounded-3 d-inline-flex bg-black p-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                                        viewBox="0 0 24 24" fill="none">
                                                        <path
                                                            d="M22 22H2C1.59 22 1.25 21.66 1.25 21.25C1.25 20.84 1.59 20.5 2 20.5H22C22.41 20.5 22.75 20.84 22.75 21.25C22.75 21.66 22.41 22 22 22Z"
                                                            fill="white"></path>
                                                        <path
                                                            d="M9.75 4V22H14.25V4C14.25 2.9 13.8 2 12.45 2H11.55C10.2 2 9.75 2.9 9.75 4Z"
                                                            fill="white"></path>
                                                        <path opacity="0.4"
                                                            d="M3 10V22H7V10C7 8.9 6.6 8 5.4 8H4.6C3.4 8 3 8.9 3 10Z"
                                                            fill="white"></path>
                                                        <path opacity="0.4"
                                                            d="M17 15V22H21V15C21 13.9 20.6 13 19.4 13H18.6C17.4 13 17 13.9 17 15Z"
                                                            fill="white"></path>
                                                    </svg>
                                                </i>
                                            </span>
                                        </div>
                                        <div class="icon-content">
                                            <h5 class="icon-title text-black mb-0">Personalized Chat Experience</h5>
                                            <p class="pt-2 inter-text">Lorem ipsum is a placeholder text commonly used
                                                to demo.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <div class="pb-3 d-flex gap-3">
                                        <div class="icon">
                                            <span>
                                                <i class="rounded-3 d-inline-flex bg-black p-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                                        viewBox="0 0 24 24" fill="none">
                                                        <path
                                                            d="M22 22H2C1.59 22 1.25 21.66 1.25 21.25C1.25 20.84 1.59 20.5 2 20.5H22C22.41 20.5 22.75 20.84 22.75 21.25C22.75 21.66 22.41 22 22 22Z"
                                                            fill="white"></path>
                                                        <path
                                                            d="M9.75 4V22H14.25V4C14.25 2.9 13.8 2 12.45 2H11.55C10.2 2 9.75 2.9 9.75 4Z"
                                                            fill="white"></path>
                                                        <path opacity="0.4"
                                                            d="M3 10V22H7V10C7 8.9 6.6 8 5.4 8H4.6C3.4 8 3 8.9 3 10Z"
                                                            fill="white"></path>
                                                        <path opacity="0.4"
                                                            d="M17 15V22H21V15C21 13.9 20.6 13 19.4 13H18.6C17.4 13 17 13.9 17 15Z"
                                                            fill="white"></path>
                                                    </svg>
                                                </i>
                                            </span>
                                        </div>
                                        <div class="icon-content">
                                            <h5 class="icon-title text-black mb-0">Easy, Flexible Setup</h5>
                                            <p class="pt-2 inter-text">Lorem ipsum is a placeholder text commonly used
                                                to demo.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="section-link">
                                <a href="#" class="leadX-Ulink">Explore Integrations →</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <section class="main-section whychooseus-section bg-light-green overflow-hidden d-none">
            <div class="container">
                <div class="row justify-content-between align-items-center pb-5">
                    <div class="col-lg-5 col-md-6 col-sm-12">
                        <h2 class="heading">Transform AI ideas to SaasS Platform</h2>
                        <p class="border-bottom border-dark pb-3 mb-4">Enjoy premium AI at budget-friendly prices
                            :sparkles:</p>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="pb-3 d-flex gap-3">
                                <div class="icon">
                                    <span>
                                        <i class="rounded-pill d-inline-flex bg-white p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                viewBox="0 0 32 32" fill="none">
                                                <g opacity="0.12">
                                                    <path
                                                        d="M1.33594 25.0667C1.33594 24.6953 1.33594 24.5096 1.34416 24.3527C1.50488 21.286 3.95522 18.8356 7.02192 18.6749C7.17882 18.6667 7.36453 18.6667 7.73594 18.6667H13.6026C13.974 18.6667 14.1597 18.6667 14.3166 18.6749C17.3833 18.8356 19.8337 21.286 19.9944 24.3527C20.0026 24.5096 20.0026 24.6953 20.0026 25.0667C20.0026 25.3143 20.0026 25.4381 19.9971 25.5427C19.89 27.5872 18.2564 29.2207 16.2119 29.3279C16.1073 29.3333 15.9835 29.3333 15.7359 29.3333H5.6026C5.355 29.3333 5.23119 29.3333 5.12659 29.3279C3.08212 29.2207 1.44857 27.5872 1.34142 25.5427C1.33594 25.4381 1.33594 25.3143 1.33594 25.0667Z"
                                                        fill="#005E54"></path>
                                                    <path
                                                        d="M16.0026 8.00001C16.0026 10.9455 13.6148 13.3333 10.6693 13.3333C7.72375 13.3333 5.33594 10.9455 5.33594 8.00001C5.33594 5.05449 7.72375 2.66667 10.6693 2.66667C13.6148 2.66667 16.0026 5.05449 16.0026 8.00001Z"
                                                        fill="#005E54"></path>
                                                </g>
                                                <path
                                                    d="M20.0026 13.3333C22.9481 13.3333 25.3359 10.9455 25.3359 8.00001C25.3359 5.05449 22.9481 2.66667 20.0026 2.66667M22.6693 29.3333H26.4026C26.6502 29.3333 26.774 29.3333 26.8786 29.3279C28.9231 29.2207 30.5566 27.5872 30.6638 25.5427C30.6693 25.4381 30.6693 25.3143 30.6693 25.0667V25.0667C30.6693 24.6953 30.6693 24.5096 30.661 24.3527C30.5003 21.286 28.05 18.8356 24.9833 18.6749C24.8264 18.6667 24.6407 18.6667 24.2693 18.6667H22.6693M16.0026 8.00001C16.0026 10.9455 13.6148 13.3333 10.6693 13.3333C7.72375 13.3333 5.33594 10.9455 5.33594 8.00001C5.33594 5.05449 7.72375 2.66667 10.6693 2.66667C13.6148 2.66667 16.0026 5.05449 16.0026 8.00001ZM5.6026 29.3333H15.7359C15.9835 29.3333 16.1073 29.3333 16.2119 29.3279C18.2564 29.2207 19.89 27.5872 19.9971 25.5427C20.0026 25.4381 20.0026 25.3143 20.0026 25.0667V25.0667C20.0026 24.6953 20.0026 24.5096 19.9944 24.3527C19.8337 21.286 17.3833 18.8356 14.3166 18.6749C14.1597 18.6667 13.974 18.6667 13.6026 18.6667H7.73594C7.36453 18.6667 7.17882 18.6667 7.02192 18.6749C3.95522 18.8356 1.50488 21.286 1.34416 24.3527C1.33594 24.5096 1.33594 24.6953 1.33594 25.0667V25.0667C1.33594 25.3143 1.33594 25.4381 1.34142 25.5427C1.44857 27.5872 3.08212 29.2207 5.12659 29.3279C5.23119 29.3333 5.355 29.3333 5.6026 29.3333Z"
                                                    stroke="#005E54" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                            </svg>
                                        </i>
                                    </span>
                                </div>
                                <div class="icon-content">
                                    <h5 class="icon-title text-dark">Easily collaborate with team members</h5>
                                    <p class="pt-2 inter-text ">Lorem ipsum is placeholder text commonly used in the
                                        graphics and publishing industries.</p>
                                </div>
                            </div>
                        </div>
                        <div class="section-link">
                            <a class="" href="#">Explore Integrations →</a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 ps-4">
                        <img src="/_public_assets/testImg/user-management.png" class="img-fluid rounded-top" alt="">
                    </div>
                </div>
                <div class="features-area">
                    <div class="row g-4">
                        <div class="col-lg-3 col-md-6 equal-height">
                            <div class="feature-box">
                                <div class="feature-icon-box">
                                    <svg class="feature-icon" xmlns="http://www.w3.org/2000/svg" width="35px"
                                        height="35px" viewBox="0 0 24 24" fill="none">
                                        <rect x="18" y="9" width="4" height="4" rx="2" transform="rotate(90 18 9)"
                                            fill="#2A4157" fill-opacity="0.24" stroke="#222222" stroke-width="1.2">
                                        </rect>
                                        <rect x="18" y="17" width="4" height="4" rx="2" transform="rotate(90 18 17)"
                                            fill="#2A4157" fill-opacity="0.24" stroke="#222222" stroke-width="1.2">
                                        </rect>
                                        <rect x="3" y="7" width="4" height="4" rx="2" transform="rotate(-90 3 7)"
                                            fill="#2A4157" fill-opacity="0.24" stroke="#222222" stroke-width="1.2">
                                        </rect>
                                        <path
                                            d="M5 8V15C5 16.8856 5 17.8284 5.58579 18.4142C6.17157 19 7.11438 19 9 19H14"
                                            stroke="#222222" stroke-width="1.2"></path>
                                        <path
                                            d="M5 7V7C5 8.88562 5 9.82843 5.58579 10.4142C6.17157 11 7.11438 11 9 11H14"
                                            stroke="#222222" stroke-width="1.2"></path>
                                    </svg>

                                    <h3 class="feature-title">User-Friendly</h3>
                                    <p>LeadXForms is fully mobile, tablet, and desktop responsive.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 equal-height">
                            <div class="feature-box">
                                <div class="feature-icon-box">
                                    <svg class="feature-icon" xmlns="http://www.w3.org/2000/svg" width="35px"
                                        height="35px" viewBox="0 0 24 24" fill="none">
                                        <rect x="18" y="9" width="4" height="4" rx="2" transform="rotate(90 18 9)"
                                            fill="#2A4157" fill-opacity="0.24" stroke="#222222" stroke-width="1.2">
                                        </rect>
                                        <rect x="18" y="17" width="4" height="4" rx="2" transform="rotate(90 18 17)"
                                            fill="#2A4157" fill-opacity="0.24" stroke="#222222" stroke-width="1.2">
                                        </rect>
                                        <rect x="3" y="7" width="4" height="4" rx="2" transform="rotate(-90 3 7)"
                                            fill="#2A4157" fill-opacity="0.24" stroke="#222222" stroke-width="1.2">
                                        </rect>
                                        <path
                                            d="M5 8V15C5 16.8856 5 17.8284 5.58579 18.4142C6.17157 19 7.11438 19 9 19H14"
                                            stroke="#222222" stroke-width="1.2"></path>
                                        <path
                                            d="M5 7V7C5 8.88562 5 9.82843 5.58579 10.4142C6.17157 11 7.11438 11 9 11H14"
                                            stroke="#222222" stroke-width="1.2"></path>
                                    </svg>

                                    <h3 class="feature-title">User-Friendly</h3>
                                    <p>LeadXForms is fully mobile, tablet, and desktop responsive.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 equal-height">
                            <div class="feature-box">
                                <div class="feature-icon-box">
                                    <svg class="feature-icon" xmlns="http://www.w3.org/2000/svg" width="35px"
                                        height="35px" viewBox="0 0 24 24" fill="none">
                                        <rect x="18" y="9" width="4" height="4" rx="2" transform="rotate(90 18 9)"
                                            fill="#2A4157" fill-opacity="0.24" stroke="#222222" stroke-width="1.2">
                                        </rect>
                                        <rect x="18" y="17" width="4" height="4" rx="2" transform="rotate(90 18 17)"
                                            fill="#2A4157" fill-opacity="0.24" stroke="#222222" stroke-width="1.2">
                                        </rect>
                                        <rect x="3" y="7" width="4" height="4" rx="2" transform="rotate(-90 3 7)"
                                            fill="#2A4157" fill-opacity="0.24" stroke="#222222" stroke-width="1.2">
                                        </rect>
                                        <path
                                            d="M5 8V15C5 16.8856 5 17.8284 5.58579 18.4142C6.17157 19 7.11438 19 9 19H14"
                                            stroke="#222222" stroke-width="1.2"></path>
                                        <path
                                            d="M5 7V7C5 8.88562 5 9.82843 5.58579 10.4142C6.17157 11 7.11438 11 9 11H14"
                                            stroke="#222222" stroke-width="1.2"></path>
                                    </svg>

                                    <h3 class="feature-title">User-Friendly</h3>
                                    <p>LeadXForms is fully mobile, tablet, and desktop responsive.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 equal-height">
                            <div class="feature-box">
                                <div class="feature-icon-box">
                                    <svg class="feature-icon" xmlns="http://www.w3.org/2000/svg" width="35px"
                                        height="35px" viewBox="0 0 24 24" fill="none">
                                        <rect x="18" y="9" width="4" height="4" rx="2" transform="rotate(90 18 9)"
                                            fill="#2A4157" fill-opacity="0.24" stroke="#222222" stroke-width="1.2">
                                        </rect>
                                        <rect x="18" y="17" width="4" height="4" rx="2" transform="rotate(90 18 17)"
                                            fill="#2A4157" fill-opacity="0.24" stroke="#222222" stroke-width="1.2">
                                        </rect>
                                        <rect x="3" y="7" width="4" height="4" rx="2" transform="rotate(-90 3 7)"
                                            fill="#2A4157" fill-opacity="0.24" stroke="#222222" stroke-width="1.2">
                                        </rect>
                                        <path
                                            d="M5 8V15C5 16.8856 5 17.8284 5.58579 18.4142C6.17157 19 7.11438 19 9 19H14"
                                            stroke="#222222" stroke-width="1.2"></path>
                                        <path
                                            d="M5 7V7C5 8.88562 5 9.82843 5.58579 10.4142C6.17157 11 7.11438 11 9 11H14"
                                            stroke="#222222" stroke-width="1.2"></path>
                                    </svg>

                                    <h3 class="feature-title">User-Friendly</h3>
                                    <p>LeadXForms is fully mobile, tablet, and desktop responsive.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
        <section class="main-section testimonials-section bg-light overflow-hidden" id="testimonials">
            <div class="container">
                <div class="main-head text-center">
                    <h4 class="subheading">Testimonials</h4>
                    <h2 class="heading">What Our Users Are Saying <br> About <span
                            class="text-primary">LeadXForms</span></h2>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-12 col-md-8 col-12">
                        <div class="testimonials-area" v-if="tesimonials.length > 0">
                            <carousel v-bind="tesimonials_slider_settings"
                                :breakpoints="tesimonials_slider_breakpoints">
                                <slide class="testimonial-slide" v-for="(tesimonial, index) in tesimonials"
                                    :key="index">
                                    <div class="testimonial-item">
                                        <div class="testimonial-rate">
                                            <i :class="{
                                                'bi bi-star-fill': tesimonial.rating >= 1,
                                                'bi bi-star': tesimonial.rating < 1,
                                            }"></i>
                                            <i :class="{
                                                'bi bi-star-fill': tesimonial.rating >= 2,
                                                'bi bi-star': tesimonial.rating < 2,
                                            }"></i>
                                            <i :class="{
                                                'bi bi-star-fill': tesimonial.rating >= 3,
                                                'bi bi-star': tesimonial.rating < 3,
                                            }"></i>
                                            <i :class="{
                                                'bi bi-star-fill': tesimonial.rating >= 4,
                                                'bi bi-star': tesimonial.rating < 4,
                                            }"></i>
                                            <i :class="{
                                                'bi bi-star-fill': tesimonial.rating == 5,
                                                'bi bi-star': tesimonial.rating < 5,
                                            }"></i>
                                        </div>
                                        <p>{{ tesimonial.text }}</p>
                                        <div class="testimonial-author">
                                            <h4 class="testimonial-author-name">{{ tesimonial.author }}</h4>
                                            <span class="testimonial-author-verified"
                                                v-if="tesimonial.verified">Verified Customer</span>
                                        </div>
                                    </div>
                                </slide>
                                <template #addons>
                                    <div class="testimonials-pagination">
                                        <pagination />
                                    </div>
                                </template>
                            </carousel>
                        </div>
                        <div class="text-center mt-5 banner_btn__mobile">
                            <Link href="#pricing" class="button button-s2 me-xl-2 me-lg-2 me-md-2 me-0 button-primary">Get
                            LeadX Froms Now →</Link>
                            <a :href="route('customer.reviews')" class="leadX-Ulink ms-xl-2 ms-lg-2 ms-md-2 ms-0">See All Reviews →</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="main-section pricing-section overflow-hidden" id="pricing">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-8">
                        <div class="main-head text-center pb-4">
                            <h2 class="heading">Check Out <span class="text-primary">Pricing</span></h2>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="pricing-area">
                            <template v-if="packages.length > 0">
                                <div class="row">
                                    <template v-for="(item, index) in packages" :key="index">
                                        <div class="col-lg-4 col-md-4 col-12 pricing-box-wrap" :class="{
                                            'pricing-featured': item.recommended
                                        }">
                                            <div class="pricing-box" :class="{
                                                'pricing-box-featured': item.recommended
                                            }">
                                                <span v-if="item.recommended"
                                                    class="pricing-box-featured_label">Featured Plan</span>
                                                <div class="pricing-box-head">
                                                    <h3 class="pricing-box-title">{{ item.title }}</h3>
                                                    <div class="pricing-box-price">
                                                        <div class="pricing-box-amount">{{ priceFormat(item.price) }}
                                                        </div>
                                                        <span class="pricing-box-duration">/ {{ item.duration_lifetime ?
                                                            'Lifetime' : item.format_duration }}</span>
                                                    </div>
                                                    <Link v-if="!user" :href="`${route('pricing')}?plan=${item.id}`"
                                                        class="button button-s2 button-primary button-block">Get Started
                                                    →
                                                    </Link>
                                                    <Link v-else-if="user.user_type === 'customer'"
                                                        :href="route('app.customer.subscription.billing')"
                                                        class="button button-s2 button-primary button-block">Upgrade →
                                                    </Link>
                                                </div>
                                                <div class="pricing-box-body" v-if="item.features">
                                                    <h4 class="pricing-box-title">Features</h4>
                                                    <ul class="pricing-box-features">
                                                        <li v-for="(feature, index) in JSON.parse(item.features)"
                                                            :key="index">{{ feature }}</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <template v-else>
                                <div class="notfound">No Packages Found</div>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center border-cst p-4 bg-light-green">
                    <div class="col-lg-3 col-md-5 col-sm-12">
                        <img src="/_public_assets/testImg/Money-Back-Guarantee-1.svg" width="250" height="250"
                            alt="Back-Guarantee img-fluid" />
                    </div>
                    <div class="col-lg-9 col-md-7 col-sm-12">
                        <h3 class="fw-bolder text-dark">Our 100% No-Risk money back guarantee!</h3>
                        <p class="inter-text">
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
        <section class="main-section blog-section bg-light overflow-hidden d-none" id="blog" style="display:none">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-8">
                        <div class="main-head text-center">
                            <h2 class="heading"><span class="text-primary">{{ $page.props.app.name }}</span> Blog &
                                Resources
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-12 col-md-8">
                        <div class="blog-area">
                            <div class="blog-posts-grid">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <article class="blog-post">
                                            <a href="#" class="blog-post-img">
                                                <img src="/_public_assets/img/dummy.png"
                                                    alt="Top 5 Tips for Creating High-Converting WordPress Forms"
                                                    class="img-fluid">
                                            </a>
                                            <div class="blog-post-content">
                                                <h3><a href="#">Top 5 Tips for Creating High-Converting WordPress
                                                        Forms</a></h3>
                                            </div>
                                        </article>
                                    </div>
                                    <div class="col-lg-4">
                                        <article class="blog-post">
                                            <a href="#" class="blog-post-img">
                                                <img src="/_public_assets/img/dummy.png"
                                                    alt="Why Every Business Needs a Custom WordPress Form Builder"
                                                    class="img-fluid">
                                            </a>
                                            <div class="blog-post-content">
                                                <h3><a href="#">Why Every Business Needs a Custom WordPress Form
                                                        Builder</a>
                                                </h3>
                                            </div>
                                        </article>
                                    </div>
                                    <div class="col-lg-4">
                                        <article class="blog-post">
                                            <a href="#" class="blog-post-img">
                                                <img src="/_public_assets/img/dummy.png"
                                                    alt="The Future of Online Forms: Trends to Watch in 2024"
                                                    class="img-fluid">
                                            </a>
                                            <div class="blog-post-content">
                                                <h3><a href="#">The Future of Online Forms: Trends to Watch in 2024</a>
                                                </h3>
                                            </div>
                                        </article>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="main-section faqs-section bg-light-green overflow-hidden" id="faqs">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-8">
                        <div class="main-head text-center">
                            <h4 class="subheading">Question & Answers</h4>
                            <h2 class="heading">Frequently <span class="text-primary">Asked</span> Questions</h2>
                        </div>
                    </div>
                </div>
                <div class="faqs-area">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div v-for="(faq, index) in faqs" :key="index" class="faq-item" :class="{
                                'show': faq.show,
                                'border-0': index + 1 == (faqs.length)
                            }">
                                <div class="faq-header">
                                    <button class="faq-button" @click.prevent="faqsToggle(index)">
                                        <h3>{{ faq.question }}</h3>
                                        <span class="faq-toggle-icon">
                                            <i :class="faq.show ? 'bi bi-chevron-down' : 'bi bi-chevron-right'"></i>
                                        </span>
                                    </button>
                                </div>
                                <div class="faq-body">
                                    <div class="faq-content">
                                        <p>{{ faq.answer }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="latest-blog main-section pb-250">
            <div class="container">
                <div class="main-head text-center">
                    <h4 class="subheading">Resources</h4>
                    <h2 class="heading">Our <span class="text-primary">latest</span> blogs</h2>
                </div>
                <div class="row align-items-center">
                    <div class="col-lg-12 col-md-12 col-sm-12 px-4">
                        <div class="pt-4">
                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 pb-4">
                                <!-- START BOX -->
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <div class="border bg-white rounded-3">
                                        <div class="d-inline-flex rounded-3 mb-4" style="background: #F6F6F7;">
                                            <img src="/_public_assets/testImg/blog-1.jpg"
                                                style="width: 100%; border-top-left-radius: 8px; border-top-right-radius: 8px;"
                                                alt="">
                                        </div>
                                        <a href="#">
                                            <h4 class="text-black pb-2 ps-3">How to Find Your Competitors' Keywords: 9
                                                Practical
                                                Strategies</h4>
                                        </a>
                                        <p class="text-muted ps-3 pe-3">Lorem ipsum is placeholder text commonly used in
                                            the
                                            graphic, print, and publishing...</p>
                                        <p class="ps-3 pe-3 fw-semibold">May 30, 2024</p>
                                    </div>
                                </div>
                                <!-- END BOX -->
                                <!-- START BOX -->
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <div class="border bg-white rounded-3">
                                        <div class="d-inline-flex rounded-3 mb-4" style="background: #F6F6F7;">
                                            <img src="/_public_assets/testImg/blog-2.jpg"
                                                style="width: 100%; border-top-left-radius: 8px; border-top-right-radius: 8px;"
                                                alt="">
                                        </div>
                                        <a href="#">
                                            <h4 class="text-black pb-2 ps-3">How to Find Your Competitors' Keywords: 9
                                                Practical
                                                Strategies</h4>
                                        </a>
                                        <p class="text-muted ps-3 pe-3">Lorem ipsum is placeholder text commonly used in
                                            the
                                            graphic, print, and publishing...</p>
                                        <p class="ps-3 pe-3 fw-semibold">May 30, 2024</p>
                                    </div>
                                </div>
                                <!-- END BOX -->
                                <!-- START BOX -->
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <div class="border bg-white rounded-3">
                                        <div class="d-inline-flex rounded-3 mb-4" style="background: #F6F6F7;">
                                            <img src="/_public_assets/testImg/gpt.jpg"
                                                style="width: 100%; border-top-left-radius: 8px; border-top-right-radius: 8px;"
                                                alt="">
                                        </div>
                                        <a href="#">
                                            <h4 class="text-black pb-2 ps-3">How to Find Your Competitors' Keywords: 9
                                                Practical
                                                Strategies</h4>
                                        </a>
                                        <p class="text-muted ps-3 pe-3">Lorem ipsum is placeholder text commonly used in
                                            the
                                            graphic, print, and publishing...</p>
                                        <p class="ps-3 pe-3 fw-semibold">May 30, 2024</p>
                                    </div>
                                </div>
                                <!-- END BOX -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </MasterLayout>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import MasterLayout from '@/frontend/Layouts/MasterLayout.vue';
import BrandLogos from '@/frontend/Components/BrandLogos.vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel';
import 'vue3-carousel/dist/carousel.css';

export default {
    components: {
        Head,
        Link,
        Carousel,
        Slide,
        Pagination,
        Navigation,
        MasterLayout,
        BrandLogos
    },
    props: {
        title: String,
        description: String
    },
    data() {
        return {
            user: this.$cookies.get("lxf-user"),
            token: this.$cookies.get("lxf-token"),
            features: [
                {
                    title: 'User-Friendly Interface',
                    image: '/_public_assets/img/features-icons/user-friendly-interface.png',
                    description: this.$page.props.app.name + '  is fully responsive and works seamlessly across mobile, tablet, and desktop devices.'
                },
                {
                    title: 'Entry Management',
                    image: '/_public_assets/img/features-icons/entry-management.png',
                    description: 'Easily view, track, manage, and export all form submissions from one centralized dashboard.'
                },
                {
                    title: 'Easy to Embed',
                    image: '/_public_assets/img/features-icons/easy-to-embed.png',
                    description: 'Instantly add your forms to pages, blog posts, sidebars, footers, and more with just a few clicks.'
                },
                {
                    title: 'Customizable Forms',
                    image: '/_public_assets/img/features-icons/customizable-forms.png',
                    description: 'Create fully customizable forms that match your brand and needs - no coding required.'
                },
                {
                    title: '2000+ Form Tamplates',
                    image: '/_public_assets/img/features-icons/customizable-forms.png',
                    description: 'Save time and streamline your workflow with our ready-to-use form templates.'
                },
                {
                    title: 'Styled Email Templates',
                    image: '/_public_assets/img/features-icons/instant-notifications.png',
                    description: 'Send beautifully formatted emails for each form response.'
                },
                {
                    title: 'Smart Spam Filters',
                    image: '/_public_assets/img/features-icons/instant-notifications.png',
                    description: 'LeadXForms uses smart spam filters to block bots and junk effortlessly, ensuring only genuine submissions reach your inbox.'
                },

                {
                    title: 'Email Notifications',
                    image: '/_public_assets/img/features-icons/instant-notifications.png',
                    description: 'With our fast form notification function for your team, respond to leads promptly.'
                },
                {
                    title: 'Custom Redirection',
                    image: '/_public_assets/img/features-icons/customizable-forms.png',
                    description: 'Send users to a thank-you page or a custom URL after submission.'
                },
                // {
                //     title: 'SMTP Integration',
                //     image: '/_public_assets/img/features-icons/easy-to-embed.png',
                //     description: 'Ensure reliable email delivery by connecting your SMTP provider.'
                // },

                // {
                //     title: 'Form Security',
                //     image: '/_public_assets/img/features-icons/easy-to-embed.png',
                //     description: 'Your data is protected with built-in spam filters, IP blocking, and CAPTCHA—so you get real leads, not bots.'
                // },
                // {
                //     title: 'Spam Protection',
                //     image: '/_public_assets/img/features-icons/spam-protection.png',
                //     description: 'Use Google reCAPTCHA, block specific keywords, and even block IPs.'
                // },
            ],
            tesimonials: [
                {
                    rating: 5,
                    text: "LeadX has completely transformed how we manage our customer interactions. The intuitive form builder made it easy to create custom forms that fit our needs perfectly. Our lead generation has increased by 40% since we started using LeadX!",
                    author: "Sarah J.",
                    verified: true
                },
                {
                    rating: 5,
                    text: "Thanks to LeadX, our workflow has become much more efficient. The pre-built form templates saved us a ton of time, and the mobile-friendly forms ensure our clients can reach us from any device. Truly a fantastic tool!",
                    author: "Emily R.",
                    verified: true
                },
                {
                    rating: 5,
                    text: "We've been using LeadX for a few months now, and it's been a game-changer for our business. The smart conditional logic feature allows us to personalize our forms, leading to higher engagement and better data collection. Highly recommend!",
                    author: "John D.",
                    verified: true
                }
            ],
            tesimonials_slider_settings: {
                itemsToShow: 6,
                autoplay: 5000,
                wrapAround: true
            },
            tesimonials_slider_breakpoints: {
                360: {
                    itemsToShow: 1,
                },
                992: {
                    itemsToShow: 3,
                }
            },
            faqs: [
                {
                    question: 'Who is ' + this.$page.props.app.name + ' for?',
                    answer:
                        '' + this.$page.props.app.name + ' is ideal for business owners, bloggers, designers, developers, photographers, and anyone looking to create custom WordPress forms.',
                    show: false,
                },
                {
                    question: 'Do I need coding skills to use ' + this.$page.props.app.name + '?',
                    answer:
                        'No, ' + this.$page.props.app.name + ' is designed to be user-friendly for anyone, regardless of coding skills.',
                    show: false,
                },
                {
                    question: 'What are the requirements to use ' + this.$page.props.app.name + '?',
                    answer:
                        'You need a WordPress site and basic knowledge of form creation.',
                    show: false,
                },
                {
                    question: 'Is ' + this.$page.props.app.name + ' translation-ready?',
                    answer:
                        'Yes, ' + this.$page.props.app.name + ' supports multiple languages and is fully translation-ready.',
                    show: false,
                },
                {
                    question: 'Will ' + this.$page.props.app.name + ' affect my website’s speed?',
                    answer:
                        '' + this.$page.props.app.name + ' is optimized for performance and will not affect your website’s speed.',
                    show: false,
                }
            ],
            packages: [],
            // contact_form: {
            //     name: '',
            //     email: '',
            //     subject: '',
            //     message: ''
            // },
            errors: {},
            contactFormLoader: false,
            loader: false
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
            await axios.get(route('api.guest.get.packages'), {
                params: {
                    orderby: 'sort',
                    order: 'ASC',
                    status: 'active',
                    limit: 3
                },
                headers: {
                    "Content-Type": "application/json"
                },
            }).then((response) => {
                let $response = response.data;
                this.packages = $response.data;
            }).catch((error) => {
                console.log(error.response.data.message);
            });
        },

        // async sendFeedBack() {
        //     this.contactFormLoader = true;
        //     this.errors = {};
        //     await axios.post(route('api.guest.create.feedback'), this.contact_form, {
        //         headers: {
        //             "Content-Type": "application/json"
        //         },
        //     }).then((response) => {
        //         this.contactFormLoader = false;
        //         let $response = response.data;
        //         this.contact_form.name = '';
        //         this.contact_form.subject = '';
        //         this.contact_form.email = '';
        //         this.contact_form.message = '';
        //         toast.success($response.message);
        //     }).catch((error) => {
        //         this.contactFormLoader = false;
        //         if (error.response.status == 422) {
        //             for (const key in error.response.data.data) {
        //                 this.errors[key] = error.response.data.data[key][0];
        //             }
        //         }

        //         toast.error(error.response.data.message);
        //     });
        // }
    },

    mounted() {
    },

    created() {
        this.getPackages();
    }
}
</script>
