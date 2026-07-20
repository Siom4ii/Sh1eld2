<?php

namespace Database\Seeders;

use App\Models\Barangay;
use App\Models\Municipality;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Davao del Sur PSGC location seed data.
     *
     * Source basis: PSA PSGC Davao del Sur listing, 1 city, 9 municipalities,
     * and 232 barangays as of 31 July 2025.
     */
    public function run(): void
    {
        $locations = [
            'Digos City' => [
                'Aplaya', 'Balabag', 'San Jose', 'Binaton', 'Cogon', 'Colorado',
                'Dawis', 'Dulangan', 'Goma', 'Igpit', 'Kiagot', 'Lungag',
                'Mahayahay', 'Matti', 'Kapatagan', 'Ruparan', 'San Agustin',
                'San Miguel', 'San Roque', 'Sinawilan', 'Soong', 'Tiguman',
                'Tres De Mayo', 'Zone 1', 'Zone 2', 'Zone 3',
            ],
            'Bansalan' => [
                'Alegre', 'Alta Vista', 'Anonang', 'Bitaug', 'Bonifacio',
                'Buenavista', 'Darapuay', 'Dolo', 'Eman', 'Kinuskusan',
                'Libertad', 'Linawan', 'Mabuhay', 'Mabunga', 'Managa',
                'Marber', 'New Clarin', 'Poblacion', 'Rizal', 'Santo Nino',
                'Sibayan', 'Tinongtongan', 'Tubod', 'Union', 'Poblacion Dos',
            ],
            'Hagonoy' => [
                'Balutakay', 'Clib', 'Guihing Aplaya', 'Guihing',
                'Hagonoy Crossing', 'Kibuaya', 'La Union', 'Lanuro',
                'Lapulabao', 'Leling', 'Mahayahay', 'Malabang Damsite',
                'Maliit Digos', 'New Quezon', 'Paligue', 'Poblacion',
                'Sacub', 'San Guillermo', 'San Isidro', 'Sinayawan', 'Tologan',
            ],
            'Kiblawan' => [
                'Abnate', 'Bagong Negros', 'Bagong Silang', 'Bagumbayan',
                'Balasiao', 'Bonifacio', 'Bunot', 'Cogon-Bacaca', 'Dapok',
                'Ihan', 'Kibongbong', 'Kimlawis', 'Kisulan', 'Lati-an',
                'Manual', 'Maraga-a', 'Molopolo', 'New Sibonga', 'Panaglib',
                'Pasig', 'Poblacion', 'Pocaleel', 'San Isidro', 'San Jose',
                'San Pedro', 'Santo Nino', 'Tacub', 'Tacul', 'Waterfall',
                'Bulol-Salo',
            ],
            'Magsaysay' => [
                'Bacungan', 'Balnate', 'Barayong', 'Blocon', 'Dalawinon',
                'Dalumay', 'Glamang', 'Kanapulo', 'Kasuga', 'Lower Bala',
                'Mabini', 'Malawanit', 'Malongon', 'New Ilocos', 'Poblacion',
                'San Isidro', 'San Miguel', 'Tacul', 'Tagaytay', 'Upper Bala',
                'Maibo', 'New Opon',
            ],
            'Malalag' => [
                'Baybay', 'Bolton', 'Bulacan', 'Caputian', 'Ibo', 'Kiblagon',
                'Lapu-Lapu', 'Mabini', 'New Baclayon', 'Pitu', 'Poblacion',
                'Tagansule', 'Bagumbayan', 'Rizal', 'San Isidro',
            ],
            'Matanao' => [
                'Asbang', 'Asinan', 'Bagumbayan', 'Bangkal', 'Buas', 'Buri',
                'Camanchiles', 'Ceboza', 'Colonsabak', 'Dongan-Pekong',
                'Kabasagan', 'Kapok', 'Kauswagan', 'Kibao', 'La Suerte',
                'Langa-an', 'Lower Marber', 'Cabligan', 'Manga',
                'New Katipunan', 'New Murcia', 'New Visayas', 'Poblacion',
                'Saboy', 'San Jose', 'San Miguel', 'San Vicente', 'Saub',
                'Sinaragan', 'Sinawilan', 'Tamlangon', 'Towak', 'Tibongbong',
            ],
            'Padada' => [
                'Almendras', 'Don Sergio Osmena, Sr.', 'Harada Butai',
                'Lower Katipunan', 'Lower Limonzo', 'Lower Malinao',
                'N C Ordaneza District', 'Northern Paligue', 'Palili',
                'Piape', 'Punta Piape', 'Quirino District', 'San Isidro',
                'Southern Paligue', 'Tulogan', 'Upper Limonzo',
                'Upper Malinao',
            ],
            'Santa Cruz' => [
                'Astorga', 'Bato', 'Coronon', 'Darong', 'Inawayan',
                'Jose Rizal', 'Matutungan', 'Melilia', 'Zone I', 'Saliducon',
                'Sibulan', 'Sinoron', 'Tagabuli', 'Tibolo', 'Tuban',
                'Zone II', 'Zone III', 'Zone IV',
            ],
            'Sulop' => [
                'Balasinon', 'Buguis', 'Carre', 'Clib', 'Harada Butai',
                'Katipunan', 'Kiblagon', 'Labon', 'Laperas', 'Lapla',
                'Litos', 'Luparan', 'Mckinley', 'New Cebu', 'Osmena',
                'Palili', 'Parame', 'Poblacion', 'Roxas', 'Solongvale',
                'Tagolilong', 'Tala-o', 'Talas', 'Tanwalang', 'Waterfall',
            ],
        ];

        foreach ($locations as $municipalityName => $barangays) {
            $municipality = Municipality::firstOrCreate(['name' => $municipalityName]);

            foreach ($barangays as $barangayName) {
                Barangay::firstOrCreate([
                    'municipality_id' => $municipality->id,
                    'name' => $barangayName,
                ]);
            }
        }
    }
}
