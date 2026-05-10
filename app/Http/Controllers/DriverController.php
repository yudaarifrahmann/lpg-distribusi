<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDriverRequest;
use App\Http\Requests\UpdateDriverRequest;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;

class DriverController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $role = $request->get('role');

        $query = Driver::with('user');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nomor_hp', 'like', '%' . $search . '%');
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($role) {
            $query->where('role_pekerjaan', $role);
        }

        $query->orderBy('nama', 'asc');

        $drivers = $query->paginate(10);

        return view('master-data.driver.index', compact('drivers', 'search', 'status', 'role'));
    }

    /**
     * Show the form for creating a new resource — returns available users for the modal.
     */
    public function create()
    {
        // Get users with supir_knek role that are not already assigned to a driver.
        $assignedUserIds = Driver::withTrashed()->pluck('user_id')->toArray();
        $query = User::role('supir_knek')
                     ->with('roles')
                     ->whereNotIn('id', $assignedUserIds);

        if (!auth()->user()->hasRole('superadmin') && auth()->user()->branch_id) {
            $query->where('branch_id', auth()->user()->branch_id);
        }

        $users = $query->get();
        $trucks = \App\Models\Truck::where('status_kendaraan', 'aktif')->get();

        return response()->json([
            'users' => $users,
            'trucks' => $trucks
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDriverRequest $request)
    {
        $validated = $request->validated();

        foreach ($validated['drivers'] as $driverData) {
            Driver::create($driverData);
        }

        return redirect()->route('driver.index')
                        ->with('success', count($validated['drivers']) . ' Supir/Knek berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Driver $driver)
    {
        $driver->load('user');
        return view('master-data.driver.show', compact('driver'));
    }

    /**
     * Show the form for editing the specified resource — returns driver data + available users.
     */
    public function edit(Driver $driver)
    {
        $assignedUserIds = Driver::withTrashed()
                                ->where('id', '!=', $driver->id)
                                ->pluck('user_id')
                                ->toArray();
        $query = User::role('supir_knek')
                     ->with('roles')
                     ->whereNotIn('id', $assignedUserIds);

        if (!auth()->user()->hasRole('superadmin') && auth()->user()->branch_id) {
            $query->where('branch_id', auth()->user()->branch_id);
        }

        $users = $query->get();
        $trucks = \App\Models\Truck::where('status_kendaraan', 'aktif')->get();

        return response()->json([
            'driver' => $driver->load('user'),
            'users' => $users,
            'trucks' => $trucks,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDriverRequest $request, Driver $driver)
    {
        $driver->update($request->validated());

        return redirect()->route('driver.index')
                        ->with('success', 'Supir/Knek berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(Driver $driver)
    {
        $driver->delete();

        return redirect()->route('driver.index')
                        ->with('success', 'Supir/Knek berhasil dihapus');
    }
}
