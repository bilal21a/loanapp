    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{url("/")."/admin/dashboard"}}">
          <div class="sidebar-brand-icon">
          <img src="{{asset('kredda/public/imgs/logo.png')}}" alt="" width="75">
          </div>
          <div class="sidebar-brand-text mx-3">{{ config('app.name', '') }}</div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item">
          <a class="nav-link" href="{{url("/")."/admin/dashboard"}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
          Menu
        </div>

        <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-users"></i>
            <span>Users</span>
          </a>
          <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <a class="collapse-item" href="{{url("/")."/admin/users"}}">Users</a>
              <a class="collapse-item" href="{{url("/")."/admin/users/new-user"}}">Create profile</a>
            </div>
          </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{url("/")."/admin/users/agents"}}" aria-expanded="true" aria-controls="collapseAgents">
                <i class="fas fa-fw fa-users-cog"></i>
                <span>Agents</span>
            </a>
        </li>

        <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAccount" aria-expanded="true" aria-controls="collapseAccount">
            <i class="fas fa-fw fa-wallet"></i>
            <span>Savings</span>
          </a>
          <div id="collapseAccount" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
           <div class="bg-white py-2 collapse-inner rounded">
              <a class="collapse-item" href="{{url("/")."/admin/account"}}">Savings</a>
              <a class="collapse-item" href="{{url("/")."/admin/account/new-category"}}">Create savings</a>
            </div>
          </div>
        </li>

        <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseInvestment" aria-expanded="true" aria-controls="collapseInvestment">
            <i class="fas fa-fw fa-briefcase"></i>
            <span>Partners</span>
          </a>
          <div id="collapseInvestment" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <a class="collapse-item" href="{{url("/")."/admin/investment"}}">Partners</a>
              <a class="collapse-item" href="{{url("/")."/admin/investment/new-investment"}}">Create new Partner</a>
            </div>
          </div>
        </li>

        <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLoan" aria-expanded="true" aria-controls="collapseLoan">
            <i class="fas fa-fw fa-dollar-sign"></i>
            <span>Loans</span>
          </a>
          <div id="collapseLoan" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <a class="collapse-item" href="{{url("/")."/admin/loan"}}">Loan Plans</a>
              <a class="collapse-item" href="{{url("/")."/admin/loan/new-loan"}}">Create new plan</a>
              <a class="collapse-item" href="{{url("/")."/admin/loan/users"}}">All user Loans</a>
            </div>
          </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{url("/")."/admin/debt-recovery"}}" aria-expanded="true" aria-controls="collapseTrans">
                <i class="fas fa-fw fa-dollar-sign"></i>
                <span>Debt Recovery</span>
            </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="{{url("/")."/admin/transactions"}}" aria-expanded="true" aria-controls="collapseTrans">
            <i class="fas fa-fw fa-dollar-sign"></i>
            <span>Transactions</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseServices" aria-expanded="true" aria-controls="collapseServices">
            <i class="fas fa-fw fa-cog"></i>
            <span>Services (Bill Payment)</span>
          </a>
          <div id="collapseServices" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <a class="collapse-item" href="{{url("/")."/admin/services"}}">Services</a>
              <a class="collapse-item" href="{{url("/")."/admin/services/category"}}">Services category</a>
              <a class="collapse-item" href="{{url("/")."/admin/services/new-service"}}">Add new service</a>
              <a class="collapse-item" href="{{url("/")."/admin/services/category/new-category"}}">Add new category</a>
            </div>
          </div>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="{{url("/")."/admin/support"}}" aria-expanded="true" aria-controls="collapseHelp">
            <i class="fas fa-fw fa-headset"></i>
            <span>Support</span>
          </a>
        </li>

        <!-- <li class="nav-item">
          <a class="nav-link" href="{{url("/")."/admin/settings"}}" aria-expanded="true" aria-controls="collapseTrans">
            <i class="fas fa-fw fa-cog"></i>
            <span>Settings</span>
          </a>
        </li> -->

        <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSettings" aria-expanded="true" aria-controls="collapseSettings">
            <i class="fas fa-fw fa-cog"></i>
            <span>Settings</span>
          </a>
          <div id="collapseSettings" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <a class="collapse-item" href="{{url("/")."/admin/banners"}}">Banners</a>
              <a class="collapse-item" href="{{url("/")."/admin/settings"}}" >
                <span>Settings</span>
              </a>
            </div>
          </div>
        </li>

        <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAdmin" aria-expanded="true" aria-controls="collapseAdmin">
            <i class="fas fa-fw fa-user"></i>
            <span>Admin</span>
          </a>
          <div id="collapseAdmin" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <a class="collapse-item" href="{{url("/")."/admin"}}">Admin users</a>
              <a class="collapse-item" href="{{url("/")."/admin/register"}}">New Admin</a>
            </div>
          </div>
        </li>
        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
          <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

      </ul>
      <!-- End of Sidebar -->
