<?php

namespace App\Helper;

use App\Models\Coa;

class Digitrans
{

    public static function totalProfitLossYearly($year)
    {

        $result = [];

        for ($i = 1; $i < 13; $i++) {
            $filter = $year . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);

            $profit = Coa::where('parent_id', '!=', 0)
                ->whereIn('code', ['11001', '11002', '11003', '13001', '13002', '13003'])
                ->whereExists(function ($query) use ($filter) {
                    $query->select('created_at')
                        ->from('journals')
                        ->where(function ($query) use ($filter) {
                            $query->where('created_at', 'like', "$filter%");
                        });
                })
                ->where('status', 1)
                ->get();

            $total_profit = 0;

            foreach ($profit as $row) {
                $total_profit += $row->balance('debit', $row->code);
            }

            $loss = Coa::where('parent_id', '!=', 0)
                ->whereIn('code', ['6001', '6002', '6003', '10001', '10002', '10003', '10004', '10005', '12001', '12002', '12003', '12004', '12005', '12006', '12007', '12008', '12009', '12010', '12011', '12012', '12013', '12014', '14001', '14002', '14003'])
                ->whereExists(function ($query) use ($filter) {
                    $query->select('created_at')
                        ->from('journals')
                        ->where(function ($query) use ($filter) {
                            $query->where('created_at', 'like', "$filter%");
                        });
                })
                ->where('status', 1)
                ->get();

            $total_loss = 0;

            foreach ($loss as $row) {
                $total_loss += $row->balance('credit', $row->code);
            }
        }


        $result[] = [
            'month'        => $filter,
            'total_profit'  => $total_profit,
            'total_loss'    => $total_loss,
        ];


        return $result;
    }
}
