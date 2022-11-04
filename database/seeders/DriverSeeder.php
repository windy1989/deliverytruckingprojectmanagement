<?php

namespace Database\Seeders;

use App\Models\Driver;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($drivers as $d) {
            Driver::insert([
                'city_id'               => $d['city_id'],
                'vendor_id'             => $d['vendor_id'],
                'photo'                 => $d['photo'],
                'name'                  => $d['name'],
                'photo_identity_card'   => $d['photo_identity_card'],
                'no_identity_card'      => $d['no_identity_card'],
                'photo_driving_licence' => $d['photo_driving_licence'],
                'no_driving_licence'    => $d['no_driving_licence'],
                'type_driving_licence'  => $d['type_driving_licence'],
                'valid_driving_licence' => $d['valid_driving_licence'],
                'address'               => $d['address'],
                'status'                => $d['status'],
                'created_at'            => $d['created_at'],
                'updated_at'            => $d['updated_at'],
                'deleted_at'            => $d['deleted_at']
            ]);
        }
    }
}
