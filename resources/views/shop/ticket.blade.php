@extends('LayoutClients.master')

@section('title')
    {{ $ticket->name }}
@endsection


@section('content_client')
    <div class="container ticket-detail-container">
        <div class="row">
            <div class="col-lg-4">
                <figure class="ticket-image">
                    <img style="max-width: 100%" src="{{ asset('storage/' . $ticket->image) }}" alt="{{ $ticket->name }}"
                        class="img-fluid">
                </figure>
            </div>
            <div class="col-lg-8">
                <!-- Hình ảnh của ticket -->


                <!-- Tên ticket -->
                <h2 class="ticket-title">{{ $ticket->name }}</h2>

                <!-- Mô tả ticket -->
                <p class="ticket-description">{{ $ticket->description }}</p>

                <!-- Giá ticket -->
                <p class="ticket-price">${{ $ticket->price }}</p>

                <!-- Thêm các thông tin khác nếu có -->
                <!-- Ví dụ: -->
                <p>Category: {{ $ticket->category->name }}</p> <!-- Nếu bạn có quan hệ với Category -->

                <!-- Thêm vào giỏ hàng hoặc các hành động khác -->
                <form action="" method="">
                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                </form>
            </div>
        </div>
    </div>
@endsection
