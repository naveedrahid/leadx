<template>
    <aside class="left-sidebar">
        <div>
            <div class="brand-logo d-flex align-items-center justify-content-between">
                <Link :href="route('app.customer.dashboard')" class="text-nowrap logo-img">
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
                        <Link :href="route('app.customer.dashboard')" class="sidebar-link gap-2" :class="{
                            'active': route().current('app.customer.dashboard')
                        }">
                            <span><i class="ti ti-dashboard"></i></span>
                            <span class="hide-menu">Dashboard</span>
                        </Link>
                    </li>

                    <li class="sidebar-item">
                        <Link :href="route('app.customer.leads.index')" class="sidebar-link gap-2" :class="{
                            'active': route().current('app.customer.leads.index')
                        }">
                            <span><i class="ti ti-list"></i></span>
                            <span class="hide-menu">Leads</span>
                        </Link>
                    </li>
                    <li class="sidebar-item">
                        <Link :href="route('app.customer.lead-customers.index')" class="sidebar-link gap-2" :class="{
                            'active': route().current('app.customer.lead-customers.index')
                        }">
                            <span><i class="ti ti-users"></i></span>
                            <span class="hide-menu">Customers</span>
                        </Link>
                    </li>
                    <li class="sidebar-item">
                        <Link :href="route('app.customer.spam-leads.index')" class="sidebar-link gap-2" :class="{
                            'active': route().current('app.customer.spam-leads.index')
                        }">
                            <span><i class="ti ti-ban"></i></span>
                            <span class="hide-menu">Spam</span>
                        </Link>
                    </li>
                    <li class="sidebar-item">
                        <Link :href="route('app.customer.blocked-ip.index')" class="sidebar-link gap-2" :class="{
                            'active': route().current('app.customer.blocked-ip.index')
                        }">
                            <span><i class="ti ti-lock"></i></span>
                            <span class="hide-menu">IP Tracking</span>
                        </Link>
                    </li>

                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link has-arrow gap-2" :class="{
                            'active': route().current('app.customer.website.*')
                        }">
                            <span class="d-flex"><i class="ti ti-world"></i></span>
                            <span class="hide-menu">Websites</span>
                        </a>
                        <ul class="collapse first-level" :class="{
                            'in': route().current('app.customer.website.*')
                        }">
                            <li class="sidebar-item">
                                <Link :href="route('app.customer.website.index')" class="sidebar-link gap-2" :class="{
                                    'active': route().current('app.customer.website.index')
                                }">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle fs-3"></i>
                                    </div>
                                    <span class="hide-menu">All Websites</span>
                                </Link>
                            </li>
                            <li class="sidebar-item">
                                <Link :href="route('app.customer.website.create')" class="sidebar-link gap-2" :class="{
                                    'active': route().current('app.customer.website.create')
                                }">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle fs-3"></i>
                                    </div>
                                    <span class="hide-menu">Add New</span>
                                </Link>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Subscription</span>
                    </li>
                    <li class="sidebar-item">
                        <Link :href="route('app.customer.subscription.index')" class="sidebar-link gap-2" :class="{
                            'active': route().current('app.customer.subscription.index')
                        }">
                            <span><i class="ti ti-coin"></i></span>
                            <span class="hide-menu">Subscriptions</span>
                        </Link>
                    </li>
                    <li class="sidebar-item">
                        <Link :href="route('app.customer.subscription.billing')" class="sidebar-link gap-2" :class="{
                            'active': route().current('app.customer.subscription.billing')
                        }">
                            <span><i class="ti ti-credit-card"></i></span>
                            <span class="hide-menu">Billing Information</span>
                        </Link>
                    </li>
                    <li class="sidebar-item">
                        <Link :href="route('app.customer.subscription.billing.history')" class="sidebar-link gap-2" :class="{
                            'active': route().current('app.customer.subscription.billing.history')
                        }">
                            <span><i class="ti ti-receipt-2"></i></span>
                            <span class="hide-menu">Billing History</span>
                        </Link>
                    </li>



                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Plugin Integration</span>
                    </li>
                    <li class="sidebar-item">
                        <Link :href="route('app.customer.plugin.documentation')" class="sidebar-link gap-2" :class="{
                            'active': route().current('app.customer.plugin.documentation')
                        }">
                            <span><i class="ti ti-file"></i></span>
                            <span class="hide-menu">Plugin Guide</span>
                        </Link>
                    </li>
                    <li class="sidebar-item">
                        <Link :href="route('app.customer.subscription.license')" class="sidebar-link gap-2" :class="{
                            'active': route().current('app.customer.subscription.license')
                        }">
                            <span><i class="ti ti-file-certificate"></i></span>
                            <span class="hide-menu">Plugin License</span>
                        </Link>
                    </li>
                    <li class="sidebar-item">
                        <Link :href="route('app.customer.plugin.index')" class="sidebar-link gap-2" :class="{
                            'active': route().current('app.customer.plugin.index')
                        }">
                            <span><i class="ti ti-download"></i></span>
                            <span class="hide-menu">Your Downlaods</span>
                        </Link>
                    </li>


                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Account</span>
                    </li>
                    <li class="sidebar-item">
                        <Link :href="route('app.customer.account_setting')" class="sidebar-link gap-2" :class="{
                            'active': route().current('app.customer.account_setting')
                        }">
                            <span><i class="ti ti-user-cog"></i></span>
                            <span class="hide-menu">Account Setting</span>
                        </Link>
                    </li>
                    <li class="sidebar-item">
                        <Link :href="route('app.customer.change_password')" class="sidebar-link gap-2" :class="{
                            'active': route().current('app.customer.change_password')
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
