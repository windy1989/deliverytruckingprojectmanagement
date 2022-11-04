<?php

namespace Database\Seeders;

use App\Models\Fee;
use Illuminate\Database\Seeder;

class FeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($fees as $f) {
            Fee::insert([
                'id'          => $f['id'],
                'user_id'     => $f['user_id'],
                'coa_debit'   => $f['coa_debit'],
                'coa_credit'  => $f['coa_credit'],
                'attachment'  => $f['attachment'],
                'code'        => $f['code'],
                'description' => $f['description'],
                'type'        => $f['type'],
                'total'       => $f['total'],
                'created_at'  => $f['created_at'],
                'updated_at'  => $f['updated_at'],
                'deleted_at'  => $f['deleted_at']
            ]);
        }
    }
}
