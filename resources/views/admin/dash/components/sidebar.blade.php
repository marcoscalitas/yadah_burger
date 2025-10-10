<nav class="pc-sidebar mt-2">
    <div class="navbar-wrapper">
        <div class="m-header flex items-center justify-content-sm-center py-4 px-6 h-header-height">
            <a href="{{ route('admin.index') }}" class="b-brand flex items-center gap-1">
                <!-- ========   Change your logo from here   ============ -->

                <img class="shrink-0 w-[55px] h-[55px] rounded-full"
                    src="{{ asset('admin/assets/images/yadah_burguer_logo.jpeg') }}" alt="Logo" />
                <div>
                    <h5><strong>Yagah Burguer </strong></h5>
                    <span class="badge bg-success-500/10 text-success-500 rounded-full theme-version">v1.0.0</span>
                </div>
            </a>
        </div>
        <div class="navbar-content h-[calc(100vh_-_74px)] py-2.5">
            <div class="card pc-user-card mx-[15px] mb-[15px] bg-theme-sidebaruserbg dark:bg-themedark-sidebaruserbg">
                <div class="card-body !p-5">
                    <div class="flex items-center">
                        <img class="shrink-0 w-[55px] h-[55px] round-image"
                            src="{{ getCurrentUser('admin')->getImageUrl() }}" alt="user-image" />
                        <div class="ml-4 mr-2 grow">
                            <h6 class="mb-0" data-i18n="{{ getCurrentUser('admin')->getShortName() }} ">
                                {{ getCurrentUser('admin')->getShortName() }}
                            </h6>
                            <small>{{ getCurrentUser('admin')->getRoleLabel() }}</small>
                        </div>
                    </div>
                </div>
            </div>
            <ul class="pc-navbar">
                <li class="pc-item pc-caption">
                    <label data-i18n="Navigation">Navegação</label>
                </li>
                <li class="pc-item">
                    <a href="{{ route('admin.index') }}" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-status-up"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext" data-i18n="Dashboard">Dashboard</span>
                    </a>
                </li>
                <li class="pc-item">
                    <a href="{{ route('admin.users.index') }}" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-user"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext" data-i18n="Utilizadores">Utilizadores</span>
                    </a>
                </li>
                <li class="pc-item">
                    <a href="{{ route('admin.orders.index') }}" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-bag"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext" data-i18n="Pedidos">Pedidos</span>

                    </a>
                </li>
                <li class="pc-item">
                    <a href="{{ route('admin.products.index') }}" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-box-1"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext" data-i18n="Produtos">Produtos</span>
                    </a>
                </li>
                <li class="pc-item">
                    <a href="{{ route('admin.categories.index') }}" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-layer"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext" data-i18n="Categorias">Categorias</span>
                    </a>
                </li>
                <li class="pc-item pc-caption">
                    <label data-i18n="Outros">Outros</label>
                    <svg class="pc-icon">
                        <use xlink:href="#custom-layer"></use>
                    </svg>
                </li>
                <li class="pc-item">
                    <a href="{{ route('admin.settings.index') }}" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-setting-2"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext" data-i18n="Definições">Definições</span>
                    </a>
                </li>
                <li class="pc-item">
                    <a href="{{ route('admin.logout') }}" class="pc-link"
                        onclick="event.preventDefault(); document.getElementById('side-barlogout-form').submit();">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-logout"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext" data-i18n="Sair">Sair</span>
                    </a>

                    <form id="side-barlogout-form" action="{{ route('admin.logout') }}" method="POST"
                        style="display:none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
