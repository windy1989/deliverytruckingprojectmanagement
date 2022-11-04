<?php

namespace Database\Seeders;

use App\Models\Repayment;
use App\Models\RepaymentDetail;
use Illuminate\Database\Seeder;

class RepaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($repayments as $r) {
            Repayment::insert([
                'id'         => $r['id'],
                'user_id'    => $r['user_id'],
                'vendor_id'  => $r['vendor_id'],
                'code'       => $r['code'],
                'total'      => $r['total'],
                'claim'      => $r['claim'],
                'paid_off'   => $r['paid_off'],
                'due_date'   => $r['due_date'],
                'created_at' => $r['created_at'],
                'updated_at' => $r['updated_at'],
                'deleted_at' => $r['deleted_at']
            ]);
        }

        foreach($repayment_details as $rd) {
            RepaymentDetail::insert([
                'id'            => $rd['id'],
                'repayment_id'  => $rd['repayment_id'],
                'letter_way_id' => $rd['letter_way_id'],
                'price'         => $rd['price'],
                'total'         => $rd['total'],
                'created_at'    => $rd['created_at'],
                'updated_at'    => $rd['updated_at']
            ]);
        }
    }
}
