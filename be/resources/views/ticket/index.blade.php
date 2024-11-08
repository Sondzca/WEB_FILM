@extends('LayoutAdmin.master')

@section('title')
    Dashboard
@endsection

@section('content_admin')
<div class="container">
    <h2>Tickets List</h2>
    <a href="{{ route('tickets.create') }}" class="btn btn-success mb-3">Add New Ticket</a>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Category</th>
                <th>Name</th>
                <th>Image</th>
                <th>Start Day</th>
                <th>End Day</th>
                <th>Address</th>
                <th>Price</th>
                <th>Description</th>
                <th>Organizer</th>
                <th>Location</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
            <tr>
                <td>{{ $ticket->id }}</td>
                <td>{{ $ticket->category->name ?? 'No Category' }}</td>
                <td>{{ $ticket->name }}</td>
                <td><img src="{{ asset('storage/' . $ticket->image) }}" alt="{{ $ticket->name }}" width="50"></td>
                <td>{{ $ticket->startday }}</td>
                <td>{{ $ticket->enday }}</td>
                <td>{{ $ticket->address }}</td>
                <td>{{ $ticket->price }}</td>
                <td>{{ $ticket->description }}</td>
                <td>{{ $ticket->nguoitochuc }}</td>
                <td>{{ $ticket->noitochuc }}</td>
                <td>
                    <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-primary btn-sm">Edit</a>
                    <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $tickets->links() }}
</div>
@endsection
