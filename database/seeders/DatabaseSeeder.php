<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ActivityLogSeeder::class);
        $this->call(CitySeeder::class);
        $this->call(ClaimSeeder::class);
        $this->call(CoaSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(DestinationSeeder::class);
        $this->call(DriverSeeder::class);
        $this->call(FeeSeeder::class);
        $this->call(InvoiceSeeder::class);
        $this->call(JournalSeeder::class);
        $this->call(LetterWaySeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(OrderSeeder::class);
        $this->call(ProvinceSeeder::class);
        $this->call(ReceiptSeeder::class);
        $this->call(RepaymentSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(TransportSeeder::class);
        $this->call(UnitSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(VendorSeeder::class);
        $this->call(WarehouseSeeder::class);
        $this->call(FileManagerSeeder::class);
    }
}
