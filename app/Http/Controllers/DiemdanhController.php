<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Attendance;

class DiemdanhController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = Carbon::today();
        $dayOfWeek = $today->dayOfWeek;

        // Kiểm tra xem đã điểm danh hôm nay chưa
        $hasAttendedToday = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->exists();

        // Chỉ cho phép điểm danh trong ngày hiện tại
        $canMarkAttendance = !$hasAttendedToday;

        // Lấy thông tin điểm danh trong tuần này
        $weeklyAttendance = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])
            ->get();

        return view('diemdanh.index', compact(
            'user',
            'dayOfWeek',
            'canMarkAttendance',
            'weeklyAttendance'
        ));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $now = Carbon::now();

        // Kiểm tra xem đã điểm danh hôm nay chưa
        if (Attendance::where('user_id', $user->id)
            ->whereDate('date', $now->toDateString())
            ->exists()
        ) {
            return redirect()
                ->route('diemdanh.index')
                ->with('error', 'Bạn đã điểm danh hôm nay rồi.');
        }

        // Tính điểm dựa vào ngày trong tuần
        $points = ($now->dayOfWeek === Carbon::SUNDAY) ? 20 : 10;

        // Tạo bản ghi điểm danh mới
        Attendance::create([
            'user_id' => $user->id,
            'points' => $points,
            'date' => $now->toDateString()
        ]);

        // Cập nhật điểm cho user
        $newPoints = $user->point + $points;

        // Kiểm tra điểm danh trong tuần
        $weeklyCount = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [
                $now->copy()->startOfWeek(),
                $now->copy()->endOfWeek()
            ])
            ->count();

        if ($weeklyCount === 7) {
            $newPoints += 10; // Thêm 10 điểm thưởng
            $message = 'Điểm danh thành công! Bạn được thưởng thêm 10 điểm vì đã điểm danh đủ 7 ngày!';
        } else {
            $message = 'Điểm danh thành công!';
        }

        // Cập nhật tổng điểm của user
        $user->update([
            'point' => $newPoints
        ]);

        return redirect()
            ->route('diemdanh.index')
            ->with('success', $message);
    }
}
