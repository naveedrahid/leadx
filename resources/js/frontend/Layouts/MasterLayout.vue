<template>
    <div id="home"></div>
    <header id="header" class="main-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <a :href="route('home')" class="logo">
                            <img src="/_public_assets/img/logos/logo.png" :alt="$page.props.app.name" class="img-fluid">
                        </a>
                        <button class="menu-btn d-lg-none d-block"><i class="bi bi-list"></i></button>
                    </div>
                </div>
                <div class="col-md-8 d-lg-block d-none">
                    <div class="d-flex align-items-center justify-content-between gap-5">
                        <nav class="main-menu">
                            <template v-if="route().current('home')">
                                <Link :href="route('home')" class="active">Home</Link>
                                <Link :href="route('featured')">Features</Link>
                                <a href="#pricing" class="nav-link">Pricing</a>
                                <Link :href="route('customer.reviews')">Testimonials</Link>
                                <Link :href="route('contact')">Contact</Link>
                                <!-- <a href="#home" class="nav-link active">Home</a> -->
                                <!-- <a href="#features" class="nav-link">Features</a> -->
                                <!-- <a href="#contact" class="nav-link">Contact</a> -->
                            </template>
                            <template v-else>
                                <Link :href="route('home')">Home</Link>
                                <Link :href="route('featured')">Features</Link>
                                <Link :href="route('home') +'#pricing'" :class="{
                                    'active': route().current('pricing')
                                }">Pricing</Link>
                                <Link :href="route('customer.reviews')">Testimonials</Link>
                                <Link :href="route('contact')">Contact</Link>
                            </template>
                        </nav>
                        <div class="other-nav-links">
                            <template v-if="user">
                                <a :href="route('app.customer.dashboard')" class="button button-primary">My Account</a>
                                <button class="button button-secondary" @click="logout()">Logout</button>
                            </template>
                            <template v-else>
                                <a :href="route('app.auth.login')" class="button button-primary text-primary bg-white">Login</a>
                                <a href="javascript:;" class="button button-primary">Get LeadX</a>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="aside-menu">
        <div class="aside-menu-box">
            <button type="button" class="aside-menu-close"><i class="bi bi-x-lg"></i></button>
            <div class="aside-logo-wrap">
                <a :href="route('home')" class="aside-logo">
                    <img src="/_public_assets/img/logos/logo.png" :alt="$page.props.app.name" class="img-fluid">
                </a>
            </div>
            <div class="aside-main-menu">
                <nav class="aside-nav-menu">
                    <template v-if="route().current('home')">
                        <Link :href="route('home')">Home</Link>
                        <Link :href="route('featured')">Features</Link>
                        <a href="#pricing" class="nav-link">Pricing</a>
                        <Link :href="route('contact')">Contact</Link>
                    </template>
                    <template v-else>
                        <Link :href="route('home')">Home</Link>
                        <Link :href="route('featured')">Features</Link>
                        <Link :href="route('pricing')" :class="{
                            'active': route().current('pricing')
                        }">Pricing</Link>
                        <Link :href="route('contact')">Contact</Link>
                    </template>
                </nav>
                <div class="aside-nav-links d-flex flex-column gap-2">
                    <template v-if="user">
                        <a :href="route('app.customer.dashboard')" class="button button-block button-primary">My
                            Account</a>
                        <button class="button button-block button-secondary" @click="logout()">Logout</button>
                    </template>
                    <template v-else>
                        <a :href="route('app.auth.login')" class="button button-block button-primary">Login</a>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- BEGIN::MAIN CONTENT -->
    <slot></slot>
    <!-- END::MAIN CONTENT -->

    <footer class="mainFooter" style="background-color: #008744;">
        <div class="footer-banner container mb-5">
            <div class="row justify-content-center align-items-center">
                <div class="footer-banner-col col-lg-12 col-md-12 col-sm-12 rounded-5 px-5 pt-3"
                    style="background-color: #f5f9f9;">
                    <div class="container px-4">
                        <div class="row align-items-center">
                            <div class="col-lg-8 col-md-7 col-sm-12 col-first">
                                <span class="colFirstTxt">Now Available on Mobile!</span>
                                <h2 class="my-4">Grab the LeadXForms Mobile App <br><span style="color: #008543;">Your
                                        Leads, On-the-Go!</span></h2>
                                <div class="py-2 d-flex gap-3">
                                    <img src="/_public_assets/testImg/Button (1).png" width="150" class="img-fluid">
                                    <img src="/_public_assets/testImg/Button.png" width="150" class="img-fluid">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 d-flex justify-content-end">
                                <img src="/_public_assets/testImg/Mobile.png" class="img-fluid footer-mobile"
                                    alt="Shape Man img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container footerMenu">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12 d-flex flex-column">
                    <div class="footer-logo">
                        <a :href="route('home')">
                            <img src="/_public_assets/img/logos/logo.png" :alt="$page.props.app.name" class="img-fluid">
                        </a>
                    </div>
                    <div class="footer-info">
                        <p class="text-white">Design and publish forms faster, smoother, and better — with the most
                            flexible
                            WordPress form builder!</p>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="footer-list">
                        <h5 class="text-white mb-4">Quick Links</h5>
                        <ul class="unstyled-list">
                            <li>
                                <Link :href="route('home')" class="text-white">Home</Link>
                            </li>
                            <li>
                                <Link :href="route('featured')" class="text-white">Features</Link>
                            </li>
                            <li><a :href="route('home') +'#pricing'" class="nav-link text-white">Pricing</a></li>
                            <li>
                                <Link :href="route('customer.reviews')" class="text-white">Testimonials</Link>
                            </li>
                            <li>
                                <Link :href="route('contact')" class="text-white">Contact</Link>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="footer-list">
                        <h5 class="text-white mb-4">Helpful Links</h5>
                        <ul class="unstyled-list">
                            <li>
                                <Link :href="route('privacy_policy')" class="text-white">Privacy Policy</Link>
                            </li>
                            <li>
                                <Link :href="route('terms_conditions')" class="text-white">Terms &amp; Conditions</Link>
                            </li>
                            <li>
                                <Link :href="route('contact')" class="text-white">Contact Us</Link>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="footer-list">
                        <h5 class="text-white mb-4">Contact</h5>
                        <ul class="unstyled-list infoIcon">
                            <li><a class="text-white" :href="'mailto:' + $page.props.app.support_mail">{{
                                    $page.props.app.support_mail}}</a>
                            </li>
                            <li><a href="#" class="text-white">
                                    Level 4/260 Queen St, Brisbane City QLD
                                    4000
                                </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row pt-3 pb-3 mt-4 border-top justify-content-between align-items-center ">
                <div class="col-lg-12 col-md-12 col-sm-12 p-0">
                    <div class="footer-rights d-flex align-items-center justify-content-center">
                        <p class="m-0 text-white">© Copyright @ 2024-2025 LeadXForms</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- <footer class="main-footer overflow-hidden">
    <div class="container">
        <div class="footer-top">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-lg-7 col-md-11">
                            <div class="footer-info">
                                <a :href="route('home')" class="footer-logo">
                                    <img src="/_public_assets/img/logos/logo3.png" :alt="$page.props.app.name" class="img-fluid">
                                </a>
                                <p>The Unlimited solution for creating custom forms and flows to connect users and enhance engagement and broaden your online presence.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="footer-head">
                                <h3>Useful Links</h3>
                            </div>
                            <ul class="footer-nav">
                                <li><Link :href="route('home')">Home</Link></li>
                                <li><Link :href="route('pricing')">Pricing</Link></li>
                            </ul>
                        </div>
                        <div class="col-md-7">
                            <div class="footer-head">
                                <h3>Contact Us</h3>
                            </div>
                            <div class="footer-contact-info">
                                <p><span>Email:</span> <a :href="'mailto:' + $page.props.app.support_mail">{{ $page.props.app.support_mail }}</a></p>
                                <p><span>Address:</span> Level 4/260 Queen St, Brisbane City QLD 4000</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="copyright">
                &copy; Copyright @ 2024-2025 <Link :href="route('home')" class="text-primary">{{ $page.props.app.name }}</Link>
            </div>
        </div>
    </div>
</footer> -->

    <div id="scrollToTop" :class="[
        'scrollToTopBtn', { 'fade-in': isVisible, 'fade-out': !isVisible }
    ]" @click.prevent="scrollToTop"><i class="bi bi-arrow-up-short"></i></div>

    <Loader :toggle="loader"></Loader>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import Loader from '@/frontend/Components/Loader.vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import Swal from 'sweetalert2/dist/sweetalert2.js';
import 'sweetalert2/dist/sweetalert2.css';

export default {
    props: {
        loader: false
    },

    components: {
        Link,
        Loader
    },

    data() {
        return {
            user: this.$cookies.get("lxf-user"),
            token: this.$cookies.get("lxf-token"),
            isVisible: false
        };
    },

    methods: {
        logout() {
            let self = this;
            Swal.fire({
                html: 'Are you sure you want to signout your account?',
                icon: "warning",
                buttonsStyling: false,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: 'Yes, Logout!',
                cancelButtonText: 'No, return',
                customClass: {
                    htmlContainer: 'fs-4',
                    actions: 'm-0 mt-5 gap-3',
                    confirmButton: "btn btn-danger m-0",
                    cancelButton: "btn btn-active-light m-0"
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    self.logoutAccount();
                }
            });
        },

        async logoutAccount() {
            this.$page.props.loader = true;
            await axios.post(route('api.auth.logout'), {}, {
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": "Bearer " + this.token
                }
            }).then((response) => {
                let $response = response.data;
                this.$cookies.set('lxf-success-msg', $response.message, 10);
                this.$cookies.remove('lxf-token');
                this.$cookies.remove('lxf-user');
                location = route('app.auth.login');
                this.$page.props.loader = false;
            }).catch((error) => {
                this.$page.props.loader = false;
                if (error.response.status == 401) {
                    this.$cookies.remove('lxf-token');
                    this.$cookies.remove('lxf-user');
                    this.$inertia.visit(route('app.auth.login'));
                }
                toast.error(error.response.data.message);
            });
        },

        async isUserLogin() {
            await axios.get(route('api.auth.get.user'), {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token
                }
            }).then((response) => {
                let $response = response.data;
                this.user = $response.data.user;
                this.$cookies.set('lxf-user', this.user, moment(moment()).add(1, 'years').diff(moment(), 'seconds'));
            }).catch((error) => {
                if (error.response) {
                    if (error.response.status == 401 || error.response.status == 403 || error.response.status == 404) {
                        this.$cookies.set('lxf-error-msg', error.response.data.message, 10);
                        this.$cookies.remove('lxf-token');
                        this.$cookies.remove('lxf-user');
                        this.$inertia.visit(route('home'));
                    }
                }
            });
        },

        handleScroll() {
            this.isVisible = window.scrollY > 100;
        },

        scrollToTop() {
            window.scrollTo({ top: 0, behavior: "smooth" });
        }
    },

    mounted() {
        if (this.token != null) {
            this.isUserLogin();
        }

        let successMsg = this.$cookies.get('lxf-success-msg');
        if (successMsg != null) {
            toast.success(successMsg);
            this.$cookies.remove('lxf-success-msg');
        }

        $("header").before($("header").clone().addClass("fixed-header"));
        $(window).on("scroll", () => $("body").toggleClass("down", ($(window).scrollTop() > 100)));

        $('.menu-btn').on('click', (e) => {
            e.preventDefault();
            $('.aside-menu').addClass('show');
        });

        $('.aside-menu-close').on('click', (e) => {
            e.preventDefault();
            $('.aside-menu').removeClass('show');
        });

        $(window).on("load scroll", function () {
            let position = $(window).scrollTop() + 200;
            $('.nav-link').each(function () {
                let link = $(this);
                if (!link.attr('href')) return;
                let section = $(link.attr('href'));
                if (section.length) {
                    if (
                        position >= section.offset().top &&
                        position <= section.offset().top + section.outerHeight()
                    ) {
                        link.addClass("active");
                    } else {
                        link.removeClass("active");
                    }

                    if (position <= 200) {
                        $('.nav-link[href="#home"]').addClass('active');
                    }
                }
            });
        });

        $('.nav-link').on('click', function () {
            const targetId = $(this).attr('href').substring(1);
            const activeLink = $(`a[href="#${targetId}"]`);

            $('.nav-link').removeClass('active');
            activeLink.addClass('active');
        });

        window.addEventListener("scroll", this.handleScroll);
    },

    beforeDestroy() {
        window.removeEventListener("scroll", this.handleScroll);
    }
}
</script>

<style>
@import '/public/_public_assets/vendor/bootstrap/css/bootstrap.min.css';
@import '/public/_public_assets/vendor/bootstrap-icons/bootstrap-icons.css';
@import '/public/_app_assets/css/style.min.css';
@import '/public/_public_assets/css/style.css';
@import '/public/_app_assets/css/main.css';
</style>