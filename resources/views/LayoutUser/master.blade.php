<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!-- Nhúng Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('user/style.css') }}">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/solana-web3.js/1.29.0/solana-web3.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@solana/web3.js@2.1.0-canary-20241115192830/dist/solana-web3.min.js"></script>
    <style>
        /* Đặt màu sắc chung và font chữ */
body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
    color: #333;
}

/* Styling cho menu */
.menu-container {
    width: 250px;
    border-radius: 10px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}

.menu-container .nav-profile-text {
    margin-bottom: 20px;
}

.menu-container img.img-profile {
    border: 2px solid #6c757d;
}

.menu-container ul.menu {
    margin: 0;
    padding: 0;
}

.menu-container ul.menu li {
    margin: 10px 0;
}

.menu-container ul.menu a {
    color: #6c757d;
    text-decoration: none;
    font-size: 16px;
    padding: 10px 15px;
    border-radius: 5px;
    transition: all 0.3s ease-in-out;
}

.menu-container ul.menu a:hover {
    background-color: #e9ecef;
    color: #007bff;
}

.menu-container .dropdown-content {
    padding-left: 20px;
}

.menu-container .dropdown-btn {
    background: none;
    border: none;
    font-size: 16px;
    font-weight: bold;
    color: #495057;
    padding: 10px;
    cursor: pointer;
    border-radius: 5px;
    text-align: left;
}

.menu-container .dropdown-btn:hover {
    background-color: #e9ecef;
}

.menu-container .dropdown-content li a {
    font-size: 14px;
}

/* Styling cho content */
.content-container {
    background: #ffffff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    margin-left: 20px;
}

/* Alerts */
.alert {
    border-radius: 5px;
    font-size: 14px;
}

/* Nút đăng xuất */
.menu-container .btn.badge {
    font-size: 14px;
    padding: 5px 10px;
}

/* Responsive cho màn hình nhỏ */
@media (max-width: 768px) {
    .d-flex {
        flex-direction: column;
    }

    .menu-container {
        width: 100%;
        margin-bottom: 20px;
    }

    .content-container {
        margin-left: 0;
    }
}

    </style>


</head>

<body class="container mt-5">
    <div class="d-flex">
        <!-- Phần Menu -->
        <div class="menu-container bg-light p-3">
            <a href="http://127.0.0.1:8000/user/dashboard">
            <div class="nav-profile-text d-flex align-items-center">
              
                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" width="50px" alt="profile" class="img-profile rounded-circle" />
                <span class="login-status online"></span>

                    <div class="ms-2">
                        <span class="d-block" style="color: cadetblue">Xin chào</span>
                        <span style="color: purple">{{ Auth::user()->fullname ?? Auth::user()->email ?? Auth::user()->username }}</span>
                    </div>
            </div>
        </a>
            <ul class="menu list-unstyled">
                <li class="dropdown">
                    <button class="dropdown-btn btn btn-light w-100 text-start" onclick="toggleDropdown(this)">
                        <i class="icon-user"></i> Tài Khoản Của Tôi
                    </button>
                    <ul class="dropdown-content list-unstyled ps-3" style="display: block;"> <!-- Đặt display: block -->
                        <li><a href="{{route('user.edit')}}">Hồ Sơ</a></li>
                        <li><a href="{{route('user.changepass.form')}}">Đổi Mật Khẩu</a></li>
                        <li><a href="#">Cài Đặt Thông Báo</a></li>
                    </ul>
                </li>
                <li><a href="{{route('wallet.index')}}" class="btn btn-light w-100 text-start">Kết nối ví</a></li>
                <li><a href="" class="btn btn-light w-100 text-start">Đơn Mua</a></li>
                <li><a href="{{route('diemdanh.index')}}" class="btn btn-light w-100 text-start">Điểm danh</a></li>
                <li><a href="#" class="btn btn-light w-100 text-start">Thông Báo</a></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn badge bg-danger ms-3 mt-2" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất?')">Đăng xuất</button>
                    </form>
                </li>
            </ul>
        </div>

        <!-- Phần Content -->
        <div class="content-container p-4 flex-grow-1">
            @if ($errors->any())
                <div class="alert alert-danger text-center">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('info'))
                <div class="alert alert-info">
                    {{ session('info') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>


    <!-- Nhúng Bootstrap JavaScript -->
    <script src="{{ asset('user/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
