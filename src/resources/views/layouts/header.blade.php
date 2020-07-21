<!-- begin:: Header -->
<div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed ">

    <div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
        <div id="kt_header_menu"
             class="kt-header-menu kt-header-menu-mobile  kt-header-menu--layout-default ">
            <ul class="kt-menu__nav ">
                @if(Guardio::has('users.viewAny'))
                    <li class="kt-menu__item kt-menu__item--rel">
                        <a href="{{ route('users.index') }}" class="kt-menu__link">
                            <span class="kt-menu__link-text">Users</span>
                        </a>
                    </li>
                @endif
                @if(Guardio::has('clients.viewAny'))
                    <li class="kt-menu__item kt-menu__item--rel" data-ktmenu-submenu-toggle="click"
                        aria-haspopup="true">
                        <a href="{{ route('clients.index') }}" class="kt-menu__link">
                            <span class="kt-menu__link-text">Clients</span>
                        </a>
                    </li>
                @endif
                @if(Guardio::has('airports.viewAny'))
                    <li class="kt-menu__item kt-menu__item--rel" data-ktmenu-submenu-toggle="click"
                        aria-haspopup="true">
                        <a href="{{ route('airports.index') }}" class="kt-menu__link">
                            <span class="kt-menu__link-text">Airports</span>
                        </a>
                    </li>
                @endif
                @if(Guardio::has('aircrafts.viewAny'))
                    <li class="kt-menu__item kt-menu__item--rel" data-ktmenu-submenu-toggle="click"
                        aria-haspopup="true">
                        <a href="{{ route('aircrafts.index') }}" class="kt-menu__link">
                            <span class="kt-menu__link-text">Aircrafts</span>
                        </a>
                    </li>
                @endif
                @if(Guardio::has('loadsheets.viewAny'))
                    <li class="kt-menu__item kt-menu__item--rel" data-ktmenu-submenu-toggle="click"
                        aria-haspopup="true">
                        <a href="{{ route('loadsheets.index') }}" class="kt-menu__link">
                            <span class="kt-menu__link-text">Load sheets</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>

    <!-- end:: Header Menu -->

    <!-- begin:: Header Topbar -->
    @if(auth()->check())
    <div class="kt-header__topbar">

        <!--begin: User Bar -->
        <div class="kt-header__topbar-item kt-header__topbar-item--user">
            <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">
                <div class="kt-header__topbar-user">
                    <span class="kt-header__topbar-welcome kt-hidden-mobile">Hi,</span>
                    <span class="kt-header__topbar-username kt-hidden-mobile">{{ auth()->user()->family }}</span>
                    <img class="kt-hidden" alt="Pic" src="images/users/300_25.jpg"/>

                    <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
                    <span
                        class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold">{{ substr(auth()->user()->family,0,1) }}</span>
                </div>
            </div>
            <div
                class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">

                <!--begin: Head -->
                <div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x"
                     style="background-image: url(images/misc/bg-1.jpg)">
                    <div class="kt-user-card__avatar">
                        <img class="kt-hidden" alt="Pic" src="images/users/300_25.jpg"/>

                        <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
                        <span
                            class="kt-badge kt-badge--lg kt-badge--rounded kt-badge--bold kt-font-success">{{ substr(auth()->user()->family,0,1) }}</span>
                    </div>
                    <div class="kt-user-card__name">
                        {{ auth()->user()->name }} {{ auth()->user()->family }}
                    </div>
                    <div class="kt-user-card__badge">
                        <span class="btn btn-success btn-sm btn-bold btn-font-md">{{ auth()->user()->Serial }}</span>
                    </div>
                </div>

                <!--end: Head -->

                <!--begin: Navigation -->
                <div class="kt-notification">
                    <a href="{{ route('users.profile') }}"
                       class="kt-notification__item">
                        <div class="kt-notification__item-icon">
                            <i class="flaticon2-calendar-3 kt-font-success"></i>
                        </div>
                        <div class="kt-notification__item-details">
                            <div class="kt-notification__item-title kt-font-bold">
                                My Profile
                            </div>
                            <div class="kt-notification__item-time">
                                Account settings and more
                            </div>
                        </div>
                    </a>
                    <div class="kt-notification__custom kt-space-between">
                        <form action="{{ route('logout') }}" method="post">
                            {{ csrf_field() }}
                            <input type="submit"
                                   class="btn btn-label btn-label-brand btn-sm btn-bold" value="Sign Out">
                        </form>
                    </div>
                </div>

                <!--end: Navigation -->
            </div>
        </div>

        <!--end: User Bar -->
    </div>
    @endif

    <!-- end:: Header Topbar -->
</div>
<!-- end:: Header -->
