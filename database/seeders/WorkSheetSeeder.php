<?php

namespace Database\Seeders;

use App\Models\WorkSheet;
use Illuminate\Database\Seeder;

class WorkSheetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Magyar kereszt- és vezetéknevek
        $firstNames = ['János', 'István', 'József', 'László', 'Ferenc', 'Zoltán', 'Gábor', 'Attila', 'Péter', 'Tamás', 'Tibor', 'András', 'Sándor', 'Imre', 'Béla', 'Mária', 'Erzsébet', 'Katalin', 'Éva', 'Anna', 'Judit', 'Krisztina', 'Ágnes', 'Andrea', 'Zsuzsanna'];
        $lastNames = ['Nagy', 'Kovács', 'Tóth', 'Szabó', 'Horváth', 'Varga', 'Kiss', 'Molnár', 'Németh', 'Farkas', 'Balogh', 'Papp', 'Lakatos', 'Takács', 'Juhász', 'Mészáros', 'Oláh', 'Simon', 'Rácz', 'Fekete'];

        // Autó típusok
        $carTypes = [
            'Opel Astra', 'Opel Corsa', 'Opel Insignia', 'Opel Mokka',
            'Volkswagen Golf', 'Volkswagen Passat', 'Volkswagen Polo', 'Volkswagen Tiguan',
            'Ford Focus', 'Ford Fiesta', 'Ford Mondeo', 'Ford Kuga',
            'Škoda Octavia', 'Škoda Fabia', 'Škoda Superb', 'Škoda Karoq',
            'Toyota Corolla', 'Toyota Yaris', 'Toyota RAV4', 'Toyota Auris',
            'BMW 320', 'BMW 520', 'BMW X3', 'BMW X5',
            'Audi A3', 'Audi A4', 'Audi A6', 'Audi Q5',
            'Mercedes A-Class', 'Mercedes C-Class', 'Mercedes E-Class', 'Mercedes GLC',
            'Suzuki Swift', 'Suzuki Vitara', 'Suzuki SX4',
            'Renault Megane', 'Renault Clio', 'Renault Captur',
            'Peugeot 308', 'Peugeot 208', 'Peugeot 3008',
            'Hyundai i30', 'Hyundai Tucson', 'Hyundai i20',
            'Kia Ceed', 'Kia Sportage', 'Kia Rio',
            'Dacia Duster', 'Dacia Logan', 'Dacia Sandero',
        ];

        // Gumi márkák
        $tireBrands = [
            'Michelin', 'Continental', 'Bridgestone', 'Goodyear', 'Pirelli',
            'Dunlop', 'Hankook', 'Nokian', 'Yokohama', 'Firestone',
            'BFGoodrich', 'Fulda', 'Semperit', 'Toyo', 'Kumho',
            'Nexen', 'Falken', 'Vredestein', 'Cooper', 'Sava',
        ];

        // Gumi méretek
        $tireSizes = [
            '195/65 R15', '205/55 R16', '225/45 R17', '215/55 R17', '205/60 R16',
            '185/65 R15', '175/65 R14', '195/55 R16', '225/50 R17', '235/45 R18',
            '215/60 R16', '225/55 R17', '205/50 R17', '195/60 R15', '185/60 R15',
            '215/45 R17', '225/40 R18', '235/55 R18', '245/45 R18', '255/55 R19',
        ];

        // Szolgáltatások (adatbázisból)
        $services = ['Abroncs javítás', 'Alufelni javítás', 'TPMS', 'Abroncs csere'];

        // Anyagok (adatbázisból)
        $materials = ['Gumiabroncs tároló zsák', 'Kerékcsavar anya', 'Anyagok...'];

        // Megjegyzések
        $comments = [
            'Ügyfél kérte a téli gumik ellenőrzését is.',
            'Bal első kerék kopottabb, jövő héten vissza kell jönni.',
            'Sérült felni, ügyfél tudja.',
            'Sürgős munka volt, ügyfél várt.',
            'Régi ügyfél, kedvezményt kapott.',
            'Kerékcsavar hiányzott, pótoltuk.',
            'Guminyomás beállítva 2.2 bar-ra.',
            'TPMS szenzor cserélve.',
            'Felni tisztítás is kérte az ügyfél.',
            'Következő szerviz 10.000 km múlva ajánlott.',
            null,
            null,
            null,
            null,
            null,
        ];

        // Magyar rendszámok generálása (ABC-123 formátum)
        $letters = 'ABCDEFGHJKLMNPRSTVXYZ';

        for ($i = 0; $i < 40; $i++) {
            // Random rendszám generálása
            $regNumber = '';
            for ($j = 0; $j < 3; $j++) {
                $regNumber .= $letters[rand(0, strlen($letters) - 1)];
            }
            $regNumber .= '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);

            // Random név
            $name = $lastNames[array_rand($lastNames)] . ' ' . $firstNames[array_rand($firstNames)];

            // Random szolgáltatások (1-4 db)
            $serviceCount = rand(1, count($services));
            $serviceKeys = $serviceCount === 1 
                ? [array_rand($services)] 
                : array_rand($services, $serviceCount);
            $selectedServices = array_map(fn($key) => $services[$key], (array) $serviceKeys);

            // Random anyagok (0-3 db)
            $materialCount = rand(0, count($materials));
            if ($materialCount === 0) {
                $selectedMaterials = [];
            } else {
                $materialKeys = $materialCount === 1 
                    ? [array_rand($materials)] 
                    : array_rand($materials, $materialCount);
                $selectedMaterials = array_map(fn($key) => $materials[$key], (array) $materialKeys);
            }

            // Random dátum az elmúlt 6 hónapból
            $createdAt = now()->subDays(rand(0, 180))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            
            // 70% eséllyel lezárt munkalap
            $isClosed = rand(1, 100) <= 70;
            $closedAt = $isClosed ? $createdAt->copy()->addHours(rand(1, 48)) : null;

            WorkSheet::create([
                'registration_number' => $regNumber,
                'name' => rand(1, 100) > 20 ? $name : null, // 80% eséllyel van név
                'car_type' => rand(1, 100) > 30 ? $carTypes[array_rand($carTypes)] : null, // 70% eséllyel van autó típus
                'used_materials' => $selectedMaterials,
                'services' => $selectedServices,
                'tire_brand' => rand(1, 100) > 40 ? $tireBrands[array_rand($tireBrands)] : null, // 60% eséllyel van márka
                'tire_size' => rand(1, 100) > 40 ? $tireSizes[array_rand($tireSizes)] : null, // 60% eséllyel van méret
                'store' => rand(0, 1) ? true : (rand(0, 1) ? false : null), // true/false/null
                'comment' => $comments[array_rand($comments)],
                'is_closed' => $isClosed,
                'closed_at' => $closedAt,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}
