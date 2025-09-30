@extends('admin.dash.layouts.main')

@section('title', 'Pedidos')

@section('content')

    <!-- [ breadcrumb ] start -->
    @include('admin.dash.components.breadcrumb', [
        'title' => 'Pedidos',
        'items' => [['label' => 'Pedidos', 'url' => route('admin.orders.index')]],
    ])
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="grid grid-cols-12 gap-x-6">
        <div class="col-span-12">
            <div class="card table-card">
                <div class="card-header">
                    <div class="sm:flex items-center justify-between">
                        <h5 class="mb-3 sm:mb-0">Lista de pedidos</h5>
                        <div>
                            <a href="../admins/course-teacher-apply.html" class="btn btn-outline-secondary mr-1">Apply
                                Utilizadores</a>
                            <a href="{{ route('admin.orders.create') }}" class="btn btn-primary">Adicionar Pedido</a>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-3">
                    <div class="table-responsive">
                        <div class="datatable-wrapper datatable-loading no-footer sortable searchable fixed-columns">
                            <div class="datatable-container">
                                <table class="table table-hover datatable-table" id="pc-dt-simple">
                                    <thead>
                                        <tr>
                                            <th data-sortable="true" style="width: 22.605694564279553%;"><button
                                                    class="datatable-sorter">NAME</button></th>
                                            <th data-sortable="true" style="width: 18.809318377911993%;"><button
                                                    class="datatable-sorter">DEPARTMENTS</button></th>
                                            <th data-sortable="true" style="width: 15.875754961173424%;"><button
                                                    class="datatable-sorter">QUALIFICATION</button></th>
                                            <th data-sortable="true" style="width: 13.805004314063849%;"><button
                                                    class="datatable-sorter">MOBILE</button></th>
                                            <th data-sortable="true" style="width: 14.667817083692839%;"><button
                                                    class="datatable-sorter">JOINING DATE</button></th>
                                            <th data-sortable="true" style="width: 14.236410698878343%;"><button
                                                    class="datatable-sorter">ACTION</button></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr data-index="0">
                                            <td>
                                                <div class="flex items-center w-44">
                                                    <div class="shrink-0">
                                                        <img src="{{ asset('admin/assets/images/user/avatar-1.jpg') }}"
                                                            alt="user image" class="rounded-full w-10">
                                                    </div>
                                                    <div class="grow ltr:ml-3 rtl:mr-3">
                                                        <h6 class="mb-0">Airi Satou</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Developer</td>
                                            <td>B.COM., M.COM.</td>
                                            <td>(123) 4567 890</td>
                                            <td>2023/09/12</td>
                                            <td>
                                                <a href="#"
                                                    class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                    <i class="ti ti-eye text-xl leading-none"></i>
                                                </a>
                                                <a href="#"
                                                    class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                    <i class="ti ti-edit text-xl leading-none"></i>
                                                </a>
                                                <a href="#"
                                                    class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                    <i class="ti ti-trash text-xl leading-none"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr data-index="1">
                                            <td>
                                                <div class="flex items-center w-44">
                                                    <div class="shrink-0">
                                                        <img src="{{ asset('admin/assets/images/user/avatar-2.jpg') }}"
                                                            alt="user image" class="rounded-full w-10">
                                                    </div>
                                                    <div class="grow ltr:ml-3 rtl:mr-3">
                                                        <h6 class="mb-0">Ashton Cox</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Junior Technical</td>
                                            <td>B.COM., M.COM.</td>
                                            <td>(123) 4567 890</td>
                                            <td>2023/12/24</td>
                                            <td>
                                                <a href="#"
                                                    class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                    <i class="ti ti-eye text-xl leading-none"></i>
                                                </a>
                                                <a href="#"
                                                    class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                    <i class="ti ti-edit text-xl leading-none"></i>
                                                </a>
                                                <a href="#"
                                                    class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                    <i class="ti ti-trash text-xl leading-none"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr data-index="2">
                                            <td>
                                                <div class="flex items-center w-44">
                                                    <div class="shrink-0">
                                                        <img src="{{ asset('admin/assets/images/user/avatar-3.jpg') }}"
                                                            alt="user image" class="rounded-full w-10">
                                                    </div>
                                                    <div class="grow ltr:ml-3 rtl:mr-3">
                                                        <h6 class="mb-0">Bradley Greer</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Sales Assistant</td>
                                            <td>B.A, B.C.A</td>
                                            <td>(123) 4567 890</td>
                                            <td>2022/09/19</td>
                                            <td>
                                                <a href="#"
                                                    class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                    <i class="ti ti-eye text-xl leading-none"></i>
                                                </a>
                                                <a href="#"
                                                    class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                    <i class="ti ti-edit text-xl leading-none"></i>
                                                </a>
                                                <a href="#"
                                                    class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                    <i class="ti ti-trash text-xl leading-none"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr data-index="3">
                                            <td>
                                                <div class="flex items-center w-44">
                                                    <div class="shrink-0">
                                                        <img src="{{ asset('admin/assets/images/user/avatar-4.jpg') }}"
                                                            alt="user image" class="rounded-full w-10">
                                                    </div>
                                                    <div class="grow ltr:ml-3 rtl:mr-3">
                                                        <h6 class="mb-0">Brielle Williamson</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>JavaScript Developer</td>
                                            <td>B.A, B.C.A</td>
                                            <td>(123) 4567 890</td>
                                            <td>2022/08/22</td>
                                            <td>
                                                <a href="#"
                                                    class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                    <i class="ti ti-eye text-xl leading-none"></i>
                                                </a>
                                                <a href="#"
                                                    class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                    <i class="ti ti-edit text-xl leading-none"></i>
                                                </a>
                                                <a href="#"
                                                    class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                    <i class="ti ti-trash text-xl leading-none"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr data-index="4">
                                            <td>
                                                <div class="flex items-center w-44">
                                                    <div class="shrink-0">
                                                        <img src="{{ asset('admin/assets/images/user/avatar-5.jpg') }}"
                                                            alt="user image" class="rounded-full w-10">
                                                    </div>
                                                    <div class="grow ltr:ml-3 rtl:mr-3">
                                                        <h6 class="mb-0">Airi Satou</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Developer</td>
                                            <td>B.COM., M.COM.</td>
                                            <td>(123) 4567 890</td>
                                            <td>2023/09/12</td>
                                            <td>
                                                <a href="#"
                                                    class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                    <i class="ti ti-eye text-xl leading-none"></i>
                                                </a>
                                                <a href="#"
                                                    class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                    <i class="ti ti-edit text-xl leading-none"></i>
                                                </a>
                                                <a href="#"
                                                    class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                    <i class="ti ti-trash text-xl leading-none"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr data-index="5">
                                            <td>
                                                <div class="flex items-center w-44">
                                                    <div class="shrink-0">
                                                        <img src="{{ asset('admin/assets/images/user/avatar-6.jpg') }}"
                                                            alt="user image" class="rounded-full w-10">
                                                    </div>
                                                    <div class="grow ltr:ml-3 rtl:mr-3">
                                                        <h6 class="mb-0">Ashton Cox</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Junior Technical</td>
                                            <td>B.COM., M.COM.</td>
                                            <td>(123) 4567 890</td>
                                            <td>2023/12/24</td>
                                            <td>
                                                <a href="#"
                                                    class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                    <i class="ti ti-eye text-xl leading-none"></i>
                                                </a>
                                                <a href="#"
                                                    class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                    <i class="ti ti-edit text-xl leading-none"></i>
                                                </a>
                                                <a href="#"
                                                    class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                    <i class="ti ti-trash text-xl leading-none"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr data-index="6">
                                            <td>
                                                <div class="flex items-center w-44">
                                                    <div class="shrink-0">
                                                        <img src="{{ asset('admin/assets/images/user/avatar-7.jpg') }}"
                                                            alt="user image" class="rounded-full w-10">
                                                    </div>
                                                    <div class="grow ltr:ml-3 rtl:mr-3">
                                                        <h6 class="mb-0">Bradley Greer</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Sales Assistant</td>
                                            <td>B.A, B.C.A</td>
                                            <td>(123) 4567 890</td>
                                            <td>2022/09/19</td>
                                            <td>
                                                <a href="#"
                                                    class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                    <i class="ti ti-eye text-xl leading-none"></i>
                                                </a>
                                                <a href="#"
                                                    class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                    <i class="ti ti-edit text-xl leading-none"></i>
                                                </a>
                                                <a href="#"
                                                    class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                    <i class="ti ti-trash text-xl leading-none"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr data-index="7">
                                            <td>
                                                <div class="flex items-center w-44">
                                                    <div class="shrink-0">
                                                        <img src="{{ asset('admin/assets/images/user/avatar-8.jpg') }}"
                                                            alt="user image" class="rounded-full w-10">
                                                    </div>
                                                    <div class="grow ltr:ml-3 rtl:mr-3">
                                                        <h6 class="mb-0">Brielle Williamson</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>JavaScript Developer</td>
                                            <td>B.A, B.C.A</td>
                                            <td>(123) 4567 890</td>
                                            <td>2022/08/22</td>
                                            <td>
                                                <a href="#"
                                                    class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                    <i class="ti ti-eye text-xl leading-none"></i>
                                                </a>
                                                <a href="#"
                                                    class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                    <i class="ti ti-edit text-xl leading-none"></i>
                                                </a>
                                                <a href="#"
                                                    class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                    <i class="ti ti-trash text-xl leading-none"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr data-index="8">
                                            <td>
                                                <div class="flex items-center w-44">
                                                    <div class="shrink-0">
                                                        <img src="{{ asset('admin/assets/images/user/avatar-9.jpg') }}"
                                                            alt="user image" class="rounded-full w-10">
                                                    </div>
                                                    <div class="grow ltr:ml-3 rtl:mr-3">
                                                        <h6 class="mb-0">Brielle Williamson</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>JavaScript Developer</td>
                                            <td>B.A, B.C.A</td>
                                            <td>(123) 4567 890</td>
                                            <td>2022/08/22</td>
                                            <td>
                                                <a href="#"
                                                    class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                    <i class="ti ti-eye text-xl leading-none"></i>
                                                </a>
                                                <a href="#"
                                                    class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                    <i class="ti ti-edit text-xl leading-none"></i>
                                                </a>
                                                <a href="#"
                                                    class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                    <i class="ti ti-trash text-xl leading-none"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr data-index="9">
                                            <td>
                                                <div class="flex items-center w-44">
                                                    <div class="shrink-0">
                                                        <img src="{{ asset('admin/assets/images/user/avatar-10.jpg') }}"
                                                            alt="user image" class="rounded-full w-10">
                                                    </div>
                                                    <div class="grow ltr:ml-3 rtl:mr-3">
                                                        <h6 class="mb-0">Airi Satou</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Developer</td>
                                            <td>B.COM., M.COM.</td>
                                            <td>(123) 4567 890</td>
                                            <td>2023/09/12</td>
                                            <td>
                                                <a href="#"
                                                    class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                    <i class="ti ti-eye text-xl leading-none"></i>
                                                </a>
                                                <a href="#"
                                                    class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                    <i class="ti ti-edit text-xl leading-none"></i>
                                                </a>
                                                <a href="#"
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
        </div>
    </div>
@endsection
