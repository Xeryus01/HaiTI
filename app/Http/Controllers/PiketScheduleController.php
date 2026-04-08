<?php

namespace App\Http\Controllers;

use App\Models\PiketSchedule;
use Illuminate\Http\Request;

class PiketScheduleController extends Controller
{
    // Show all piket schedules
    public function index()
    {
        $schedules = PiketSchedule::orderBy('year', 'desc')->orderBy('month', 'desc')->get();
        $technicians = PiketSchedule::getTechnicians();

        return view('admin.piket.index', compact('schedules', 'technicians'));
    }

    // Show edit form for specific month
    public function edit($month)
    {
        $currentYear = date('Y');
        $schedule = PiketSchedule::where('month', $month)
            ->where('year', $currentYear)
            ->first() ?? PiketSchedule::createDefault($month, $currentYear);

        $technicians = PiketSchedule::getTechnicians();
        $monthNames = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return view('admin.piket.edit', compact('schedule', 'technicians', 'monthNames'));
    }

    // Update piket schedule
    public function update(Request $request, $month)
    {
        $request->validate([
            'lantai_1' => 'required|string',
            'lantai_2' => 'required|string',
            'tu' => 'required|string',
        ]);

        $currentYear = date('Y');
        $schedule = PiketSchedule::where('month', $month)
            ->where('year', $currentYear)
            ->first();

        if (!$schedule) {
            $schedule = PiketSchedule::createDefault($month, $currentYear);
        }

        $schedule->update($request->only(['lantai_1', 'lantai_2', 'tu']));

        return redirect()->route('piket.index')->with('success', 'Jadwal piket berhasil diperbarui');
    }

    // Show current piket schedule (for display on welcome page)
    public function show()
    {
        $schedule = PiketSchedule::getCurrentMonth();

        return response()->json([
            'lantai_1' => $schedule->lantai_1,
            'lantai_2' => $schedule->lantai_2,
            'tu' => $schedule->tu,
        ]);
    }
}
