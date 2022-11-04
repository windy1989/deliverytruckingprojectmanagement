<?php

namespace Database\Seeders;

use App\Models\Transport;
use Illuminate\Database\Seeder;

class TransportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($transports as $t) {
            Transport::insert([
                'id'         => $t['id'],
                'no_plate'   => $t['no_plate'],
                'brand'      => $t['brand'],
                'valid_kir'  => $t['valid_kir'],
                'photo_stnk' => $t['photo_stnk'],
                'valid_stnk' => $t['valid_stnk'],
                'type'       => $t['type'],
                'status'     => $t['status'],
                'created_at' => $t['created_at'],
                'updated_at' => $t['updated_at'],
                'deleted_at' => $t['deleted_at']
            ]);
        }
    }
}
