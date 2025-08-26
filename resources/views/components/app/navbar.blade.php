<nav class="navbar navbar-main navbar-expand-lg mx-3 px-0 shadow-none rounded" id="navbarBlur" navbar-scroll="true">
    <div class="container-fluid py-1 px-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-1 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm">
                    <a class="opacity-5 text-dark" href="{{ route('dashboard') }}">
                        Administration
                    </a>
                </li>
                <li class="breadcrumb-item text-sm text-dark active" aria-current="page">
                    Acceuil
                </li>
            </ol>
            <h6 class="font-weight-bold mb-0">
                Gestion & Organisation
                @php
                    $y = getCurrentYear();
                    $msg;
                    isset($y) ? $msg = "( année : $y->libelleAnneeScolaire )" : $msg = "";
                    echo $msg;
                @endphp
            </h6>
        </nav>
        <div class="collapse justify-content-end navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <div>
                <a href="{{ route("home.index") }}"
                    class="text-sm font-weight-bold ms-auto btn btn-sm btn-outline-primary mb-0 me-1"
                >
                    voir le site <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
            <div class="mb-0 font-weight-bold breadcrumb-text text-white">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="login" onclick="event.preventDefault(); this.closest('form').submit();">
                        <button class="btn btn-sm  btn-white  mb-0 me-1" type="submit">
                            Deconnexion <i class="fa-solid fa-lg fa-arrow-right-from-bracket"></i>
                        </button>
                    </a>
                </form>
            </div>
            <ul class="navbar-nav">
                <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </a>
                </li>
                <li class="nav-item px-3 d-flex align-items-center">
                    <a href="{{ route('notification.index') }}" class="nav-link text-body p-0">
                        <i class="fa fa-bell"></i>
                        <span class="text-sm {{ count(unreadNotifications()) > 0 ? 'text-danger text-bold' : '' }}">
                            ({{ count(unreadNotifications()) }})
                        </span>
                    </a>
                </li>
                <li class="nav-item px-3 d-flex align-items-center">
                    <a href="{{ route('profile.index') }}" class="nav-link text-body p-0">
                        <i class="fa-solid fa-gear"></i>
                    </a>
                </li>
                <li class="nav-item ps-2 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0">
                        <img src="{{ asset('logo/logo-bista.png') }}" class="avatar avatar-sm" alt="avatar" />
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->
