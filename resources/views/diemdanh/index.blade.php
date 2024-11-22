@extends('LayoutUser.master')

@section('title')
    Điểm danh
@endsection

@section('content')
    <?php
    use Carbon\Carbon;
    ?>
    <div class="container">
        <h2 class="text-center">Điểm Danh Nhận Thưởng</h2>
    
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
    
        <div class="row mt-4">
            @foreach (range(0, 6) as $day)
                @php
                    $date = Carbon::now()->startOfWeek()->addDays($day);
                    $attended = $user->attendances()->whereDate('date', $date)->exists(); // Kiểm tra đã điểm danh
                    $today = Carbon::today();
                    $isPastDay = $date->isBefore($today);
                    $isFutureDay = $date->isAfter($today);
                @endphp
    
                <div class="col-md-1 text-center">
                    <div
                        class="day {{ $attended ? 'attended' : '' }} {{ $today->isSameDay($date) ? 'today' : '' }} {{ $isFutureDay ? 'future-day' : '' }} {{ $isPastDay ? 'past-day' : '' }}">
                        <div class="day-header">
                            {{ $date->format('D') }}
                        </div>
    
                        @if ($attended)
                            <span class="text-success">✔</span>
                        @else
                            @if ($isPastDay)
                                {{-- Nút điểm danh bù --}}
                                <form action="{{ route('diemdanh.makeup') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="date" value="{{ $date }}">
                                    <button type="submit" class="btn btn-sm btn-warning">
                                        Makeup
                                    </button>
                                </form>
                            @elseif (!$isFutureDay)
                                {{-- Nút điểm danh hiện tại --}}
                                <form action="{{ route('diemdanh.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="date" value="{{ $date }}">
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        Mark
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    
        <!-- Hiển thị tổng điểm -->
        <p class="text-center mt-4">Current Points: {{ $userPoints }}</p>
    
        <!-- Hiển thị lịch sử điểm danh -->
        <h3 class="mt-5 text-center">Lịch sử điểm danh</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Ngày</th>
                    <th>Điểm</th>
                    <th>Loại điểm danh</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($user->attendances as $attendance)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }}</td>
                    <td>{{ $attendance->points }}</td>
                    <td>{{ $attendance->is_makeup ? 'Điểm danh bù' : 'Điểm danh thường' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    
        <!-- Hiển thị thông tin tổng số ngày đã điểm danh trong tuần -->
        <p class="text-center mt-4">
            <strong>Điểm danh tuần này:</strong> {{ $attendanceCount }} / 7 ngày
        </p>
    </div>
    

        </div>

        
    </div>

    <style>
        /* Styling cho các ô ngày */
.day {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 10px;
    margin: 10px auto;
    transition: all 0.3s ease;
    font-size: 14px;
}

.day-header {
    font-size: 1rem;
    font-weight: bold;
    margin-bottom: 10px;
}

.today {
    background-color: #ffc107; /* Vàng cho ngày hiện tại */
    color: #333;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
}

.attended {
    background-color: #28a745; /* Xanh lá cho đã điểm danh */
    color: white;
    font-weight: bold;
    box-shadow: 0px 4px 6px rgba(40, 167, 69, 0.4);
}

.past-day {
    background-color: #6c757d; /* Xám đậm cho ngày đã qua */
    color: white;
    opacity: 0.8;
}

.future-day {
    background-color: #e0e0e0; /* Xám nhạt cho ngày tương lai */
    color: #aaa;
    cursor: not-allowed;
}

/* Nút bấm */
button {
    font-size: 12px;
    padding: 5px 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
}

button.btn-sm {
    padding: 6px 12px;
    font-size: 14px;
}

button.btn-primary {
    background-color: #007bff;
    color: white;
}

button.btn-primary:hover {
    background-color: #0056b3;
}

button.btn-warning {
    background-color: #ffc107;
    color: #333;
}

button.btn-warning:hover {
    background-color: #d39e00;
}

button.disabled {
    background-color: #dcdcdc;
    cursor: not-allowed;
    pointer-events: none;
}

/* Bảng lịch sử điểm danh */
.table {
    margin-top: 30px;
    font-size: 14px;
    border-collapse: collapse;
    width: 100%;
}

.table th, .table td {
    padding: 12px;
    text-align: center;
    border: 1px solid #ddd;
}

.table th {
    background-color: #f4f4f4;
    font-weight: bold;
}

.table tbody tr:hover {
    background-color: #f9f9f9;
}

/* Tổng điểm và các thông tin khác */
.text-center {
    font-size: 16px;
    margin-top: 20px;
    font-weight: bold;
}

.alert {
    margin-top: 20px;
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 14px;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* Responsive cho màn hình nhỏ */
@media (max-width: 768px) {
    .day {
        font-size: 12px;
        padding: 10px;
    }

    .day-header {
        font-size: 1rem;
    }

    .table {
        font-size: 12px;
    }
}

    </style>
@endsection
