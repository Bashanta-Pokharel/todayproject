<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title')</title>
    
   

    <!-- Custom fonts for this template-->
    <link href="{{asset('assets/backend/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{asset('assets/backend/css/sb-admin-2.min.css')}}" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
                <div class="sidebar-brand-icon rotate-n-15">
                    <!-- logo add png -->
                     <img src="{{asset('assets/backend/img/logo3.png')}}" alt="Logo" width="100" height="auto">

                </div>
                <div class="sidebar-brand-text mx-3">Ecommerce Admin</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <!-- Category Management -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCategory"
        aria-expanded="true" aria-controls="collapseCategory">
        <i class="fas fa-fw fa-list"></i>
        <span>Category Management</span>
    </a>
    <div id="collapseCategory" class="collapse {{ request()->routeIs('admin.category.*') ? 'show' : '' }}"
        data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item {{ request()->routeIs('admin.category.create') ? 'active' : '' }}" href="{{route('admin.category.create')}}">Create</a>
            <a class="collapse-item {{ request()->routeIs('admin.category.index') ? 'active' : '' }}" href="{{route('admin.category.index')}}">List</a>
            <a class="collapse-item {{ request()->routeIs('admin.category.trashed') ? 'active' : '' }}" href="{{route('admin.category.trashed')}}">Trash</a>
        </div>
    </div>
</li>

<!-- Attribute Management -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAttribute"
        aria-expanded="true" aria-controls="collapseAttribute">
        <i class="fas fa-fw fa-tags"></i>
        <span>Attribute Management</span>
    </a>
    <div id="collapseAttribute" class="collapse {{ request()->routeIs('admin.attribute.*') ? 'show' : '' }}"
        data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item {{ request()->routeIs('admin.attribute.create') ? 'active' : '' }}" href="{{route('admin.attribute.create')}}">Create</a>
            <a class="collapse-item {{ request()->routeIs('admin.attribute.index') ? 'active' : '' }}" href="{{route('admin.attribute.index')}}">List</a>
            <a class="collapse-item {{ request()->routeIs('admin.attribute.trashed') ? 'active' : '' }}" href="{{route('admin.attribute.trashed')}}">Trash</a>
        </div>
    </div>
</li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseThree"
                   aria-expanded="true" aria-controls="collapseThree">
                    <i class="fas fa-fw fa-shopping-bag"></i>
                    <span>Product Management</span>
                </a>
                <div id="collapseThree" class="collapse {{ request()->routeIs('admin.product.*') ? 'show' : '' }}" aria-labelledby="headingThree" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item {{ request()->routeIs('admin.product.create') ? 'active' : '' }}" href="{{route('admin.product.create')}}">Create</a>
                        <a class="collapse-item {{ request()->routeIs('admin.product.index') ? 'active' : '' }}" href="{{route('admin.product.index')}}">List</a>
                        <a class="collapse-item {{ request()->routeIs('admin.product.trashed') ? 'active' : '' }}" href="{{route('admin.product.trashed')}}">Trash</a>
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.orders.index') }}">
                    <i class="fas fa-fw fa-receipt"></i>
                    <span>Order Management</span>
                </a>
            </li>


        </ul>
        <!-- End of Sidebar -->
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>


                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                                <img class="img-profile rounded-circle"
                                    src="{{asset('assets/backend/img/undraw_profile.svg')}}">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Dashboard
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                @yield('content')
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Ecommerce Admin {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-primary" type="submit">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{asset('assets/backend/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('assets/backend/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{asset('assets/backend/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{asset('assets/backend/js/sb-admin-2.min.js')}}"></script>

</body>

</html>
