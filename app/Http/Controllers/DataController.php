<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\UsedMaterial;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DataController extends Controller
{
    public function index()
    {
        return Inertia::render('DataUpload', [
            'services' => Service::orderBy('service_name')->get(),
            'materials' => UsedMaterial::orderBy('material_name')->get(),
        ]);
    }

    // Szolgáltatások
    public function storeService(Request $request)
    {
        $request->validate([
            'service_name' => 'required|string|max:255|unique:services,service_name',
        ]);

        Service::create([
            'service_name' => $request->service_name,
        ]);

        return redirect()->back()->with('message', 'Szolgáltatás sikeresen hozzáadva!');
    }

    public function updateService(Request $request, $id)
    {
        $request->validate([
            'service_name' => 'required|string|max:255|unique:services,service_name,' . $id,
        ]);

        $service = Service::findOrFail($id);
        $service->update([
            'service_name' => $request->service_name,
        ]);

        return redirect()->back()->with('message', 'Szolgáltatás sikeresen módosítva!');
    }

    public function destroyService($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return redirect()->back()->with('message', 'Szolgáltatás sikeresen törölve!');
    }

    // Anyagok
    public function storeMaterial(Request $request)
    {
        $request->validate([
            'material_name' => 'required|string|max:255|unique:used_materials,material_name',
        ]);

        UsedMaterial::create([
            'material_name' => $request->material_name,
        ]);

        return redirect()->back()->with('message', 'Anyag sikeresen hozzáadva!');
    }

    public function updateMaterial(Request $request, $id)
    {
        $request->validate([
            'material_name' => 'required|string|max:255|unique:used_materials,material_name,' . $id,
        ]);

        $material = UsedMaterial::findOrFail($id);
        $material->update([
            'material_name' => $request->material_name,
        ]);

        return redirect()->back()->with('message', 'Anyag sikeresen módosítva!');
    }

    public function destroyMaterial($id)
    {
        $material = UsedMaterial::findOrFail($id);
        $material->delete();

        return redirect()->back()->with('message', 'Anyag sikeresen törölve!');
    }
}
