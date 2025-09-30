@extends('admin.dash.layouts.main')

@section('title', 'Dashboard')

@section('content')
    <!-- [ breadcrumb ] start -->
    @include('admin.dash.components.breadcrumb', ['title' => 'Dashboard'])
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="grid grid-cols-12 gap-x-6">
        <div class="col-span-12 lg:col-span-3 md:col-span-6">
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <div
                                class="w-12 h-12 rounded-lg inline-flex items-center justify-center bg-primary-500/10 text-primary-500">
                                <i class="ti ti-users text-2xl leading-none"></i>
                            </div>
                        </div>
                        <div class="grow ltr:ml-3 rtl:mr-3">
                            <p class="mb-1">Pedidos</p>
                            <div class="flex items-center justify-between">
                                <h4 class="mb-0">400+</h4>
                                <span class="text-success font-medium">30.6%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-3 md:col-span-6">
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <div
                                class="w-12 h-12 rounded-lg inline-flex items-center justify-center bg-warning-500/10 text-warning-500">
                                <i class="ti ti-notebook text-2xl leading-none"></i>
                            </div>
                        </div>
                        <div class="grow ltr:ml-3 rtl:mr-3">
                            <p class="mb-1">Utilizadores</p>
                            <div class="flex items-center justify-between">
                                <h4 class="mb-0">520+</h4>
                                <span class="text-warning fw-medium">30.6%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-3 md:col-span-6">
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <div
                                class="w-12 h-12 rounded-lg inline-flex items-center justify-center bg-success-500/10 text-success-500">
                                <i class="ti ti-eye text-2xl leading-none"></i>
                            </div>
                        </div>
                        <div class="grow ltr:ml-3 rtl:mr-3">
                            <p class="mb-1">Produtos</p>
                            <div class="flex items-center justify-between">
                                <h4 class="mb-0">800+</h4>
                                <span class="text-success fw-medium">30.6%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-3 md:col-span-6">
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <div
                                class="w-12 h-12 rounded-lg inline-flex items-center justify-center bg-danger-500/10 text-danger-500">
                                <i class="ti ti-credit-card text-2xl leading-none"></i>
                            </div>
                        </div>
                        <div class="grow ltr:ml-3 rtl:mr-3">
                            <p class="mb-1">Categorías</p>
                            <div class="flex items-center justify-between">
                                <h4 class="mb-0">1,065</h4>
                                <span class="text-danger fw-medium">30.6%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-7">
            <div class="card">
                <div class="card-body">
                    <div class="flex items-start justify-between">
                        <div>
                            <h5 class="mb-1">Statistics</h5>
                            <p class="text-muted mb-2">Revenue and Sales</p>
                        </div>
                        <select class="form-select rounded-3 form-select-sm w-auto">
                            <option>Today</option>
                            <option>Weekly</option>
                            <option selected>Monthly</option>
                        </select>
                    </div>
                    <div id="revenue-sales-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-5">
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-3">
                        <h5 class="mb-0">Invites goal</h5>
                        <div class="dropdown">
                            <a class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary dropdown-toggle arrow-none"
                                href="course-dashboard.html#" data-pc-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="ti ti-dots-vertical text-lg leading-none"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="course-dashboard.html#">Today</a>
                                <a class="dropdown-item" href="course-dashboard.html#">Weekly</a>
                                <a class="dropdown-item" href="course-dashboard.html#">Monthly</a>
                            </div>
                        </div>
                    </div>
                    <div id="invites-goal-chart"></div>
                    <div class="text-center">
                        <span class="badge bg-success-500/10 text-success-500 rounded-full py-2 px-3">+10%</span>
                        <p class="text-muted my-2">You succeed earn $240 today, its higher than yesterday</p>
                        <div class="grid grid-cols-12 gap-3">
                            <div class="col-span-4">
                                <div class="text-center d-inline-block px-md-3">
                                    <p class="mb-1 text-muted">Target</p>
                                    <h5 class="inline-flex items-center gap-1 mb-0">
                                        $20k <i class="ti ti-arrow-narrow-down text-danger-500"></i>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-span-4">
                                <div class="text-center d-inline-block px-md-3">
                                    <p class="mb-1 text-muted">Revenue</p>
                                    <h5 class="inline-flex items-center gap-1 mb-0">
                                        $16k <i class="ti ti-arrow-narrow-up text-success-500"></i>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-span-4">
                                <div class="text-center d-inline-block px-md-3">
                                    <p class="mb-1 text-muted">Today</p>
                                    <h5 class="inline-flex items-center gap-1 mb-0">
                                        $1.5k <i class="ti ti-arrow-narrow-down text-danger-500"></i>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-5">
            <div class="card">
                <div class="card-header">
                    <h5>Upcoming Course</h5>
                </div>
                <div class="card-body !px-0 !py-2">
                    <ul
                        class="rounded-lg *:py-4 *:px-[25px] divide-y divide-inherit border-theme-border dark:border-themedark-border">
                        <li class="list-group-item">
                            <div class="flex items-center">
                                <div class="shrink-0">
                                    <img src="../assets/images/admin/img-bootstrap.svg" alt="img"
                                        class="w-10 rounded-lg" />
                                </div>
                                <div class="grow mx-3"> Bootstrap 5 Beginner Course </div>
                                <div class="shrink-0">
                                    <a href="course-dashboard.html#"
                                        class="w-10 h-10 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                        <i class="ti ti-chevron-right text-xl leading-none"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="flex items-center">
                                <div class="shrink-0">
                                    <img src="../assets/images/admin/img-php.svg" alt="img"
                                        class="w-10 rounded-lg" />
                                </div>
                                <div class="grow mx-3"> PHP Training Course </div>
                                <div class="shrink-0">
                                    <a href="course-dashboard.html#"
                                        class="w-10 h-10 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                        <i class="ti ti-chevron-right text-xl leading-none"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="flex items-center">
                                <div class="shrink-0">
                                    <img src="../assets/images/admin/img-ux.svg" alt="img"
                                        class="w-10 rounded-lg" />
                                </div>
                                <div class="grow mx-3"> UI/UX Training Course </div>
                                <div class="shrink-0">
                                    <a href="course-dashboard.html#"
                                        class="w-10 h-10 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                        <i class="ti ti-chevron-right text-xl leading-none"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="flex items-center">
                                <div class="shrink-0">
                                    <img src="../assets/images/admin/img-web.svg" alt="img"
                                        class="w-10 rounded-lg" />
                                </div>
                                <div class="grow mx-3"> Web Designing Course </div>
                                <div class="shrink-0">
                                    <a href="course-dashboard.html#"
                                        class="w-10 h-10 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                        <i class="ti ti-chevron-right text-xl leading-none"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-7">
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center justify-between">
                        <h5 class="mb-0">Courses</h5>
                        <button class="btn btn-sm btn-link-primary">View Report</button>
                    </div>
                    <h4 class="mb-1">$7,860</h4>
                    <p class="inline-flex items-center text-success-500 gap-1 mb-0"> <i class="ti ti-arrow-narrow-up"></i>
                        2.1% </p>
                    <p class="text-muted mb-1">Sales from 1-12 Dec, 2023</p>
                    <div id="course-report-bar-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-4 md:col-span-6">
            <div class="card">
                <div class="card-body">
                    <p class="text-muted mb-1">Total Revenue</p>
                    <div class="flex items-center justify-between">
                        <h4 class="mb-0">7,265</h4>
                        <p class="inline-flex items-center gap-1 mb-0"> +11.02% <i
                                class="ti ti-arrow-up-right text-success-500"></i> </p>
                    </div>
                    <div id="total-revenue-line-1-chart"></div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <p class="text-muted mb-1">Total Subscription</p>
                    <div class="flex items-center justify-between">
                        <h4 class="mb-0">5,326</h4>
                        <p class="inline-flex items-center gap-1 mb-0"> +12.02% <i
                                class="ti ti-arrow-down-right text-danger-500"></i> </p>
                    </div>
                    <div id="total-revenue-line-2-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-4 md:col-span-6">
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-3">
                        <h5 class="mb-0">Student States</h5>
                        <div class="dropdown">
                            <a class="w-10 h-10 rounded-xl inline-flex items-center justify-center btn-link-secondary dropdown-toggle arrow-none"
                                href="course-dashboard.html#" data-pc-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="ti ti-dots-vertical text-lg leading-none"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="course-dashboard.html#">Today</a>
                                <a class="dropdown-item" href="course-dashboard.html#">Weekly</a>
                                <a class="dropdown-item" href="course-dashboard.html#">Monthly</a>
                            </div>
                        </div>
                    </div>
                    <div id="student-states-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-4">
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-3">
                        <h5 class="mb-0">Student Queries</h5>
                        <div class="dropdown">
                            <a class="w-10 h-10 rounded-xl inline-flex items-center justify-center btn-link-secondary dropdown-toggle arrow-none"
                                href="course-dashboard.html#" data-pc-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="ti ti-dots-vertical f-18"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="course-dashboard.html#">Today</a>
                                <a class="dropdown-item" href="course-dashboard.html#">Weekly</a>
                                <a class="dropdown-item" href="course-dashboard.html#">Monthly</a>
                            </div>
                        </div>
                    </div>
                    <ul class="rounded-lg *:py-4 divide-y divide-inherit border-theme-border dark:border-themedark-border">
                        <li class="list-group-item">
                            <div class="flex items-center">
                                <div class="shrink-0">
                                    <img src="../assets/images/user/avatar-2.jpg" alt="img"
                                        class="w-10 rounded-full" />
                                </div>
                                <div class="grow mx-3"> Python $ Data Manage </div>
                                <div class="shrink-0">
                                    <a href="course-dashboard.html#"
                                        class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-light-secondary">
                                        <i class="ti ti-eye text-xl leading-none"></i>
                                    </a>
                                    <a href="course-dashboard.html#"
                                        class="ml-1 w-8 h-8 rounded-xl inline-flex items-center justify-center btn-light-secondary">
                                        <i class="ti ti-trash text-xl leading-none"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-2">
                            <div class="flex items-center">
                                <div class="shrink-0">
                                    <img src="../assets/images/user/avatar-1.jpg" alt="img"
                                        class="w-10 rounded-full" />
                                </div>
                                <div class="grow mx-3"> Website Error </div>
                                <div class="shrink-0">
                                    <a href="course-dashboard.html#"
                                        class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-light-secondary">
                                        <i class="ti ti-eye text-xl leading-none"></i>
                                    </a>
                                    <a href="course-dashboard.html#"
                                        class="ml-1 w-8 h-8 rounded-xl inline-flex items-center justify-center btn-light-secondary">
                                        <i class="ti ti-trash text-xl leading-none"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-2">
                            <div class="flex items-center">
                                <div class="shrink-0">
                                    <img src="../assets/images/user/avatar-3.jpg" alt="img"
                                        class="w-10 rounded-full" />
                                </div>
                                <div class="grow mx-3"> How to Illustrate </div>
                                <div class="shrink-0">
                                    <a href="course-dashboard.html#"
                                        class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-light-secondary">
                                        <i class="ti ti-eye text-xl leading-none"></i>
                                    </a>
                                    <a href="course-dashboard.html#"
                                        class="ml-1 w-8 h-8 rounded-xl inline-flex items-center justify-center btn-light-secondary">
                                        <i class="ti ti-trash text-xl leading-none"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-2">
                            <div class="flex items-center">
                                <div class="shrink-0">
                                    <img src="../assets/images/user/avatar-4.jpg" alt="img"
                                        class="w-10 rounded-full" />
                                </div>
                                <div class="grow mx-3"> PHP Learning </div>
                                <div class="shrink-0">
                                    <a href="course-dashboard.html#"
                                        class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-light-secondary">
                                        <i class="ti ti-eye text-xl leading-none"></i>
                                    </a>
                                    <a href="course-dashboard.html#"
                                        class="ml-1 w-8 h-8 rounded-xl inline-flex items-center justify-center btn-light-secondary">
                                        <i class="ti ti-trash text-xl leading-none"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-span-12 xl:col-span-8">
            <div class="card">
                <div class="card-body">
                    <div class="flex items-start justify-between">
                        <div>
                            <h5 class="mb-1">Activity</h5>
                        </div>
                        <select class="form-select rounded-3 form-select-sm w-auto">
                            <option>Today</option>
                            <option>Weekly</option>
                            <option selected>Monthly</option>
                        </select>
                    </div>
                    <div id="activity-line-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-span-12 xl:col-span-4">
            <div class="card">
                <div class="card-body">
                    <div class="widget-calender *:mx-auto" id="pc-datepicker-6"></div>
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-6">
            <div class="card table-card">
                <div class="card-header">
                    <div class="flex items-center justify-between">
                        <h5 class="mb-0">Activity</h5>
                        <button class="btn btn-sm btn-link-primary">View All</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Qualification</th>
                                    <th>Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="flex items-center w-44">
                                            <div class="shrink-0">
                                                <img src="../assets/images/user/avatar-4.jpg" alt="user image"
                                                    class="rounded-full w-10" />
                                            </div>
                                            <div class="grow ltr:ml-3 rtl:mr-3">
                                                <h6 class="mb-0">Airi Satou</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Developer</td>
                                    <td class="font-semibold"> <i class="fas fa-star text-warning-500"></i>
                                        5.0 </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="flex items-center w-44">
                                            <div class="shrink-0">
                                                <img src="../assets/images/user/avatar-1.jpg" alt="user image"
                                                    class="rounded-full w-10" />
                                            </div>
                                            <div class="grow ltr:ml-3 rtl:mr-3">
                                                <h6 class="mb-0">Ashton Cox</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Junior Technical</td>
                                    <td class="font-semibold"> <i class="fas fa-star text-warning-500"></i>
                                        4.5 </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="flex items-center w-44">
                                            <div class="shrink-0">
                                                <img src="../assets/images/user/avatar-2.jpg" alt="user image"
                                                    class="rounded-full w-10" />
                                            </div>
                                            <div class="grow ltr:ml-3 rtl:mr-3">
                                                <h6 class="mb-0">Bradley Greer</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Sales Assistant</td>
                                    <td class="font-semibold"> <i class="fas fa-star text-warning-500"></i>
                                        4.3 </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="flex items-center w-44">
                                            <div class="shrink-0">
                                                <img src="../assets/images/user/avatar-3.jpg" alt="user image"
                                                    class="rounded-full w-10" />
                                            </div>
                                            <div class="grow ltr:ml-3 rtl:mr-3">
                                                <h6 class="mb-0">Brielle Williamson</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>JavaScript Developer</td>
                                    <td class="font-semibold"> <i class="fas fa-star text-warning-500"></i>
                                        4.9 </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="flex items-center w-44">
                                            <div class="shrink-0">
                                                <img src="../assets/images/user/avatar-5.jpg" alt="user image"
                                                    class="rounded-full w-10" />
                                            </div>
                                            <div class="grow ltr:ml-3 rtl:mr-3">
                                                <h6 class="mb-0">Colleen Hurst</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Software Engineer</td>
                                    <td class="font-semibold"> <i class="fas fa-star text-warning-500"></i>
                                        4.9 </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-6">
            <div class="card table-card">
                <div class="card-header">
                    <h5>Trending Course</h5>
                </div>
                <div class="card-body px-0 py-2">
                    <ul
                        class="rounded-lg *:py-4 *:px-[25px] divide-y divide-inherit border-theme-border dark:border-themedark-border">
                        <li class="list-group-item">
                            <div class="flex items-center">
                                <div class="shrink-0">
                                    <img src="../assets/images/admin/img-bootstrap.svg" alt="img"
                                        class="w-10 rounded-lg" />
                                </div>
                                <div class="grow mx-3"> Bootstrap 5 Beginner Course </div>
                                <div class="shrink-0">
                                    <a href="course-dashboard.html#"
                                        class="w-10 h-10 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                        <i class="ti ti-chevron-right text-xl leading-none"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="flex items-center">
                                <div class="shrink-0">
                                    <img src="../assets/images/admin/img-php.svg" alt="img"
                                        class="w-10 rounded-lg" />
                                </div>
                                <div class="grow mx-3"> PHP Training Course </div>
                                <div class="shrink-0">
                                    <a href="course-dashboard.html#"
                                        class="w-10 h-10 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                        <i class="ti ti-chevron-right text-xl leading-none"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="flex items-center">
                                <div class="shrink-0">
                                    <img src="../assets/images/admin/img-ux.svg" alt="img"
                                        class="w-10 rounded-lg" />
                                </div>
                                <div class="grow mx-3"> UI/UX Training Course </div>
                                <div class="shrink-0">
                                    <a href="course-dashboard.html#"
                                        class="w-10 h-10 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                        <i class="ti ti-chevron-right text-xl leading-none"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="flex items-center">
                                <div class="shrink-0">
                                    <img src="../assets/images/admin/img-web.svg" alt="img"
                                        class="w-10 rounded-lg" />
                                </div>
                                <div class="grow mx-3"> Web Designing Course </div>
                                <div class="shrink-0">
                                    <a href="course-dashboard.html#"
                                        class="w-10 h-10 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                        <i class="ti ti-chevron-right text-xl leading-none"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="flex items-center">
                                <div class="shrink-0">
                                    <img src="../assets/images/admin/img-c.svg" alt="img" class="w-10 rounded-lg" />
                                </div>
                                <div class="grow mx-3"> C Training Course </div>
                                <div class="shrink-0">
                                    <a href="course-dashboard.html#"
                                        class="w-10 h-10 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                        <i class="ti ti-chevron-right text-xl leading-none"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-4 md:col-span-6">
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-3">
                        <h5 class="mb-0">Visitors</h5>
                        <div class="dropdown">
                            <a class="w-10 h-10 rounded-xl inline-flex items-center justify-center btn-link-secondary dropdown-toggle arrow-none"
                                href="course-dashboard.html#" data-pc-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="ti ti-dots-vertical f-18"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="course-dashboard.html#">Today</a>
                                <a class="dropdown-item" href="course-dashboard.html#">Weekly</a>
                                <a class="dropdown-item" href="course-dashboard.html#">Monthly</a>
                            </div>
                        </div>
                    </div>
                    <div id="visitors-bar-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-4 md:col-span-6">
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-3">
                        <h5 class="mb-0">Earning Courses</h5>
                        <select class="form-select rounded-3 form-select-sm w-auto">
                            <option>Day</option>
                            <option>Month</option>
                            <option selected>Year</option>
                        </select>
                    </div>
                    <div id="earning-courses-line-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-4">
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-3">
                        <h5 class="mb-0">Notifications</h5>
                        <div class="dropdown">
                            <a class="w-10 h-10 rounded-xl inline-flex items-center justify-center btn-link-secondary dropdown-toggle arrow-none"
                                href="course-dashboard.html#" data-pc-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="ti ti-dots-vertical f-18"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="course-dashboard.html#">Today</a>
                                <a class="dropdown-item" href="course-dashboard.html#">Weekly</a>
                                <a class="dropdown-item" href="course-dashboard.html#">Monthly</a>
                            </div>
                        </div>
                    </div>
                    <ul class="rounded-lg *:py-4 divide-y divide-inherit border-theme-border dark:border-themedark-border">
                        <li class="list-group-item px-0 py-2">
                            <div class="flex items-center">
                                <div class="shrink-0">
                                    <img src="../assets/images/user/avatar-1.jpg" alt="img"
                                        class="w-10 rounded-full" />
                                </div>
                                <div class="grow mx-3">
                                    <h6 class="mb-1">Report Successfully</h6>
                                    <p class="mb-0 text-muted">Today | 9:00 AM</p>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-2">
                            <div class="flex items-center">
                                <div class="shrink-0">
                                    <img src="../assets/images/user/avatar-2.jpg" alt="img"
                                        class="w-10 rounded-full" />
                                </div>
                                <div class="grow mx-3">
                                    <h6 class="mb-1">Reminder: Test time</h6>
                                    <p class="mb-0 text-muted">Yesterday | 6:30 PM</p>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-2">
                            <div class="flex items-center">
                                <div class="shrink-0">
                                    <img src="../assets/images/user/avatar-3.jpg" alt="img"
                                        class="w-10 rounded-full" />
                                </div>
                                <div class="grow mx-3">
                                    <h6 class="mb-1">Send course pdf</h6>
                                    <p class="mb-0 text-muted">05 Feb | 3:45 PM</p>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-2">
                            <div class="flex items-center">
                                <div class="shrink-0">
                                    <img src="../assets/images/user/avatar-4.jpg" alt="img"
                                        class="w-10 rounded-full" />
                                </div>
                                <div class="grow mx-3">
                                    <h6 class="mb-1">Report Successfully</h6>
                                    <p class="mb-0 text-muted">05 Feb | 4:00 PM</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-span-12">
            <div class="card table-card">
                <div class="card-header">
                    <div class="flex items-center justify-between">
                        <h5 class="mb-0">Course States</h5>
                        <button class="btn btn-sm btn-link-primary">View All</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Teacher</th>
                                    <th>Rating</th>
                                    <th>Earring</th>
                                    <th>Sale</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Web Designing Course</td>
                                    <td>Airi Satou</td>
                                    <td class="font-semibold"> <i class="fas fa-star text-warning-500"></i>
                                        4.8 </td>
                                    <td>$200</td>
                                    <td><span class="donut">5/7</span></td>
                                    <td>
                                        <a href="course-dashboard.html#"
                                            class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                            <i class="ti ti-eye text-xl leading-none"></i>
                                        </a>
                                        <a href="course-dashboard.html#"
                                            class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                            <i class="ti ti-edit text-xl leading-none"></i>
                                        </a>
                                        <a href="course-dashboard.html#"
                                            class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                            <i class="ti ti-trash text-xl leading-none"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>UI/UX Training Course</td>
                                    <td>Ashton Cox</td>
                                    <td class="font-semibold"> <i class="fas fa-star text-warning-500"></i>
                                        5.0 </td>
                                    <td>$100</td>
                                    <td><span class="donut">4/7</span></td>
                                    <td>
                                        <a href="course-dashboard.html#"
                                            class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                            <i class="ti ti-eye text-xl leading-none"></i>
                                        </a>
                                        <a href="course-dashboard.html#"
                                            class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                            <i class="ti ti-edit text-xl leading-none"></i>
                                        </a>
                                        <a href="course-dashboard.html#"
                                            class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                            <i class="ti ti-trash text-xl leading-none"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>PHP Training Course</td>
                                    <td>Bradley Greer</td>
                                    <td class="font-semibold"> <i class="fas fa-star text-warning-500"></i>
                                        4.9 </td>
                                    <td>$80</td>
                                    <td><span class="donut">2/7</span></td>
                                    <td>
                                        <a href="course-dashboard.html#"
                                            class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                            <i class="ti ti-eye text-xl leading-none"></i>
                                        </a>
                                        <a href="course-dashboard.html#"
                                            class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                            <i class="ti ti-edit text-xl leading-none"></i>
                                        </a>
                                        <a href="course-dashboard.html#"
                                            class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                            <i class="ti ti-trash text-xl leading-none"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Bootstrap 5 Course</td>
                                    <td>Brielle Williamson</td>
                                    <td class="font-semibold"> <i class="fas fa-star text-warning-500"></i>
                                        4.4 </td>
                                    <td>$150</td>
                                    <td><span class="donut">6/7</span></td>
                                    <td>
                                        <a href="course-dashboard.html#"
                                            class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                            <i class="ti ti-eye text-xl leading-none"></i>
                                        </a>
                                        <a href="course-dashboard.html#"
                                            class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                            <i class="ti ti-edit text-xl leading-none"></i>
                                        </a>
                                        <a href="course-dashboard.html#"
                                            class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                            <i class="ti ti-trash text-xl leading-none"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>C Training Course</td>
                                    <td>Cedric Kelly</td>
                                    <td class="font-semibold"> <i class="fas fa-star text-warning-500"></i>
                                        4.3 </td>
                                    <td>$50</td>
                                    <td><span class="donut">3/7</span></td>
                                    <td>
                                        <a href="course-dashboard.html#"
                                            class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                            <i class="ti ti-eye text-xl leading-none"></i>
                                        </a>
                                        <a href="course-dashboard.html#"
                                            class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                            <i class="ti ti-edit text-xl leading-none"></i>
                                        </a>
                                        <a href="course-dashboard.html#"
                                            class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                            <i class="ti ti-trash text-xl leading-none"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
@endsection
