<?php

use App\Models\SystemSetting;

$system = SystemSetting::first();

?>

<!-- Sidenav Menu Start -->
<div class="sidenav-menu">

    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard.index') }}"
       class="logo">

        <span class="logo-light">

            <span class="logo-lg">

                <img style="height: auto; max-height: 60px; width: auto;"
                     src="{{ asset($system->logo ?? '/backend/assets/images/logo-dark.png') }}"
                     alt="logo">

            </span>

            <span class="logo-sm">

                <img style="height: auto; max-height: 69px; width: auto;"
                     src="{{ asset($system->dark_logo ?? '/backend/assets/images/logo-dark.png') }}"
                     alt="small logo">

            </span>

        </span>

        <span class="logo-dark">

            <span class="logo-lg">

                <img style="height: auto; max-height: 60px; width: auto;"
                     src="{{ asset($system->dark_logo ?? '/backend/assets/images/logo-dark.png') }}"
                     alt="dark logo">

            </span>

            <span class="logo-sm">

                <img src="{{ asset($system->dark_logo ?? '/backend/assets/images/logo-dark.png') }}"
                     alt="small logo">

            </span>

        </span>

    </a>

    <!-- Sidebar Hover Menu Toggle Button -->
    <button class="button-sm-hover">
        <i class="ri-circle-line align-middle"></i>
    </button>

    <!-- Sidebar Menu Toggle Button -->
    <button class="sidenav-toggle-button">
        <i class="ri-menu-5-line fs-20"></i>
    </button>

    <!-- Full Sidebar Menu Close Button -->
    <button class="button-close-fullsidebar">
        <i class="ti ti-x align-middle"></i>
    </button>

    <div data-simplebar>

        <!-- Sidebar Menu -->
        <ul class="side-nav">

            {{-- DASHBOARD --}}
            <li class="side-nav-item {{ request()->routeIs('admin.dashboard.*') ? 'active' : '' }}">

                <a href="{{ route('admin.dashboard.index') }}"
                   class="side-nav-link">

                    <span class="menu-icon">
                        <i class="ti ti-layout-dashboard"></i>
                    </span>

                    <span class="menu-text">
                        {{ __('menu.dashboard') }}
                    </span>

                </a>

            </li>

            {{-- USERS --}}
            <li class="side-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">

                <a href="{{ route('admin.users.index') }}"
                   class="side-nav-link">

                    <span class="menu-icon">
                        <i class="ti ti-users-group"></i>
                    </span>

                    <span class="menu-text">
                        {{ __('menu.users') }}
                    </span>

                </a>

            </li>

            {{-- TRIPS --}}
            <li class="side-nav-item {{ request()->routeIs('admin.trips.*') ? 'active' : '' }}">

                <a href="{{ route('admin.trips.index') }}"
                   class="side-nav-link">

                    <span class="menu-icon">
                        <i class="ti ti-route-2"></i>
                    </span>

                    <span class="menu-text">
                        Trips
                    </span>

                </a>

            </li>

            {{-- BOOKINGS --}}
            <li class="side-nav-item {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">

                <a href="{{ route('admin.bookings.index') }}"
                   class="side-nav-link">

                    <span class="menu-icon">
                        <i class="ti ti-ticket"></i>
                    </span>

                    <span class="menu-text">
                        Bookings
                    </span>

                </a>

            </li>

            {{-- CHATS --}}
            <li class="side-nav-item {{ request()->routeIs('admin.chats.*') ? 'active' : '' }}">

                <a href="{{ route('admin.chats.index') }}"
                   class="side-nav-link">

                    <span class="menu-icon">
                        <i class="ti ti-message-circle"></i>
                    </span>

                    <span class="menu-text">
                        Chats
                    </span>

                </a>

            </li>

            {{-- REVIEWS --}}
            <li class="side-nav-item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">

                <a href="{{ route('admin.reviews.index') }}"
                   class="side-nav-link">

                    <span class="menu-icon">
                        <i class="ti ti-star"></i>
                    </span>

                    <span class="menu-text">
                        Reviews
                    </span>

                </a>

            </li>

            {{-- ANALYTICS --}}
            {{-- <li class="side-nav-item {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">

                <a href="{{ route('admin.analytics.index') }}"
                   class="side-nav-link">

                    <span class="menu-icon">
                        <i class="ti ti-chart-bar"></i>
                    </span>

                    <span class="menu-text">
                        Analytics
                    </span>

                </a>

            </li> --}}

            {{-- REVENUE --}}
            {{-- <li class="side-nav-item {{ request()->routeIs('admin.revenue.*') ? 'active' : '' }}">

                <a href="{{ route('admin.revenue.index') }}"
                   class="side-nav-link">

                    <span class="menu-icon">
                        <i class="ti ti-cash-banknote"></i>
                    </span>

                    <span class="menu-text">
                        Revenue
                    </span>

                </a>

            </li> --}}

            {{-- REPORTS --}}
            <li class="side-nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">

                <a href="{{ route('admin.reports.index') }}"
                   class="side-nav-link">

                    <span class="menu-icon">
                        <i class="ti ti-flag-3"></i>
                    </span>

                    <span class="menu-text">
                        Reports
                    </span>

                </a>

            </li>

            {{-- FAQ --}}
            <li class="side-nav-item {{ request()->routeIs('admin.faq.*') ? 'active' : '' }}">

                <a href="{{ route('admin.faq.index') }}"
                   class="side-nav-link">

                    <span class="menu-icon">
                        <i class="ti ti-help-circle"></i>
                    </span>

                    <span class="menu-text">
                        {{ __('menu.faq') }}
                    </span>

                </a>

            </li>

            {{-- PAGES --}}
            <li class="side-nav-item {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">

                <a href="{{ route('admin.pages.index') }}"
                   class="side-nav-link">

                    <span class="menu-icon">
                        <i class="ti ti-files"></i>
                    </span>

                    <span class="menu-text">
                        {{ __('menu.pages') }}
                    </span>

                </a>

            </li>

            {{-- SUBSCRIBERS --}}
            <li class="side-nav-item {{ request()->routeIs('admin.subscribers.*') ? 'active' : '' }}">

                <a href="{{ route('admin.subscribers.index') }}"
                   class="side-nav-link">

                    <span class="menu-icon">
                        <i class="ti ti-mail-star"></i>
                    </span>

                    <span class="menu-text">
                        {{ __('menu.subscribers') }}
                    </span>

                </a>

            </li>

            {{-- SECTION --}}
            <li class="side-nav-title mt-2">
                {{ __('menu.more') }}
            </li>

            {{-- SETTINGS --}}
            <li class="side-nav-item">

                <a data-bs-toggle="collapse"
                   href="#sidebarSettings"
                   aria-expanded="false"
                   aria-controls="sidebarSettings"
                   class="side-nav-link">

                    <span class="menu-icon">
                        <i class="ti ti-settings-cog"></i>
                    </span>

                    <span class="menu-text">
                        {{ __('menu.settings') }}
                    </span>

                    <span class="menu-arrow"></span>

                </a>

                <div class="collapse"
                     id="sidebarSettings">

                    <ul class="sub-menu">

                        {{-- SYSTEM SETTINGS --}}
                        <li class="side-nav-item">

                            <a href="{{ route('admin.dashboard.system.settings') }}"
                               class="side-nav-link">

                                <span class="menu-text">
                                    {{ __('menu.system_setting') }}
                                </span>

                            </a>

                        </li>

                        {{-- SMTP --}}
                        <li class="side-nav-item">

                            <a href="{{ route('admin.credentials.edit', 'smtp') }}"
                               class="side-nav-link">

                                <span class="menu-text">
                                    {{ __('menu.smtp_setting') }}
                                </span>

                            </a>

                        </li>

                    </ul>

                </div>

            </li>

        </ul>

        <div class="clearfix"></div>

    </div>

</div>
<!-- Sidenav Menu End -->