<?php

namespace Database\Seeders;

use App\Models\LetterWay;
use App\Models\Destination;
use Illuminate\Database\Seeder;

class LetterWaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($letter_ways as $lw) {
            LetterWay::insert([
                'id'                               => $lw['id'],
                'order_id'                         => $lw['order_id'],
                'destination_id'                   => $lw['destination_id'],
                'number'                           => $lw['number'],
                'weight'                           => $lw['weight'],
                'qty'                              => $lw['qty'],
                'received_date'                    => $lw['received_date'],
                'send_back_attachment'             => $lw['send_back_attachment'],
                'legalize_attachment'              => $lw['legalize_attachment'],
                'legalize_received_date'           => $lw['legalize_received_date'],
                'legalize_send_back_attachment'    => $lw['legalize_send_back_attachment'],
                'legalize_send_back_received_date' => $lw['legalize_send_back_received_date'],
                'ttbr_qty'                         => $lw['ttbr_qty'],
                'ttbr_attachment'                  => $lw['ttbr_attachment'],
                'status'                           => $lw['status'],
                'created_at'                       => $lw['created_at'],
                'updated_at'                       => $lw['updated_at']
            ]);
        }
    }
}
