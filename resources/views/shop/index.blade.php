@extends('LayoutClients.master')

@section('title')
    index
@endsection
<style>
    .fit-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* Cắt ảnh để vừa với khung */
    }

    .image {
        width: 100%;
        height: 250px;
        /* Điều chỉnh chiều cao khung chứa theo ý muốn */
        overflow: hidden;
        /* Đảm bảo phần thừa của ảnh không hiển thị */
    }
</style>

@section('content_client')
    <div class="site-blocks-cover" style="background-image: url({{ asset('client/images/hero_1.jpg') }});" data-aos="fade">
        <div class="container">
            <div class="row align-items-start align-items-md-center justify-content-end">
                <div class="col-md-5 text-center text-md-left pt-5 pt-md-0">
                    <h1 class="mb-2">Finding Your Perfect Shoes</h1>
                    <div class="intro-text text-center text-md-left">
                        <p class="mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus at iaculis
                            quam. Integer accumsan tincidunt fringilla. </p>
                        <p>
                            <a href="#" class="btn btn-sm btn-primary">Shop Now</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="site-section site-blocks-2">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7 site-section-heading text-center pt-4">
                    <h2>New films</h2>
                </div>
            </div>
            <div class="row">
                @foreach ($tickets as $ticket)
                    <div class="col-sm-6 col-md-6 col-lg-4 mb-4 mb-lg-0" data-aos="fade"
                        data-aos-delay="{{ $loop->index * 100 }}">
                        <a class="block-2-item" href="{{ route('ticket.show', $ticket->id) }}">
                            <figure class="image">
                                <img src="{{ asset('storage/' . $ticket->image) }}" alt="{{ $ticket->title }}"
                                    class="img-fluid">
                            </figure>
                            <div class="text">
                                <span class="text-uppercase">{{ $ticket->name }}</span>
                                <h3>{{ $ticket->title }}</h3>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>


    <div class="site-section block-3 site-blocks-2 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7 site-section-heading text-center pt-4">
                    <h2>Featured Products</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="nonloop-block-3 owl-carousel">

                        @foreach ($ticketss as $item)
                            <div class="item">
                                <div class="block-4 text-center">
                                    <figure class="block-4-image">
                                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}"
                                            class="img-fluid">
                                    </figure>
                                    <div class="block-4-text p-4">
                                        <h3><a href="#">{{ $item->name }}</a></h3>
                                        <p class="text-primary font-weight-bold">{{ $item->price }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- <div class="site-section block-8">
        <div class="container">
            <div class="row justify-content-center  mb-5">
                <div class="col-md-7 site-section-heading text-center pt-4">
                    <h2>Big Sale!</h2>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-md-12 col-lg-7 mb-5">
                    <a href="#"><img src="{{ asset('client/images/blog_1.jpg') }}" alt="Image placeholder"
                            class="img-fluid rounded"></a>
                </div>
                <div class="col-md-12 col-lg-5 text-center pl-md-5">
                    <h2><a href="#">50% less in all items</a></h2>
                    <p class="post-meta mb-4">By <a href="#">Carl Smith</a> <span class="block-8-sep">&bullet;</span>
                        September 3, 2018</p>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam iste dolor accusantium facere
                        corporis ipsum animi deleniti fugiat. Ex, veniam?</p>
                    <p><a href="#" class="btn btn-primary btn-sm">Shop Now</a></p>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
