@extends('account.master')

@section('title')
    Forgot
@endsection

@section('content')
    <form action="{{ route('password.fprgot') }}" method="POST">
        @csrf
        <input type="text" id="login" class="fadeIn second" name="email" placeholder="Email" required>
        <input type="submit" class="fadeIn fourth" value="Forgot">
    </form>
@endsection
