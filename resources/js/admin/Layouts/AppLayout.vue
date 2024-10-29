<template>
    <div class="preloader">
        <img src="/_app_assets/images/logos/x-icon.png" alt="loader" class="lds-ripple img-fluid" />
    </div>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
        <AdminSidebar @logout="logout" v-if="$page.props.is_admin"></AdminSidebar>
        <CustomerSidebar @logout="logout" v-if="$page.props.is_customer"></CustomerSidebar>

        <div class="body-wrapper">
            <AdminHeader @logout="logout" v-if="$page.props.is_admin"></AdminHeader>
            <CustomerHeader @logout="logout" v-if="$page.props.is_customer"></CustomerHeader>
            
            <slot></slot>
        </div>
    </div>

    <Loader :toggle="loader"></Loader>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import Loader from '@/admin/Components/Loader.vue';
import AdminHeader from '@/admin/Components/AdminHeader.vue';
import AdminSidebar from '@/admin/Components/AdminSidebar.vue';
import CustomerHeader from '@/admin/Components/CustomerHeader.vue';
import CustomerSidebar from '@/admin/Components/CustomerSidebar.vue';
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
        Loader,
        AdminHeader,
        AdminSidebar,
        CustomerHeader,
        CustomerSidebar,
    },

    data() {
        return {
            urlParams: new URLSearchParams(window.location.search),
            user: this.$cookies.get('lxf-user'),
            token: this.$cookies.get('lxf-token'),
            current_user: {},
            scripts: [
                { id: 'simpleBarJs', src: '/_app_assets/libs/simplebar/dist/simplebar.min.js', async: true, body: true },
            ]
        }
    },

    methods: {
        loadScript() {
            this.scripts.forEach(script => {
                let scriptTag = document.createElement("script");
                $('#'+script.id).remove();
                scriptTag.src = script.src;
                scriptTag.id = script.id;
                scriptTag.async = script.async;
                if(script.body == true) {
                    document.body.appendChild(scriptTag);
                } else {
                    document.head.appendChild(scriptTag);
                }
            });
        },

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
                    cancelButton: "btn m-0"
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$page.props.loader = true;
                    self.logoutAccount();
                }
            });
        },

        async logoutAccount() {
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
                this.$inertia.visit(route('app.auth.login'));
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
                this.current_user = $response.data.user;
                this.$cookies.set('lxf-user', this.current_user, moment(moment()).add(1, 'years').diff(moment(), 'seconds'));
            }).catch((error) => {
                if(error.response) {
                    this.$cookies.set('lxf-error-msg', error.response.data.message, 10);
                    this.$cookies.remove('lxf-token');
                    this.$cookies.remove('lxf-user');
                    this.$inertia.visit(route('app.auth.login'));
                }
            });
        }
    },

    created() {
        this.loadScript();
        this.isUserLogin();
    },

    mounted() {  
        $(function() {
            // Admin Panel settings
            $.fn.AdminSettings = function (settings) {
                var myid = this.attr("id");
                // General option for vertical header
                var defaults = {
                    Theme: true, // this can be true or false ( true means dark and false means light ),
                    SidebarType: "full", // You can change it full / mini-sidebar
                    SidebarPosition: false, // it can be true / false
                    HeaderPosition: true, // it can be true / false
                    BoxedLayout: false, // it can be true / false
                    ThemeBg: "aqua_theme",
                };
                var settings = $.extend({}, defaults, settings);
                // Attribute functions
                var AdminSettings = {
                    // Settings INIT
                    AdminSettingsInit: function () {
                    AdminSettings.ManageSidebarType();
                    AdminSettings.ManageSidebarPosition();
                    },

                    //****************************
                    // ManageThemeLayout functions
                    //****************************
                    ManageSidebarType: function () {
                    switch (settings.SidebarType) {
                        //****************************
                        // If the sidebar type has full
                        //****************************
                        case "full":
                        $("#" + myid).attr("data-sidebartype", "full");
                        //****************************
                        /* This is for the mini-sidebar if width is less then 1170*/
                        //****************************
                        var setsidebartype = function () {
                            var width =
                            window.innerWidth > 0 ? window.innerWidth : this.screen.width;
                            if (width < 1170) {
                                $("#main-wrapper").attr("data-sidebartype", "mini-sidebar");
                                $("#main-wrapper").addClass("mini-sidebar");
                            } else {
                                $("#main-wrapper").attr("data-sidebartype", "full");
                                $("#main-wrapper").removeClass("mini-sidebar");
                            }
                        };
                        $(window).ready(setsidebartype);
                        $(window).on("resize", setsidebartype);
                        //****************************
                        /* This is for sidebartoggler*/
                        //****************************
                        $(".sidebartoggler").on("click", function () {
                            $("#main-wrapper").toggleClass("mini-sidebar");
                            if ($("#main-wrapper").hasClass("mini-sidebar")) {
                            $(".sidebartoggler").prop("checked", !0);
                            $("#main-wrapper").attr("data-sidebartype", "mini-sidebar");
                            } else {
                            $(".sidebartoggler").prop("checked", !1);
                            $("#main-wrapper").attr("data-sidebartype", "full");
                            }
                        });
                        $(".sidebartoggler").on("click", function () {
                            $("#main-wrapper").toggleClass("show-sidebar");
                            $(".sidebartoggler i").toggleClass("text-dark");
                            $(".fullsidebar i").addClass("text-dark");
                        });
                        $(".fullsidebar").on("click", function () {
                            $("#main-wrapper").attr("data-sidebartype", "full");
                            $(".fullsidebar i").removeClass("text-dark");
                            $(".fullsidebar i").addClass("text-dark");
                            $(".sidebartoggler i").removeClass("text-dark");
                        });
                        break;

                        //****************************
                        // If the sidebar type has mini-sidebar
                        //****************************
                        case "mini-sidebar":
                        $("#" + myid).attr("data-sidebartype", "mini-sidebar");
                        //****************************
                        /* This is for sidebartoggler*/
                        //****************************
                        $(".sidebartoggler").on("click", function () {
                            $("#main-wrapper").toggleClass("mini-sidebar");
                            if ($("#main-wrapper").hasClass("mini-sidebar")) {
                            $(".sidebartoggler").prop("checked", !0);
                            $("#main-wrapper").attr("data-sidebartype", "full");
                            } else {
                            $(".sidebartoggler").prop("checked", !1);
                            $("#main-wrapper").attr("data-sidebartype", "mini-sidebar");
                            }
                        });
                        $(".sidebartoggler").on("click", function () {
                            $("#main-wrapper").toggleClass("show-sidebar");
                        });
                        break;

                        default:
                    }
                    },

                    //****************************
                    // ManageSidebarPosition functions
                    //****************************
                    ManageSidebarPosition: function () {
                    var sidebarposition = settings.SidebarPosition;
                    var headerposition = settings.HeaderPosition;
                    switch (settings.Layout) {
                        case "vertical":
                        if (sidebarposition == true) {
                            $("#" + myid).attr("data-sidebar-position", "fixed");
                        } else {
                            $("#" + myid).attr("data-sidebar-position", "absolute");
                        }
                        if (headerposition == true) {
                            $("#" + myid).attr("data-header-position", "fixed");
                        } else {
                            $("#" + myid).attr("data-header-position", "relative");
                        }
                        break;
                        case "horizontal":
                        if (sidebarposition == true) {
                            $("#" + myid).attr("data-sidebar-position", "fixed");
                        } else {
                            $("#" + myid).attr("data-sidebar-position", "absolute");
                        }
                        if (headerposition == true) {
                            $("#" + myid).attr("data-header-position", "fixed");
                        } else {
                            $("#" + myid).attr("data-header-position", "relative");
                        }
                        break;
                        default:
                    }
                    },
                };
                AdminSettings.AdminSettingsInit();
            };

            $("#main-wrapper").AdminSettings({
                ThemeBg: "purple_theme",
                SidebarType: "full",
            });

            $("#sidebarnav a").on("click", function (e) {
                if (!$(this).hasClass("active")) {
                    $("ul", $(this).parents("ul:first")).removeClass("in");
                    $("a", $(this).parents("ul:first")).removeClass("active");
                    $(this).next("ul").addClass("in");
                    $(this).addClass("active");
                } else if ($(this).hasClass("active")) {
                    $(this).removeClass("active");
                    $(this).parents("ul:first").removeClass("active");
                    $(this).next("ul").removeClass("in");
                }
            });

            $(window).scroll(function() {
                if ($(window).scrollTop() >= 60) {
                    $(".app-header").addClass("fixed-header");
                } else {
                    $(".app-header").removeClass("fixed-header");
                }
            });
            
            $(".full-width").on("click", function() {
                $(".container-fluid").addClass("mw-100");
                $(".full-width i").addClass("text-primary");
                $(".boxed-width i").removeClass("text-primary");
            });
            $(".boxed-width").on("click", function() {
                $(".container-fluid").removeClass("mw-100");
                $(".full-width i").removeClass("text-primary");
                $(".boxed-width i").addClass("text-primary");
            });
            
            $(".preloader").fadeOut();
        });        

        if(!route().current('auth.*')) {
            let successMsg = this.$cookies.get('lxf-success-msg');
            if(successMsg != null) {
                toast.success(successMsg);
                this.$cookies.remove('lxf-success-msg');
            }

            let errorMsg = this.$cookies.get('lxf-error-msg');
            if(errorMsg != null) {
                toast.error(errorMsg);
                this.$cookies.remove('lxf-error-msg');
            }

            let warningMsg = this.$cookies.get('lxf-warning-msg');
            if(warningMsg != null) {
                toast.warning(warningMsg);
                this.$cookies.remove('lxf-warning-msg');
            }
        }
    },

    beforeCreate() {
        if(!this.$cookies.get('lxf-token') || !this.$cookies.get('lxf-user')) {
            this.$cookies.remove('lxf-token');
            this.$cookies.remove('lxf-user');
            this.$inertia.visit(route('app.auth.login'));
        }

        if(this.$cookies.get('lxf-user')) {
            let user = this.$cookies.get('lxf-user');
            if (user.user_type == 'admin' && !route().current('app.admin.*')) {
                this.$inertia.visit(route('app.admin.dashboard'));
            } else if (user.user_type == 'customer' && !route().current('app.customer.*')) {
                this.$inertia.visit(route('app.customer.dashboard'));
            }
        }
    }
}
</script>

<style>
@import '/public/_app_assets/css/animate.min.css';
@import '/public/_app_assets/css/fontawesome/css/all.min.css';
@import '/public/_app_assets/css/style.min.css';
@import '/public/_app_assets/css/custom.css';
</style>