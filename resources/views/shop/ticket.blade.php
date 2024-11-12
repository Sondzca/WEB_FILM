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
                <h2 class="ticket-title">{{ $ticket->name }}</h2>
                <p class="ticket-description">{{ $ticket->description }}</p>

                <!-- Giá ticket -->
                <p class="ticket-price" style="color: red">{{ number_format($ticket->price, 0, ',', '.') }} VNĐ</p>


                <p>Category: {{ $ticket->category->name }}</p>

                <!-- Thêm vào giỏ hàng hoặc các hành động khác -->
                <form action="" method="">
                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                </form>
            </div>
        </div>
    </div>
@endsection