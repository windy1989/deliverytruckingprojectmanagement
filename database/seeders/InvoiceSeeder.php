<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($invoices as $i) {
            Invoice::insert([
                'id'           => $i['id'],
                'user_id'      => $i['user_id'],
                'customer_id'  => $i['customer_id'],
                'code'         => $i['code'],
                'down_payment' => $i['down_payment'],
                'tax'          => $i['tax'],
                'discount'     => $i['discount'],
                'subtotal'     => $i['subtotal'],
                'grandtotal'   => $i['grandtotal'],
                'created_at'   => $i['created_at'],
                'updated_at'   => $i['updated_at'],
                'deleted_at'   => $i['deleted_at']
            ]);
        }

        foreach($invoice_details as $id) {
            InvoiceDetail::insert([
                'id'            => $id['id'],
                'invoice_id'    => $id['invoice_id'],
                'letter_way_id' => $id['letter_way_id'],
                'price'         => $id['price'],
                'total'         => $id['total'],
                'created_at'    => $id['created_at'],
                'updated_at'    => $id['updated_at']
            ]);
        }
    }
}
