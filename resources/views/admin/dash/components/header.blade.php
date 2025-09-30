<!-- [ Sidebar Menu ] start -->
@include('admin.dash.components.sidebar')
<!-- [ Sidebar Menu ] end -->
<!-- [ Header Topbar ] start -->
<header class="pc-header">
    <div class="header-wrapper flex max-sm:px-[15px] px-[25px] grow"><!-- [Mobile Media Block] start -->
        <div class="me-auto pc-mob-drp">
            <ul class="inline-flex *:min-h-header-height *:inline-flex *:items-center">
                <!-- ======= Menu collapse Icon ===== -->
                <li class="pc-h-item pc-sidebar-collapse max-lg:hidden lg:inline-flex">
                    <a href="#" class="pc-head-link ltr:!ml-0 rtl:!mr-0" id="sidebar-hide">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
                <li class="pc-h-item pc-sidebar-popup lg:hidden">
                    <a href="#" class="pc-head-link ltr:!ml-0 rtl:!mr-0" id="mobile-collapse">
                        <i class="ti ti-menu-2 text-2xl leading-none"></i>
                    </a>
                </li>
                <li class="pc-h-item max-md:hidden md:inline-flex">
                    <form class="form-search relative">
                        <i class="search-icon absolute top-[14px] left-[15px]">
                            <svg class="pc-icon w-4 h-4">
                                <use xlink:href="#custom-search-normal-1"></use>
                            </svg>
                        </i>
                        <input type="search" class="form-control px-2.5 pr-3 pl-10 w-[198px] leading-none"
                            placeholder="Ctrl + K" />
                    </form>
                </li>
            </ul>
        </div>
        <!-- [Mobile Media Block end] -->
        <div class="ms-auto">
            <ul class="inline-flex *:min-h-header-height *:inline-flex *:items-center">
                <li class="dropdown pc-h-item">
                    <a class="pc-head-link dropdown-toggle me-0" data-pc-toggle="dropdown" href="course-dashboard.html#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <svg class="pc-icon">
                            <use xlink:href="#custom-sun-1"></use>
                        </svg>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
                        <a href="course-dashboard.html#!" class="dropdown-item" onclick="layout_change('dark')">
                            <svg class="pc-icon w-[18px] h-[18px]">
                                <use xlink:href="#custom-moon"></use>
                            </svg>
                            <span>Dark</span>
                        </a>
                        <a href="course-dashboard.html#!" class="dropdown-item" onclick="layout_change('light')">
                            <svg class="pc-icon w-[18px] h-[18px]">
                                <use xlink:href="#custom-sun-1"></use>
                            </svg>
                            <span>Light</span>
                        </a>
                        <a href="course-dashboard.html#!" class="dropdown-item" onclick="layout_change_default()">
                            <svg class="pc-icon w-[18px] h-[18px]">
                                <use xlink:href="#custom-setting-2"></use>
                            </svg>
                            <span>Default</span>
                        </a>
                    </div>
                </li>
                <li class="dropdown pc-h-item">
                    <a class="pc-head-link dropdown-toggle me-0" data-pc-toggle="dropdown" href="course-dashboard.html#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <svg class="pc-icon">
                            <use xlink:href="#custom-language"></use>
                        </svg>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end pc-h-dropdown lng-dropdown">
                        <a href="course-dashboard.html#!" class="dropdown-item" data-lng="en">
                            <span>
                                English
                                <small>(UK)</small>
                            </span>
                        </a>
                        <a href="course-dashboard.html#!" class="dropdown-item" data-lng="fr">
                            <span>
                                français
                                <small>(French)</small>
                            </span>
                        </a>
                        <a href="course-dashboard.html#!" class="dropdown-item" data-lng="ro">
                            <span>
                                Română
                                <small>(Romanian)</small>
                            </span>
                        </a>
                        <a href="course-dashboard.html#!" class="dropdown-item" data-lng="cn">
                            <span>
                                中国人
                                <small>(Chinese)</small>
                            </span>
                        </a>
                    </div>
                </li>
                <li class="dropdown pc-h-item">
                    <a class="pc-head-link dropdown-toggle me-0" data-pc-toggle="dropdown" href="course-dashboard.html#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <svg class="pc-icon">
                            <use xlink:href="#custom-notification"></use>
                        </svg>
                        <span class="badge bg-success-500 text-white rounded-full z-10 absolute right-0 top-0">3</span>
                    </a>
                    <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown p-2">
                        <div class="dropdown-header flex items-center justify-between py-4 px-5">
                            <h5 class="m-0">Notifications</h5>
                            <a href="course-dashboard.html#!" class="btn btn-link btn-sm">Mark all read</a>
                        </div>
                        <div class="dropdown-body header-notification-scroll relative py-4 px-5"
                            style="max-height: calc(100vh - 215px)">
                            <p class="text-span mb-3">Today</p>
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="flex gap-4">
                                        <div class="shrink-0">
                                            <svg class="pc-icon text-primary-500 w-[22px] h-[22px]">
                                                <use xlink:href="#custom-layer"></use>
                                            </svg>
                                        </div>
                                        <div class="grow">
                                            <span class="float-end text-sm text-muted">2 min ago</span>
                                            <h5 class="text-body mb-2">UI/UX Design</h5>
                                            <p class="mb-0">
                                                Lorem Ipsum has been the industry's standard dummy text ever since
                                                the 1500s, when an unknown printer took a galley of
                                                type and scrambled it to make a type
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="flex gap-4">
                                        <div class="shrink-0">
                                            <svg class="pc-icon text-primary-500 w-[22px] h-[22px]">
                                                <use xlink:href="#custom-sms"></use>
                                            </svg>
                                        </div>
                                        <div class="grow">
                                            <span class="float-end text-sm text-muted">1 hour ago</span>
                                            <h5 class="text-body mb-2">Message</h5>
                                            <p class="mb-0">Lorem Ipsum has been the industry's standard dummy
                                                text ever since the 1500.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="text-span mb-3 mt-4">Yesterday</p>
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="flex gap-4">
                                        <div class="shrink-0">
                                            <svg class="pc-icon text-primary-500 w-[22px] h-[22px]">
                                                <use xlink:href="#custom-document-text"></use>
                                            </svg>
                                        </div>
                                        <div class="grow ms-3">
                                            <span class="float-end text-sm text-muted">2 hour ago</span>
                                            <h5 class="text-body mb-2">Forms</h5>
                                            <p class="mb-0">
                                                Lorem Ipsum has been the industry's standard dummy text ever since
                                                the 1500s, when an unknown printer took a galley of
                                                type and scrambled it to make a type
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="flex gap-4">
                                        <div class="shrink-0">
                                            <svg class="pc-icon text-primary-500 w-[22px] h-[22px]">
                                                <use xlink:href="#custom-user-bold"></use>
                                            </svg>
                                        </div>
                                        <div class="grow ms-3">
                                            <span class="float-end text-sm text-muted">12 hour ago</span>
                                            <h5 class="text-body mb-2">Challenge invitation</h5>
                                            <p class="mb-2">
                                                <span class="text-dark">Jonny aber</span>
                                                invites to join the challenge
                                            </p>
                                            <button class="btn btn-sm btn-outline-secondary me-2">Decline</button>
                                            <button class="btn btn-sm btn-primary">Accept</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="flex gap-4">
                                        <div class="shrink-0">
                                            <svg class="pc-icon text-primary-500 w-[22px] h-[22px]">
                                                <use xlink:href="#custom-security-safe"></use>
                                            </svg>
                                        </div>
                                        <div class="grow ms-3">
                                            <span class="float-end text-sm text-muted">5 hour ago</span>
                                            <h5 class="text-body mb-2">Security</h5>
                                            <p class="mb-0">
                                                Lorem Ipsum has been the industry's standard dummy text ever since
                                                the 1500s, when an unknown printer took a galley of
                                                type and scrambled it to make a type
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center py-2">
                            <a href="course-dashboard.html#!"
                                class="text-danger-500 hover:text-danger-600 focus:text-danger-600 active:text-danger-600">
                                Clear all Notifications
                            </a>
                        </div>
                    </div>
                </li>
                <li class="dropdown pc-h-item header-user-profile">
                    <a class="pc-head-link dropdown-toggle arrow-none me-0" data-pc-toggle="dropdown"
                        href="course-dashboard.html#" role="button" aria-haspopup="false"
                        data-pc-auto-close="outside" aria-expanded="false">
                        <img class="shrink-0 w-[45px] h-[45px] round-image"
                            src="{{ auth('admin')->user()->getImageUrl() }}" alt="user-image" />
                    </a>
                    <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown p-2">
                        <div class="dropdown-header flex items-center justify-between py-4 px-5">
                            <h5 class="m-0">Perfil</h5>
                        </div>
                        <div class="profile-notification-scroll position-relative"
                            style="max-height: calc(100vh - 225px)">
                            <div class="dropdown-body py-4 px-5">
                                <div class="flex mb-1 items-center">
                                    <div class="shrink-0">
                                        <img class="shrink-0 w-[45px] h-[45px] round-image"
                                            src="{{ auth('admin')->user()->getImageUrl() }}" alt="user-image" />
                                    </div>
                                    <div class="grow ms-3">
                                        <h6 class="mb-1"> {{ auth('admin')->user()->getShortName() }} 🖖</h6>
                                        <span> {{ auth('admin')->user()->email }}</span>
                                    </div>
                                </div>
                                <hr class="border-secondary-500/10 my-4" />
                                <div class="card">
                                    <div class="card-body !py-4">
                                        <div class="flex items-center justify-between">
                                            <h5 class="mb-0 inline-flex items-center">
                                                <svg class="pc-icon text-muted me-2 w-[22px] h-[22px]">
                                                    <use xlink:href="#custom-notification-outline"></use>
                                                </svg>
                                                Notification
                                            </h5>
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="checkbox" value="" class="sr-only peer" />
                                                <div
                                                    class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-span mb-3">Menu</p>
                                <a href="{{ route('admin.profile.index') }}" class="dropdown-item">
                                    <span>
                                        <svg class="pc-icon text-muted me-2 inline-block">
                                            <use xlink:href="#custom-profile-2user-outline"></use>
                                        </svg>
                                        <span>Perfil</span>
                                    </span>
                                </a>
                                <a href="{{ route('admin.profile.edit') }}" class="dropdown-item">
                                    <span>
                                        <svg class="pc-icon text-muted me-2 inline-block">
                                            <use xlink:href="#custom-profile-2user-outline"></use>
                                        </svg>
                                        <span>Editar perfil</span>
                                    </span>
                                </a>
                                <a href="{{ route('admin.settings.index') }}" class="dropdown-item">
                                    <span>
                                        <svg class="pc-icon text-muted me-2 inline-block">
                                            <use xlink:href="#custom-setting-outline"></use>
                                        </svg>
                                        <span>Definições</span>
                                    </span>
                                </a>
                                <hr class="border-secondary-500/10 my-4" />
                                <div class="grid mb-3">
                                    <button type="button"
                                        class="btn btn-primary-500 flex items-center justify-center"
                                        onclick="event.preventDefault(); document.getElementById('header-logout-form').submit();">
                                        <svg class="pc-icon me-2 w-[22px] h-[22px]">
                                            <use xlink:href="#custom-logout-1-outline"></use>
                                        </svg>
                                        Sair
                                    </button>

                                    <form id="header-logout-form" action="{{ route('admin.logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>
