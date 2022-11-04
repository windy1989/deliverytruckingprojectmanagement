<?php

namespace App\Http\Controllers;

use App\Models\Coa;
use Illuminate\Http\Request;

class BalanceSheetController extends Controller {
    
    public function index(Request $request)
    {
        $coa_asset = Coa::where('status', 1)
            ->whereIn('code', ['1001', '1002', '2001', '2002', '2003', '2004', '3001', '3002', '3003', '4001', '4002', '4003', '5001', '5002', '5003', '5004', '7001', '7002', '8001', '8002', '8003', '9001', '9002', '9003', '11001', '11002'])
            ->get();

        $coa_liability = Coa::where('status', 1)
            ->whereIn('code', ['6001', '6002', '6003', '10001', '10002', '10003', '10004', '10005', '12004', '12006', '12011'])
            ->get();

        $data = [
            'title'         => 'Digitrans - Neraca',
            'coa_asset'     => $coa_asset,
            'coa_liability' => $coa_liability,
            'content'       => 'accounting.balance_sheet'
        ];
    
        return view('errors.404', ['data' => $data]);
    }

}
