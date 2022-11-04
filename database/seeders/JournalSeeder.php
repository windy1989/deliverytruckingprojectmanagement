<?php

namespace Database\Seeders;

use App\Models\Journal;
use Illuminate\Database\Seeder;

class JournalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($journals as $j) {
            Journal::insert([
                'id'          => $j['id'],
                'coa_debit'   => $j['coa_debit'],
                'coa_credit'  => $j['coa_credit'],
                'nominal'     => $j['nominal'],
                'description' => $j['description'],
                'created_at'  => $j['created_at'],
                'updated_at'  => $j['updated_at']
            ]);
        }
    }
}
