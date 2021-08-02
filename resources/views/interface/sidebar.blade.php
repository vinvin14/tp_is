<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            {{--<i class="fas fa-laugh-wink"></i>--}}
        </div>
        <div class="sidebar-brand-text mx-3">TATAK<b class="text-danger">PINAS</b></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item @if($page=='dashboard') active @endif">
        <a class="nav-link" href="{{route('dashboard')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
       Navigation
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item @if($page=='customer') active @endif">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
           aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-users"></i>
            <span>Customers</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Customer Options</h6>
                <a class="collapse-item font-weight-bold" href="{{route('customer.list')}}"><i class="fas fa-fw fa-list"></i> Customer List</a>
                <a class="collapse-item font-weight-bold" href="{{route('customer.create')}}"><i class="fas fa-fw fa-user-plus"></i> Add a Customer</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item @if($page=='shop') active @endif">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
           aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span>Shop</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
             data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Utilities:</h6>
                <a class="collapse-item font-weight-bold" href="{{ route('product.list') }}"><i class="fas fa-fw fa-shopping-bag"></i>  Products</a>
                <a class="collapse-item font-weight-bold" href="cards.html">Promo</a>
                <a class="collapse-item font-weight-bold" href="{{ route('transaction.list') }}"><i class="fas fa-fw fa-handshake"></i> Transactions</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item @if($page=='reference') active @endif">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReference"
           aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-book"></i>
            <span>Reference</span>
        </a>
        <div id="collapseReference" class="collapse" aria-labelledby="headingUtilities"
             data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Reference Option:</h6>
{{--                <a class="collapse-item font-weight-bold" href="{{ route('specification.list') }}"><i class="fas fa-fw fa-photo-video"></i>  Specifications</a>--}}
                <a class="collapse-item font-weight-bold" href="{{ route('category.list') }}"><i class="fas fa-fw fa-chart-pie"></i> Category</a>
                <a class="collapse-item font-weight-bold" href="{{ route('unit.list') }}"><i class="fas fa-fw fa-ruler"></i> Units</a>
                <a class="collapse-item font-weight-bold" href="{{ route('paymentMethod.list') }}"><i class="fas fa-fw fa-money-bill"></i> Payment Methods</a>
            </div>
        </div>
    </li>
    <!-- Divider -->

    <!-- Heading -->

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
