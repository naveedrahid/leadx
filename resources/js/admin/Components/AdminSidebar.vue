<template>
    <aside class="left-sidebar">
        <div>
            <div class="brand-logo d-flex align-items-center justify-content-between">
                <Link :href="route('app.admin.dashboard')" class="text-nowrap logo-img">
                    <img src="/_app_assets/images/logos/logo.png" class="dark-logo" width="170" :alt="$page.props.app.name" />
                </Link>
                <div class="close-btn d-lg-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                    <i class="ti ti-x fs-8 text-muted"></i>
                </div>
            </div>
            <nav class="sidebar-nav scroll-sidebar" data-simplebar>
                <ul id="sidebarnav">
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Main Navigation</span>
                    </li>
                    <li class="sidebar-item">
                        <Link :href="route('app.admin.dashboard')" class="sidebar-link gap-2" :class="{ 
                            'active': route().current('app.admin.dashboard') 
                        }">
                            <span><i class="ti ti-dashboard"></i></span>
                            <span class="hide-menu">Dashboard</span>
                        </Link>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link has-arrow gap-2" :class="{ 
                            'active': route().current('app.admin.customer.*')
                        }">
                            <span class="d-flex"><i class="ti ti-users"></i></span>
                            <span class="hide-menu">Customers</span>
                        </a>
                        <ul class="collapse first-level" :class="{ 
                            'in': route().current('app.admin.customer.*')
                        }">
                            <li class="sidebar-item">
                                <Link :href="route('app.admin.customer.index')" class="sidebar-link gap-2" :class="{ 
                                    'active': route().current('app.admin.customer.*') && !route().current('app.admin.customer.create')
                                }">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle fs-3"></i>
                                    </div>
                                    <span class="hide-menu">All Customers</span>
                                </Link>
                            </li>
                            <li class="sidebar-item">
                                <Link :href="route('app.admin.customer.create')" class="sidebar-link gap-2" :class="{ 
                                    'active': route().current('app.admin.customer.create') 
                                }">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle fs-3"></i>
                                    </div>
                                    <span class="hide-menu">Add New</span>
                                </Link>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link has-arrow gap-2" :class="{ 
                            'active': route().current('app.admin.package.*')
                        }">
                            <span class="d-flex">
                                <i class="ti ti-package"></i>
                            </span>
                            <span class="hide-menu">Packages</span>
                        </a>
                        <ul class="collapse first-level" :class="{ 
                            'in': route().current('app.admin.package.*')
                        }">
                            <li class="sidebar-item">
                                <Link :href="route('app.admin.package.index')" class="sidebar-link gap-2" :class="{ 
                                    'active': route().current('app.admin.package.index') 
                                }">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle fs-3"></i>
                                    </div>
                                    <span class="hide-menu">All Packages</span>
                                </Link>
                            </li>
                            <li class="sidebar-item">
                                <Link :href="route('app.admin.package.create')" class="sidebar-link gap-2" :class="{ 
                                    'active': route().current('app.admin.package.create') 
                                }">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle fs-3"></i>
                                    </div>
                                    <span class="hide-menu">Add New</span>
                                </Link>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link has-arrow gap-2" :class="{ 
                            'active': route().current('app.admin.coupon.*')
                        }">
                            <span class="d-flex">
                                <i class="ti ti-ticket"></i>
                            </span>
                            <span class="hide-menu">Coupons</span>
                        </a>
                        <ul class="collapse first-level" :class="{ 
                            'in': route().current('app.admin.coupon.*')
                        }">
                            <li class="sidebar-item">
                                <Link :href="route('app.admin.coupon.index')" class="sidebar-link gap-2" :class="{ 
                                    'active': route().current('app.admin.coupon.index') 
                                }">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle fs-3"></i>
                                    </div>
                                    <span class="hide-menu">All Coupons</span>
                                </Link>
                            </li>
                            <li class="sidebar-item">
                                <Link :href="route('app.admin.coupon.create')" class="sidebar-link gap-2" :class="{ 
                                    'active': route().current('app.admin.coupon.create') 
                                }">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle fs-3"></i>
                                    </div>
                                    <span class="hide-menu">Add New</span>
                                </Link>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link has-arrow gap-2" :class="{ 
                            'active': route().current('app.admin.plugin.*')
                        }">
                            <span class="d-flex"><i class="ti ti-bolt"></i></span>
                            <span class="hide-menu">Plugins</span>
                        </a>
                        <ul class="collapse first-level" :class="{ 
                            'in': route().current('app.admin.plugin.*')
                        }">
                            <li class="sidebar-item">
                                <Link :href="route('app.admin.plugin.index')" class="sidebar-link gap-2" :class="{ 
                                    'active': route().current('app.admin.plugin.index') 
                                }">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle fs-3"></i>
                                    </div>
                                    <span class="hide-menu">All Plugins</span>
                                </Link>
                            </li>
                            <li class="sidebar-item">
                                <Link :href="route('app.admin.plugin.create')" class="sidebar-link gap-2" :class="{ 
                                    'active': route().current('app.admin.plugin.create') 
                                }">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle fs-3"></i>
                                    </div>
                                    <span class="hide-menu">Add New</span>
                                </Link>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <Link :href="route('app.admin.keyword.index')" class="sidebar-link gap-2" :class="{ 
                            'active': route().current('app.admin.keyword.index') 
                        }">
                            <span><i class="ti ti-key"></i></span>
                            <span class="hide-menu">Keywords</span>
                        </Link>
                    </li>
                    <li class="sidebar-item">
                        <Link :href="route('app.admin.feedback.index')" class="sidebar-link gap-2" :class="{ 
                            'active': route().current('app.admin.feedback.index') 
                        }">
                            <span><i class="ti ti-mail"></i></span>
                            <span class="hide-menu">Feedback</span>
                        </Link>
                    </li>
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">PerBuild Form Templates</span>
                    </li>
                    <li class="sidebar-item">
                        <Link :href="route('app.admin.template.index')" class="sidebar-link gap-2" :class="{ 
                            'active': route().current('app.admin.template.index') 
                        }">
                            <span><i class="ti ti-folder"></i></span>
                            <span class="hide-menu">Templates</span>
                        </Link>
                    </li>
                    <li class="sidebar-item">
                        <Link :href="route('app.admin.category.index')" class="sidebar-link gap-2" :class="{ 
                            'active': route().current('app.admin.category.index') 
                        }">
                            <span><i class="ti ti-tags"></i></span>
                            <span class="hide-menu">Categories</span>
                        </Link>
                    </li>
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Setting</span>
                    </li>
                    <li class="sidebar-item">
                        <Link :href="route('app.admin.account_setting')" class="sidebar-link gap-2" :class="{ 
                            'active': route().current('app.admin.account_setting') 
                        }">
                            <span><i class="ti ti-user-cog"></i></span>
                            <span class="hide-menu">Account</span>
                        </Link>
                    </li>
                    <li class="sidebar-item">
                        <Link :href="route('app.admin.change_password')" class="sidebar-link gap-2" :class="{ 
                            'active': route().current('app.admin.change_password') 
                        }">
                            <span><i class="ti ti-asterisk"></i></span>
                            <span class="hide-menu">Change Password</span>
                        </Link>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link gap-2" @click="logout()">
                            <span><i class="ti ti-logout"></i></span>
                            <span class="hide-menu">Logout</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';

export default {
    components: {
        Link
    },

    data() {
        return {
        };
    },

    methods: {
        logout() {
            this.$emit('logout');
        }
    }
}
</script>