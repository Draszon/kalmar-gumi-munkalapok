<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\UsedMaterial;
use Illuminate\Database\Seeder;

class ServicesAndMaterialsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Új szolgáltatások (a meglévőkhöz hozzáadva)
        $services = [
            'Kerékcsere',
            'Kerék centrírozás',
            'Defektjavítás',
            'Szelepcsere',
            'Nitrogén töltés',
            'Futómű beállítás',
            'Kerékkiegyensúlyozás',
            'Felni egyengetés',
            'Felni polírozás',
            'Gumiabroncs tárolás',
            'Téli/nyári átszerelés',
        ];

        foreach ($services as $serviceName) {
            Service::firstOrCreate(['service_name' => $serviceName]);
        }

        // Új felhasznált anyagok (a meglévőkhöz hozzáadva)
        $materials = [
            'Szelep',
            'Szelepsapka',
            'Centírozó gyűrű',
            'Kerékcsavar',
            'Keréktárcsa',
            'Felnivédő',
            'Gumiragasztó',
            'Defektjavító készlet',
            'TPMS szenzor',
            'Kerékanya kupak',
            'Súly (ólom)',
            'Súly (cink)',
            'Abroncs fényező',
            'Felnitisztító',
        ];

        foreach ($materials as $materialName) {
            UsedMaterial::firstOrCreate(['material_name' => $materialName]);
        }
    }
}
