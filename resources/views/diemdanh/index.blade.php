@extends('LayoutUser.master')

@section('title')
    Điểm danh
@endsection

@section('content')
    <?php
    use Carbon\Carbon;
    ?>
    <div class="container">
        <h2 class="text-center">Attendance</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row">
            @foreach (range(0, 6) as $day)
                @php
                    $date = Carbon::now()->startOfWeek()->addDays($day);
                    $attended = $user->point > 0 && $date->isToday(); // Check if the user has attended today or not
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
                            <form action="{{ route('diemdanh.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="date" value="{{ $date }}">
                                <button type="submit"
                                    class="btn btn-sm btn-primary {{ $isFutureDay ? 'disabled' : '' }} {{ $isPastDay && !$attended ? 'highlight-button' : '' }}">
                                    Mark
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <p class="text-center mt-4">Current Points: {{ $user->point }}</p>
    </div>

    <style>
        .today {
            background-color: #ffd700;
            /* Yellow for today */
            color: white;
        }

        .attended {
            background-color: #28a745;
            /* Green for attended */
            color: white;
        }

        .past-day {
            background-color: #6c757d;
            /* Secondary color for past days */
            color: white;
        }

        .future-day {
            background-color: #e0e0e0;
            /* Gray for future days */
            color: #aaa;
        }

        .day {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
            transition: background-color 0.3s ease;
        }

        .day-header {
            font-size: 1.25rem;
            font-weight: bold;
        }

        .highlight-button {
            background-color: #ffc107;
            /* Highlight button for unmarked past days */
            color: black;
            border: none;
        }

        .disabled {
            background-color: #dcdcdc;
            pointer-events: none;
            cursor: not-allowed;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
    </style>
@endsection
