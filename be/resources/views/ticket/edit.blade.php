@extends('LayoutAdmin.master')

@section('title')
    Dashboard
@endsection

@section('content_admin')
<div class="container">
    <h2>Edit Ticket</h2>
    <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Category</label>
            <select name="category_id" class="form-control">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $ticket->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ $ticket->name }}" maxlength="50" required>
        </div>
        <div class="form-group">
            <label>Image</label>
            <input type="text" name="image" class="form-control" value="{{ $ticket->image }}" maxlength="255" required>
        </div>
        <div class="form-group">
            <label>Start Day</label>
            <input type="datetime-local" name="startday" class="form-control" value="{{ $ticket->startday }}" required>
        </div>
        <div class="form-group">
            <label>End Day</label>
            <input type="datetime-local" name="enday" class="form-control" value="{{ $ticket->enday }}" required>
        </div>
        <div class="form-group">
            <label>Address</label>
            <input type="text" name="address" class="form-control" value="{{ $ticket->address }}" maxlength="100" required>
        </div>
        <div class="form-group">
            <label>Price</label>
            <input type="number" name="price" class="form-control" step="0.01" value="{{ $ticket->price }}" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" maxlength="250">{{ $ticket->description }}</textarea>
        </div>
        <div class="form-group">
            <label>Organizer</label>
            <input type="text" name="nguoitochuc" class="form-control" value="{{ $ticket->nguoitochuc }}">
        </div>
        <div class="form-group">
            <label>Location</label>
            <input type="text" name="noitochuc" class="form-control" value="{{ $ticket->noitochuc }}">
        </div>
        <button type="submit" class="btn btn-primary">Update Ticket</button>
    </form>
</div>
@endsection
