<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Models\Reservation;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class ReservationViewController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $q = Reservation::query()->with(['requester', 'approver']);

        if (! $user->hasAnyRole(['Admin', 'Teknisi'])) {
            $q->where('requester_id', $user->id);
        }

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
        $data['code'] = (new Reservation())->generateCode();

        $reservation = Reservation::create($data);

        // Send notification
        $this->notificationService->notifyReservationCreated($request->user(), $reservation);

        return redirect()->route('reservations.show', $reservation)->with('success', 'Reservation created');
    }

    public function show(Request $request, Reservation $reservation)
    {
        $user = $request->user();

        if (! $user->hasAnyRole(['Admin', 'Teknisi']) && $reservation->requester_id !== $user->id) {
            abort(403);
        }

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
        $oldStatus = $reservation->status;
        $data = $request->validated();
        $isHandler = $request->user()->hasAnyRole(['Admin', 'Teknisi']);

        if ($isHandler) {
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

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->route('reservations.index')->with('success', 'Reservation deleted');
    }
}
