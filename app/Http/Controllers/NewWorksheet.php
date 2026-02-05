<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\UsedMaterial;
use App\Models\WorkSheet;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NewWorksheet extends Controller
{
    public function index() {
        $services = Service::all();
        $materials = UsedMaterial::all();

        return Inertia::render('Work', [
            'services'  => $services,
            'materials' => $materials,
        ]);
    }

    public function store(Request $request) {
        
        $validated = $request->validate([
            'registration_number'   => 'required|string',
            'name'                  => 'nullable|string',
            'car_type'              => 'nullable|string',
            'used_materials'        => 'nullable|array',
            'services'              => 'nullable|array',
            'tire_brand'            => 'nullable|string',
            'tire_size'             => 'nullable|string',
            'store'                 => 'nullable|boolean',
            'store_qty'             => 'nullable|integer|min:1',
            'store_tire'            => 'nullable|boolean',
            'store_wheel'           => 'nullable|boolean',
            'comment'               => 'nullable|string'
        ]);
        
        try {
            $work = new WorkSheet;
            $work->create($validated);

            return redirect()->back()->with('message', 'Sikeres adatfeltöltés!');
        } catch (\Exception $e) {
            return redirect()->back()->with('message', 'Hiba az adatok feltöltése közben: '. $e->getMessage());
        }
    }
}
