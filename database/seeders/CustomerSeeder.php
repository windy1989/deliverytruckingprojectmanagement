<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerBill;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($customers as $c) {
            Customer::insert([
                'city_id'     => $c['city_id'],
                'code'        => $c['code'],
                'name'        => $c['name'],
                'phone'       => $c['phone'],
                'fax'         => $c['fax'],
                'website'     => $c['website'],
                'address'     => $c['address'],
                'pic'         => $c['pic'],
                'status'      => $c['status'],
                'created_at'  => $c['created_at'],
                'updated_at'  => $c['updated_at'],
                'deleted_at'  => $c['deleted_at']
            ]);
        }

        foreach($customer_bills as $cb) {
            CustomerBill::insert([
                'customer_id' => $cb['customer_id'],
                'bank'        => $cb['bank'],
                'bill'        => $cb['bill'],
                'created_at'  => $cb['created_at'],
                'updated_at'  => $cb['updated_at']
            ]);
        }
    }
}
