<nav class="site-navigation text-right text-md-center" role="navigation">
    <div class="container">
        <ul class="site-menu js-clone-nav d-none d-md-block">
            <li><a href="{{ route('index') }}">Home</a></li>
            <li class="has-children active">
                <a href="{{ route('index') }}">Category</a>
                <ul class="dropdown">
                    @foreach ($categories as $category)
                        <li><a href="#">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
            </li>
            <li><a href="{{ route('shop') }}">Shop</a></li>
            <li><a href="{{ route('about') }}">About</a></li>
            <li><a href="{{ route('contact') }}">Contact</a></li>

        </ul>
    </div>
</nav>
