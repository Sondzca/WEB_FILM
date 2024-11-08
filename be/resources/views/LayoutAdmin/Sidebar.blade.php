<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile dropdown no-arrow">
            <a href="#" class="nav-link d-flex align-items-center" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="nav-profile-image">
                    <img src="{{asset('assets/images/logo-mini.svg')}}" alt="profile" class="img-profile rounded-circle" style="width: 80px; height: 35px;" /> <!-- Thay đổi kích thước ở đây -->
                    <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">
                        Vi Giang
                    </span>
                   
                    <span style="color: green">Admin</span>
               
                </div>
                <!-- Dropdown toggle icon -->
                <span class="mdi mdi-dots-vertical mdi-24px ms-3"></span>
            </a>
            
            <!-- Dropdown menu -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item bg-red text-center" href="#">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item bg-yellow" href="#" data-toggle="modal" data-target="#logoutModal">
                    <form action="#" method="POST" class=" text-center">
                        @csrf
                        <button type="submit" class="btn badge bg-danger" onclick="return confirm('chắc chắn đằng xuất')">Log Out</button>
                    </form>
                </a>
            </div>
        </li>

        <li class="nav-item">

            <a class="nav-link" href="{{route('admin.dashboard')}}">
                <span class="menu-title">Trang chủ</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('categories.index')}}">
                <span class="menu-title">Danh mục</span>
                <i class="mdi mdi-tshirt-crew menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('tickets.index')}}">
                <span class="menu-title">Vé Xem phim </span>
                <i class="mdi mdi-format-list-bulleted menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <span class="menu-title">Đơn hàng</span>
                <i class="mdi mdi-clipboard menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <span class="menu-title">#</span>
                <i class="mdi mdi-account menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#tables">
                <span class="menu-title">#</span>
                <i class="mdi mdi-comment menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <span class="menu-title">#</span>
                <i class="mdi mdi-format-size menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <span class="menu-title">#</span>
                <i class="mdi mdi-format-color-fill menu-icon"></i>
            </a>
        </li>
    </ul>
</nav>
