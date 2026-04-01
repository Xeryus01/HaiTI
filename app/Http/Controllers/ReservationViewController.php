<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Models\Reservation;
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

        $reservations = $q->latest()->paginate(15);
        return view('reservations.index', compact('reservations'));
    }

    public function create()
    {
        return view('reservations.create');
    }

    public function store(StoreReservationRequest $request)
    {
        $data = $request->validated();
        $data['requester_id'] = $request->user()->id;
        $data['status'] = 'PENDING';
        $data['code'] = Reservation::generateCode();

        // Handle nota dinas upload
        if ($request->hasFile('nota_dinas')) {
            $file = $request->file('nota_dinas');
            $path = $file->store('nota_dinas', 'public');
            $data['nota_dinas_path'] = $path;
        }

        $reservation = Reservation::create($data);

        // Send notification
        $this->notificationService->notifyReservationCreated($request->user(), $reservation);

        return redirect()->route('reservations.show', $reservation)->with('success', 'Pengajuan Zoom berhasil dibuat.');
    }

    public function show(Request $request, Reservation $reservation)
    {
        $reservation->load(['requester', 'approver']);

        return view('reservations.show', compact('reservation'));
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

        $oldStatus = $reservation->status;
        $data = $request->validated();
        $isApprover = $user->hasPermissionTo('approve reservations');

        if ($isApprover) {
            $data['approver_id'] = $request->user()->id;
        } else {
            unset($data['status'], $data['zoom_link'], $data['notes'], $data['approver_id']);
        }

        $reservation->update($data);

        if ($oldStatus !== $reservation->status && $reservation->status === 'APPROVED') {
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
