<?php

namespace App\Http\Controllers;

use App\Models\PiketSchedule;
use Illuminate\Http\Request;

class PiketScheduleController extends Controller
{
    // Show all piket schedules
    public function index()
    {
        // Get all schedules from database, ordered by week_start_date
        $schedules = PiketSchedule::orderBy('week_start_date', 'asc')->get();
        
        $technicians = PiketSchedule::getTechnicians();

        return view('admin.piket.index', compact('schedules', 'technicians'));
    }

    // Show edit form for specific week
    public function edit($weekStart)
    {
        $weekStartDate = \Carbon\Carbon::parse($weekStart)->startOfWeek();
        $schedule = PiketSchedule::whereDate('week_start_date', $weekStartDate->toDateString())
            ->first() ?? PiketSchedule::createDefault($weekStartDate);

        $technicians = PiketSchedule::getTechnicians();

        return view('admin.piket.edit', compact('schedule', 'technicians'));
    }

    // Update piket schedule
    public function update(Request $request, $weekStart)
    {
        $request->validate([
            'technician_1' => 'required|string',
            'technician_2' => 'required|string|different:technician_1',
            'technician_3' => 'required|string|different:technician_1,technician_2',
        ], [
            'technician_2.different' => 'Petugas 2 tidak boleh sama dengan Petugas 1',
            'technician_3.different' => 'Petugas 3 tidak boleh sama dengan Petugas 1 atau Petugas 2',
        ]);

        $weekStartDate = \Carbon\Carbon::parse($weekStart)->startOfWeek();
        $schedule = PiketSchedule::whereDate('week_start_date', $weekStartDate->toDateString())
            ->first();

        if (!$schedule) {
            $schedule = PiketSchedule::createDefault($weekStartDate);
        }

        $schedule->update($request->only(['technician_1', 'technician_2', 'technician_3']));

        return redirect()->route('piket.index')->with('success', 'Jadwal piket berhasil diperbarui');
    }

    // Show create form for new week schedule
    public function create()
    {
        $technicians = PiketSchedule::getTechnicians();

        return view('admin.piket.create', compact('technicians'));
    }

    // Store new piket schedule
    public function store(Request $request)
    {
        $request->validate([
            'week_start_date' => 'required|date',
            'technician_1' => 'required|string',
            'technician_2' => 'required|string|different:technician_1',
            'technician_3' => 'required|string|different:technician_1,technician_2',
        ], [
            'technician_2.different' => 'Petugas 2 tidak boleh sama dengan Petugas 1',
            'technician_3.different' => 'Petugas 3 tidak boleh sama dengan Petugas 1 atau Petugas 2',
        ]);

        $weekStartDate = \Carbon\Carbon::parse($request->input('week_start_date'))->startOfWeek();

        $schedule = PiketSchedule::firstOrCreate([
            'week_start_date' => $weekStartDate->toDateString(),
        ], [
            'technician_1' => $request->input('technician_1'),
            'technician_2' => $request->input('technician_2'),
            'technician_3' => $request->input('technician_3'),
        ]);

        if (! $schedule->wasRecentlyCreated) {
            $schedule->update($request->only(['technician_1', 'technician_2', 'technician_3']));
        }

        return redirect()->route('piket.index')->with('success', 'Jadwal piket berhasil ditambahkan');
    }

    // Show current piket schedule (for display on welcome page)
    public function show()
    {
        $schedule = PiketSchedule::getCurrentWeek();

        return response()->json([
            'technician_1' => $schedule->technician_1,
            'technician_2' => $schedule->technician_2,
            'technician_3' => $schedule->technician_3,
            'week_start' => $schedule->week_start_date,
        ]);
    }
}
