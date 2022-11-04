<?php

namespace Database\Seeders;

use App\Models\Claim;
use App\Models\ClaimDetail;
use Illuminate\Database\Seeder;

class ClaimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($claims as $c) {
            Claim::insert([
                'id'             => $c['id'],
                'claimable_type' => $c['claimable_type'],
                'claimable_id'   => $c['claimable_id'],
                'date'           => $c['date'],
                'description'    => $c['description'],
                'flag'           => $c['flag'],
                'created_at'     => $c['created_at'],
                'updated_at'     => $c['updated_at']
            ]);
        }

        foreach($claim_details as $cd) {
            ClaimDetail::insert([
                'id'            => $cd['id'],
                'claim_id'      => $cd['claim_id'],
                'letter_way_id' => $cd['letter_way_id'],
                'percentage'    => $cd['percentage'],
                'rupiah'        => $cd['rupiah'],
                'tolerance'     => $cd['tolerance'],
                'created_at'    => $cd['created_at'],
                'updated_at'    => $cd['updated_at']
            ]);
        }
    }
}
