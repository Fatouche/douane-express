<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Order;
use App\Models\Service;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Order::factory()
        //     ->count(3)
        //     ->hasAttached(
        //         Service::factory()->count(5),
        //         ['total_price' => rand(10, 20), 'total_quantity' => rand(1, 3)]
        //     )
        //     ->create();

        Service::create([
            'name' => 'Ouverture de compte / opening account',
            'reference' => 'DOU001',
            'price' => 30,
            'minQuantity' => 1,
            'maxQuantity' => null,
            'image' => null,
            'serviceId' => null,
        ]);
        $serviceTransit = Service::create([
            'name' => 'Notification d\'un titre de transit',
            'reference' => 'DOU002',
            'price' => 25,
            'minQuantity' => 0,
            'maxQuantity' => 1,
            'image' => null,
            'serviceId' => null,
        ]);
        Service::create([
            'name' => 'Notification a partir du deuxieme transit',
            'reference' => 'DOU003',
            'price' => 15,
            'minQuantity' => 1,
            'maxQuantity' => null,
            'image' => null,
            'serviceId' => $serviceTransit->id,
        ]);
        Service::create([
            'name' => 'Frais de rechargement par coup de fourche',
            'reference' => 'DOU005',
            'price' => 50,
            'minQuantity' => 1,
            'maxQuantity' => null,
            'image' => null,
            'serviceId' => null,
        ]);
        Service::create([
            'name' => 'Stockage : forfait de 48 h par palette',
            'reference' => 'DOU006',
            'price' => 15,
            'minQuantity' => 1,
            'maxQuantity' => null,
            'image' => null,
            'serviceId' => null,
        ]);
        $serviceDoc = Service::create([
            'name' => 'Impression documents',
            'reference' => 'DOU013',
            'price' => 0,
            'minQuantity' => 0,
            'maxQuantity' => null,
            'image' => null,
            'serviceId' => null,
        ]);
        $serviceDoc1 = Service::create([
            'name' => 'Impression documents 1 à 10 pages',
            'reference' => 'DOU014',
            'price' => 10,
            'minQuantity' => 1,
            'maxQuantity' => 10,
            'image' => null,
            'serviceId' => $serviceDoc->id,
        ]);
        $serviceDoc2 = Service::create([
            'name' => 'Impression documents 11 à 20 pages',
            'reference' => 'DOU015',
            'price' => 5,
            'minQuantity' => 11,
            'maxQuantity' => 20,
            'image' => null,
            'serviceId' => $serviceDoc1->id,
        ]);
        Service::create([
            'name' => 'Impression document > 20 pages / page',
            'reference' => 'DOU016',
            'price' => 0.5,
            'minQuantity' => 21,
            'maxQuantity' => null,
            'image' => null,
            'serviceId' => $serviceDoc2->id,
        ]);
    }
}
