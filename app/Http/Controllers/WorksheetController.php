<?php

namespace App\Http\Controllers;

use App\Models\WorkSheet;
use App\Models\Service;
use App\Models\UsedMaterial;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;

class WorksheetController extends Controller
{
    /**
     * Nyitott munkalapok listázása
     */
    public function opened()
    {
        $worksheets = WorkSheet::where('is_closed', false)
            ->orderBy('created_at', 'desc')
            ->get();

        $services = Service::all();
        $materials = UsedMaterial::all();

        return Inertia::render('OpenedWorks', [
            'worksheets' => $worksheets,
            'services' => $services,
            'materials' => $materials,
        ]);
    }

    /**
     * Zárt munkalapok listázása (paginált, 30/oldal)
     */
    public function closed()
    {
        $worksheets = WorkSheet::where('is_closed', true)
            ->orderBy('closed_at', 'desc')
            ->paginate(30);

        $services = Service::all();
        $materials = UsedMaterial::all();

        return Inertia::render('ClosedWorks', [
            'worksheets' => $worksheets,
            'services' => $services,
            'materials' => $materials,
        ]);
    }

    /**
     * Munkalap lezárása
     */
    public function close($id)
    {
        $worksheet = WorkSheet::findOrFail($id);
        $worksheet->update([
            'is_closed' => true,
            'closed_at' => now()
        ]);

        return redirect()->back()->with('message', 'Munkalap sikeresen lezárva!');
    }

    /**
     * Munkalap újranyitása
     */
    public function reopen($id)
    {
        $worksheet = WorkSheet::findOrFail($id);
        $worksheet->update([
            'is_closed' => false,
            'closed_at' => null
        ]);

        return redirect()->back()->with('message', 'Munkalap sikeresen újranyitva!');
    }

    /**
     * Munkalap frissítése
     */
    public function update(Request $request, $id)
    {
        $worksheet = WorkSheet::findOrFail($id);
        
        $worksheet->update([
            'registration_number' => $request->registration_number,
            'name' => $request->name,
            'car_type' => $request->car_type,
            'services' => $request->services,
            'used_materials' => $request->used_materials,
            'tire_brand' => $request->tire_brand,
            'tire_size' => $request->tire_size,
            'store' => $request->store,
            'store_qty' => $request->store_qty,
            'store_tire' => $request->store_tire,
            'store_wheel' => $request->store_wheel,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('message', 'Munkalap sikeresen frissítve!');
    }

    /**
     * Munkalap törlése
     */
    public function destroy($id)
    {
        $worksheet = WorkSheet::findOrFail($id);
        $worksheet->delete();

        return redirect()->back()->with('message', 'Munkalap sikeresen törölve!');
    }

    /**
     * Munkalap letöltése PDF formátumban
     */
    public function downloadPdf($id)
    {
        $worksheet = WorkSheet::findOrFail($id);
        
        $pdf = Pdf::loadView('pdf.worksheet', ['worksheet' => $worksheet]);
        
        $filename = 'munkalap_' . $worksheet->registration_number . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }
}
