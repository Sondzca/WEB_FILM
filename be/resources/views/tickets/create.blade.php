@extends('LayoutAdmin.master')

@section('title')
    Add new ticket
@endsection

@section('content_admin')
<div class="container">
    <h2 class="text-center"> Add new ticket</h2>
    <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group mb-3">
            <label>Category</label>
            <select name="category_id" class="form-control">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group mt-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" maxlength="50" value="{{ old('name') }}" required>
        </div>

        <div class="form-group mt-3">
            <label>Image</label>
            <input type="file" name="image" class="form-control" id="image" maxlength="255" required>
            <img id="preview-image" src="#" alt="Image Preview" style="display: none; margin-top: 10px; width: 150px; height: 100px;" />
        </div>

        <div class="form-group mt-3">
            <label>Start Day</label>
            <input type="date" name="startday" class="form-control" value="{{ old('startday') }}" required>
        </div>

        <div class="form-group mt-3">
            <label>End Day</label>
            <input type="date" name="enday" class="form-control" value="{{ old('enday') }}" required>
        </div>

        <div class="form-group mt-3">
            <label>Quantity</label>
            <input type="number" name="quantity" class="form-control" value="{{ old('quantity') }}" required>
        </div>

        <div class="form-group mt-3">
            <label>Price</label>
            <input type="number" name="price" class="form-control" step="0.01" value="{{ old('price') }}" required>
        </div>

        <div class="form-group mt-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="10">{{ old('description') }}</textarea>
        </div>

        <div class="form-group mt-3">
            <label>Organizer</label>
            <input type="text" name="nguoitochuc" class="form-control" value="{{ old('nguoitochuc') }}">
        </div>

        <div class="form-group mt-3">
            <label>Address</label>
            <input type="text" name="address" class="form-control" maxlength="100" value="{{ old('address') }}" required>
        </div>

        <div class="form-group mt-3">
            <label>IS_ACTIVE</label>
            <input type="checkbox" name="is_active" value="1" class="mb-4 ms-3">
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Create Ticket</button>
            <a href="{{route('tickets.index')}}" class="btn btn-secondary">Quay lại</a>
        </div>
        
    </form>
</div>

<script>
    // Lắng nghe sự kiện thay đổi (khi chọn file)
    document.getElementById('image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            // Hiển thị hình ảnh đã chọn trong thẻ img
            const preview = document.getElementById('preview-image');
            preview.src = e.target.result;
            preview.style.display = 'block'; // Hiển thị ảnh
        };

        // Đọc file hình ảnh
        if (file) {
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
