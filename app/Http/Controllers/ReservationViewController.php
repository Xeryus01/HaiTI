<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Models\Reservation;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReservationViewController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $q = Reservation::query()->with(['requester', 'approver']);

        if ($request->filled('status')) {
            $q->where('status', $request->input('status'));
        }

        $reservations = $q->latest()->paginate(15)->withQueryString();
        return view('reservations.index', compact('reservations'));
    }

    public function create()
    {
        return view('reservations.create');
    }

    public function store(StoreReservationRequest $request)
    {
        $data = $request->validated();
        
        // Add the converted datetime fields if they were provided
        if ($request->filled('start_time_local')) {
            $startTime = $request->input('start_time_local');
            if (strlen($startTime) == 16) {
                $startTime .= ':00';
            }
            $data['start_time'] = $startTime;
        }

        if ($request->filled('end_time_local')) {
            $endTime = $request->input('end_time_local');
            if (strlen($endTime) == 16) {
                $endTime .= ':00';
            }
            $data['end_time'] = $endTime;
        }
        
        $data['requester_id'] = $request->user()->id;
        $data['status'] = Reservation::STATUS_PENDING;
        $data['code'] = Reservation::generateCode();
        $data['operator_needed'] = $request->boolean('operator_needed');
        $data['breakroom_needed'] = $request->boolean('breakroom_needed');
        $data['participants_count'] = $request->input('participants_count', 1);

        // Handle nota dinas upload
        if ($request->hasFile('nota_dinas')) {
            $file = $request->file('nota_dinas');
            $path = $file->store('nota_dinas', 'public');
            $data['nota_dinas_path'] = $path;
        }

        $reservation = Reservation::create($data);

        // Send notification
        $this->notificationService->notifyReservationCreated($request->user(), $reservation);

        return redirect()->route('reservations.index')->with('success', 'Pengajuan Zoom berhasil dibuat.');
    }

    public function show(Request $request, Reservation $reservation)
    {
        $reservation->load(['requester', 'approver']);

        $technicians = collect();
        if ($request->user() && $request->user()->hasRole('Admin')) {
            $technicians = User::whereHas('roles', function ($q) {
                $q->where('name', 'Teknisi');
            })->get();
        }

        return view('reservations.show', compact('reservation', 'technicians'));
    }

    public function edit(Request $request, Reservation $reservation)
    {
        $user = $request->user();

        if (! $user->hasAnyRole(['Admin', 'Teknisi']) && $reservation->requester_id !== $user->id) {
            abort(403);
        }

        return view('reservations.edit', compact('reservation'));
    }

    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        $user = $request->user();

        if (! $user->hasAnyRole(['Admin', 'Teknisi']) && $reservation->requester_id !== $user->id) {
            abort(403);
        }

        // Only Admin can assign petugas, but both Admin and Teknisi can approve reservations
        if (! $user->hasPermissionTo('approve reservations') && $request->filled('status')) {
            abort(403, 'Anda tidak memiliki izin untuk menyetujui pengajuan Zoom.');
        }

        if ($request->filled('approver_id') && ! $user->hasRole('Admin')) {
            abort(403, 'Hanya Admin yang dapat menugaskan petugas.');
        }

        $oldStatus = $reservation->status;
        $data = $request->validated();
        $isApprover = $user->hasPermissionTo('approve reservations');

        if ($user->hasRole('Admin')) {
            if ($request->has('approver_id')) {
                $data['approver_id'] = $request->input('approver_id') ?: null;
            } else {
                unset($data['approver_id']);
            }
        } elseif ($isApprover) {
            $data['approver_id'] = $request->user()->id;
        } else {
            unset($data['approver_id']);
        }

        // Add the converted datetime fields if they were provided
        if ($request->filled('start_time_local')) {
            $startTime = $request->input('start_time_local');
            if (strlen($startTime) == 16) {
                $startTime .= ':00';
            }
            $data['start_time'] = $startTime;
        }

        if ($request->filled('end_time_local')) {
            $endTime = $request->input('end_time_local');
            if (strlen($endTime) == 16) {
                $endTime .= ':00';
            }
            $data['end_time'] = $endTime;
        }

        $data['operator_needed'] = $request->boolean('operator_needed');
        $data['breakroom_needed'] = $request->boolean('breakroom_needed');
        $data['participants_count'] = $request->input('participants_count', $reservation->participants_count ?? 1);

        if ($isApprover) {
            $data['approver_id'] = $request->user()->id;
        } else {
            unset($data['status'], $data['zoom_link'], $data['zoom_record_link'], $data['notes'], $data['approver_id']);
        }

        // Handle nota dinas upload if provided
        if ($request->hasFile('nota_dinas')) {
            // Delete old file if exists
            if ($reservation->nota_dinas_path && Storage::disk('public')->exists($reservation->nota_dinas_path)) {
                Storage::disk('public')->delete($reservation->nota_dinas_path);
            }

            $file = $request->file('nota_dinas');
            $path = $file->store('nota_dinas', 'public');
            $data['nota_dinas_path'] = $path;
        }

        $reservation->update($data);

        if ($oldStatus !== $reservation->status && $reservation->status === Reservation::STATUS_APPROVED) {
            $this->notificationService->notifyReservationApproved($reservation->requester, $reservation);
        }

        return redirect()->route('reservations.show', $reservation)->with('success', 'Pengajuan Zoom berhasil diperbarui.');
    }

    public function destroy(Request $request, Reservation $reservation)
    {
        $user = $request->user();

        if (! $user->hasAnyRole(['Admin', 'Teknisi']) && $reservation->requester_id !== $user->id) {
            abort(403);
        }

        $reservation->delete();
        return redirect()->route('reservations.index')->with('success', 'Reservation deleted');
    }

    public function showNotaDinas(Request $request, Reservation $reservation)
    {
        if (!$reservation->nota_dinas_path) {
            abort(404);
        }

        $diskName = Storage::disk('public')->exists($reservation->nota_dinas_path) ? 'public' : 'local';
        $disk = Storage::disk($diskName);

        if (! $disk->exists($reservation->nota_dinas_path)) {
            abort(404);
        }

        return $disk->response($reservation->nota_dinas_path, 'nota_dinas_' . $reservation->code . '.pdf', [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="nota_dinas_' . $reservation->code . '.pdf"',
        ]);
    }
}
