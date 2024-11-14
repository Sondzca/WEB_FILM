<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class DiemdanhController extends Controller
{
    public function index()
    {
        // Get current date and day of the week
        $user = auth()->user();
        $today = Carbon::today();
        $dayOfWeek = $today->dayOfWeek; // Get today's day number (0 = Sunday, 1 = Monday, ..., 6 = Saturday)

        // Determine if the user can mark attendance today
        $canMarkAttendance = $dayOfWeek >= Carbon::parse($today)->dayOfWeek; // You can only mark attendance for today or future days

        return view('diemdanh.index', compact('user', 'dayOfWeek', 'canMarkAttendance'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $today = Carbon::today();
        $dayOfWeek = $today->dayOfWeek;

        // Prevent attendance for past days
        if ($dayOfWeek > $today->dayOfWeek) {
            return redirect()->route('diemdanh.index')->with('error', 'You cannot mark attendance for past days.');
        }

        // Calculate points for today (10 points for weekdays, 20 for Sunday)
        $points = ($dayOfWeek == 0) ? 20 : 10; // 20 points for Sunday, 10 points for other days

        // Update user's points
        $user->increment('point', $points);

        // Check if all 7 days of attendance are marked (check if the user has attended each day of the week)
        $attendanceDays = 7; // Total number of days in the week (from Monday to Sunday)
        $userPoints = $user->point;

        // If the user has attended every day of the week (7 days), reward with extra 10 points
        if ($user->attendance()->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count() == $attendanceDays) {
            $user->increment('point', 10);
        }

        return redirect()->route('diemdanh.index')->with('success', 'Attendance marked successfully.');
    }
}
