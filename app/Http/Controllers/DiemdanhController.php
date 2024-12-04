<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Attendance;

class DiemdanhController extends Controller
{
    public function index()
    {
        // Lấy người dùng hiện tại
        $user = auth()->user();
        
        // Lấy ngày hôm nay và các thông tin cần thiết
        $today = Carbon::today();
        $attendanceDays = 7; // Tổng số ngày trong tuần (từ thứ 2 đến Chủ Nhật)
    
        // Kiểm tra xem người dùng đã điểm danh đủ 7 ngày trong tuần chưa
        $attendanceCount = $user->attendances()->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $userPoints = $user->point;
    
        // Truyền dữ liệu cho View
        return view('diemdanh.index', compact('user', 'attendanceCount', 'userPoints', 'today'));
    }
    

    /**
     * Xử lý lưu điểm danh
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $today = Carbon::today();

        // Kiểm tra xem đã điểm danh hôm nay chưa
        $alreadyMarked = $user->attendances()->whereDate('date', $today)->exists();
        if ($alreadyMarked) {
            return redirect()->route('diemdanh.index')->with('error', 'Bạn đã điểm danh hôm nay.');
        }

        // Tính điểm thưởng: 20 điểm cho Chủ Nhật, 10 điểm cho các ngày khác
        $dayOfWeek = $today->dayOfWeek;
        $points = ($dayOfWeek == 0) ? 20 : 10;

        // Lưu điểm danh vào cơ sở dữ liệu
        $user->attendances()->create([
            'date' => $today,
            'points' => $points,
        ]);

        // Cộng điểm vào tài khoản người dùng
        $user->increment('point', $points);

        // Kiểm tra nếu đã điểm danh đủ 7 ngày trong tuần thì thưởng thêm 10 điểm
        $startOfWeek = $today->startOfWeek(); // Bắt đầu từ Thứ Hai
        $endOfWeek = $today->endOfWeek();    // Kết thúc vào Chủ Nhật

        $attendanceCount = $user->attendances()
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->count();

        if ($attendanceCount == 7) {
            $user->increment('point', 10);
            return redirect()->route('diemdanh.index')->with('success', 'Điểm danh thành công. Bạn được thưởng thêm 10 điểm vì đã điểm danh đủ 7 ngày trong tuần!');
        }

        return redirect()->route('diemdanh.index')->with('success', 'Điểm danh thành công.');
    }

    public function makeupAttendance(Request $request)
    {
        $user = auth()->user();
        $makeupDate = \Carbon\Carbon::parse($request->date); // Ngày điểm danh bù
        $today = \Carbon\Carbon::today();

        // Kiểm tra ngày điểm danh bù phải là ngày trong quá khứ
        if ($makeupDate >= $today) {
            return redirect()->route('diemdanh.index')->with('error', 'Chỉ có thể điểm danh bù cho các ngày trong quá khứ.');
        }

        // Kiểm tra người dùng đã điểm danh ngày này chưa
        if ($user->attendances()->whereDate('date', $makeupDate)->exists()) {
            return redirect()->route('diemdanh.index')->with('error', 'Bạn đã điểm danh ngày này rồi.');
        }

        // Kiểm tra số lần điểm danh bù trong tháng hiện tại
        $currentMonth = $today->startOfMonth();
        $makeupCount = $user->attendances()
            ->where('is_makeup', true)
            ->whereBetween('date', [$currentMonth, $today])
            ->count();

        if ($makeupCount >= 5) {
            return redirect()->route('diemdanh.index')->with('error', 'Bạn đã đạt giới hạn 5 lần điểm danh bù trong tháng.');
        }

        // Tính điểm: 20 điểm cho Chủ Nhật, 10 điểm cho các ngày khác
        $dayOfWeek = $makeupDate->dayOfWeek;
        $points = ($dayOfWeek == 0) ? 20 : 10;

        // Tạo bản ghi điểm danh bù
        $user->attendances()->create([
            'date' => $makeupDate,
            'points' => $points,
            'is_makeup' => true,
        ]);

        // Cộng điểm vào tổng điểm
        $user->increment('point', $points);

        return redirect()->route('diemdanh.index')->with('success', 'Điểm danh bù thành công.');
    }
}
