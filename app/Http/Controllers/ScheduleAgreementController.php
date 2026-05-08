<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreScheduleAgreementRequest;
use App\Http\Requests\UpdateScheduleAgreementRequest;
use App\Models\ScheduleAgreement;
use Illuminate\Http\Request;

class ScheduleAgreementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ScheduleAgreement::query();

        if ($request->filled('search')) {
            $query->where('keterangan', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_sa', $request->tanggal);
        }

        if ($request->filled('status')) {
            $query->where('status_sa', $request->status);
        }

        $sas = $query->latest('tanggal_sa')->paginate(10);

        return view('sa.index', compact('sas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreScheduleAgreementRequest $request)
    {
        $data = $request->validated();
        $data['status_sa'] = 'pending';

        ScheduleAgreement::create($data);

        return redirect()->route('schedule-agreement.index')
            ->with('success', 'Schedule Agreement berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ScheduleAgreement $scheduleAgreement)
    {
        $scheduleAgreement->load('penebusans.truck', 'penebusans.driver');
        return view('sa.show', compact('scheduleAgreement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ScheduleAgreement $scheduleAgreement)
    {
        return view('sa.edit', compact('scheduleAgreement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateScheduleAgreementRequest $request, ScheduleAgreement $scheduleAgreement)
    {
        $data = $request->validated();

        $scheduleAgreement->update($data);

        return redirect()->route('schedule-agreement.index')
            ->with('success', 'Schedule Agreement berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ScheduleAgreement $scheduleAgreement)
    {
        // Check if has penebusans
        if ($scheduleAgreement->penebusans()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus SA yang sudah memiliki data penebusan.');
        }

        $scheduleAgreement->delete();

        return redirect()->route('schedule-agreement.index')
            ->with('success', 'Schedule Agreement berhasil dihapus.');
    }
}
