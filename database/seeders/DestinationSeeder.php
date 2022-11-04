<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Destination;
use Illuminate\Database\Seeder;
use App\Models\DestinationPrice;

class DestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($destinations as $d) {
            Destination::insert([
                'vendor_id'        => $d['vendor_id'],
                'label'            => $d['label'],
                'city_origin'      => $d['city_origin'],
                'city_destination' => $d['city_destination'],
                'created_at'       => $d['created_at'],
                'updated_at'       => $d['updated_at'],
                'deleted_at'       => $d['deleted_at']
            ]);
        }

        foreach($destination_prices as $dp) {
            DestinationPrice::insert([
                'destination_id' => $dp['destination_id'],
                'unit_id'        => $dp['unit_id'],
                'date'           => $dp['date'],
                'price_vendor'   => $dp['price_vendor'],
                'price_customer' => $dp['price_customer'],
                'created_at'     => $dp['created_at'],
                'updated_at'     => $dp['updated_at']
            ]);
        }
    }
}
