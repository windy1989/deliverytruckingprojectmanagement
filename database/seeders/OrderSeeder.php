<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Destination;
use App\Models\Customer;
use App\Models\OrderTransport;
use Illuminate\Database\Seeder;
use App\Models\OrderDestination;
use App\Models\OrderCustomerDetail;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($orders as $o) {
            Order::insert([
                'user_id'     => $o['user_id'],
                'vendor_id'   => $o['vendor_id'],
                'unit_id'     => $o['unit_id'],
                'code'        => $o['code'],
                'reference'   => $o['reference'],
                'weight'      => $o['weight'],
                'qty'         => $o['qty'],
                'date'        => $o['date'],
                'deadline'    => $o['deadline'],
                'tolerance'   => $o['tolerance'],
                'status'      => $o['status'],
                'created_at'  => $o['created_at'],
                'updated_at'  => $o['updated_at'],
                'deleted_at'  => $o['deleted_at']
            ]);
        }

        foreach($order_customer_details as $ocd) {
            OrderCustomerDetail::insert([
                'id'          => $ocd['id'],
                'order_id'    => $ocd['order_id'],
                'customer_id' => $ocd['customer_id'],
                'created_at'  => $ocd['created_at'],
                'updated_at'  => $ocd['updated_at']
            ]);
        }

        foreach($order_destinations as $od) {
            OrderDestination::insert([
                'id'             => $od['id'],
                'order_id'       => $od['order_id'],
                'destination_id' => $od['destination_id'],
                'created_at'     => $od['created_at'],
                'updated_at'     => $od['updated_at']
            ]);
        }

        foreach($order_transports as $ot) {
            OrderTransport::insert([
                'id'                    => $ot['id'],
                'order_id'              => $ot['order_id'],
                'driver_id'             => $ot['driver_id'],
                'transport_id'          => $ot['transport_id'],
                'warehouse_origin'      => $ot['warehouse_origin'],
                'warehouse_destination' => $ot['warehouse_destination'],
                'created_at'            => $ot['created_at'],
                'updated_at'            => $ot['updated_at']
            ]);
        }
    }
}
