<?php

namespace Database\Seeders;

use App\Models\Receipt;
use App\Models\ReceiptDetail;
use Illuminate\Database\Seeder;

class ReceiptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($receipts as $r) {
            Receipt::insert([
                'id'          => $r['id'],
                'user_id'     => $r['user_id'],
                'customer_id' => $r['customer_id'],
                'code'        => $r['code'],
                'total'       => $r['total'],
                'claim'       => $r['claim'],
                'other'       => $r['other'],
                'paid_off'    => $r['paid_off'],
                'due_date'    => $r['due_date'],
                'created_at'  => $r['created_at'],
                'updated_at'  => $r['updated_at'],
                'deleted_at'  => $r['deleted_at']
            ]);
        }

        foreach($receipt_details as $rd) {
            ReceiptDetail::insert([
                'id'         => $rd['id'],
                'receipt_id' => $rd['receipt_id'],
                'invoice_id' => $rd['invoice_id'],
                'created_at' => $rd['created_at'],
                'updated_at' => $rd['updated_at']
            ]);
        }
    }
}
