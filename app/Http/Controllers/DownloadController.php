<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use App\Exports\ReportUnitExport;
use App\Exports\ReportOrderExport;
use App\Exports\ReportDriverExport;
use App\Exports\ReportVendorExport;
use App\Exports\ReportInvoiceExport;
use App\Exports\ReportReceiptExport;
use App\Http\Controllers\Controller;
use App\Exports\ReportActivityExport;
use App\Exports\ReportCustomerExport;
use App\Exports\ReportPurchaseExport;
use App\Exports\ReportLetterWayExport;
use App\Exports\ReportTransportExport;
use App\Exports\ReportWarehouseExport;
use App\Exports\ReportDestinationExport;

class DownloadController extends Controller {

    public function excel(Request $request)
    {
        // return dd(true);
        return redirect()->back();
        if($request->param == 'report_order') {
            $data = (object)[
                'start_date'  => $request->start_date,
                'finish_date' => $request->finish_date,
                'customer_id' => $request->customer_id,
                'vendor_id'   => $request->vendor_id,
                'status'      => $request->status
            ];

            return (new ReportOrderExport($data))->download('LAPORAN ORDER ' . strtoupper(date('d F Y')) . '.xlsx', Excel::XLSX);
        } else if($request->param == 'report_letter_way') {
            $data = (object)[
                'start_date'  => $request->start_date,
                'finish_date' => $request->finish_date,
                'order_id'    => $request->order_id,
                'status'      => $request->status
            ];

            return (new ReportLetterWayExport($data))->download('LAPORAN SURAT JALAN ' . strtoupper(date('d F Y')) . '.xlsx', Excel::XLSX);
        } else if($request->param == 'report_invoice') {
            $data = (object)[
                'start_date'  => $request->start_date,
                'finish_date' => $request->finish_date,
                'customer_id' => $request->customer_id,
                'status'      => $request->status
            ];

            return (new ReportInvoiceExport($data))->download('LAPORAN INVOICE ' . strtoupper(date('d F Y')) . '.xlsx', Excel::XLSX);
        } else if($request->param == 'report_receipt') {
            $data = (object)[
                'start_date'  => $request->start_date,
                'finish_date' => $request->finish_date,
                'customer_id' => $request->customer_id,
                'status'      => $request->status
            ];

            return (new ReportReceiptExport($data))->download('LAPORAN KWITANSI ' . strtoupper(date('d F Y')) . '.xlsx', Excel::XLSX);
        } else if($request->param == 'report_purchase') {
            $data = (object)[
                'start_date'  => $request->start_date,
                'finish_date' => $request->finish_date,
                'vendor_id'   => $request->vendor_id,
                'radio'       => $request->radio
            ];

            return (new ReportPurchaseExport($data))->download('LAPORAN PEMBELIAN ' . strtoupper(date('d F Y')) . '.xlsx', Excel::XLSX);
        } else if($request->param == 'report_activity') {
            $data = (object)[
                'start_date'  => $request->start_date,
                'finish_date' => $request->finish_date,
                'causer_id'   => $request->causer_id
            ];

            return (new ReportActivityExport($data))->download('LAPORAN AKTIVITAS ' . strtoupper(date('d F Y')) . '.xlsx', Excel::XLSX);
        } else if($request->param == 'report_warehouse') {
            return (new ReportWarehouseExport)->download('LAPORAN GUDANG ' . strtoupper(date('d F Y')) . '.xlsx', Excel::XLSX);
        } else if($request->param == 'report_vendor') {
            return (new ReportVendorExport)->download('LAPORAN VENDOR ' . strtoupper(date('d F Y')) . '.xlsx', Excel::XLSX);
        } else if($request->param == 'report_driver') {
            return (new ReportDriverExport)->download('LAPORAN SOPIR ' . strtoupper(date('d F Y')) . '.xlsx', Excel::XLSX);
        } else if($request->param == 'report_transport') {
            return (new ReportTransportExport)->download('LAPORAN KENDARAAN ' . strtoupper(date('d F Y')) . '.xlsx', Excel::XLSX);
        } else if($request->param == 'report_destination') {
            return (new ReportDestinationExport)->download('LAPORAN HARGA PER TUJUAN ' . strtoupper(date('d F Y')) . '.xlsx', Excel::XLSX);
        } else if($request->param == 'report_customer') {
            return (new ReportCustomerExport)->download('LAPORAN CUSTOMER ' . strtoupper(date('d F Y')) . '.xlsx', Excel::XLSX);
        } else if($request->param == 'report_unit') {
            return (new ReportUnitExport)->download('LAPORAN SATUAN ' . strtoupper(date('d F Y')) . '.xlsx', Excel::XLSX);
        } else {
            return redirect()->back();
        }
    }

    public function pdf(Request $request)
    {
        if($request->param == 'claim') {
            if($request->type == 'receipts') {
                $view = 'pdf.claim_receipt';
            } else {
                $view = 'pdf.claim_purchase';
            }

            $data = [
                'title'   => 'Digitrans - Cetak Klaim',
                'claim'   => Claim::where('claimable_type', $request->type)->where('claimable_id', $request->id)->first(),
                'content' => $view
            ];

            return view('layouts.index', ['data' => $data]);
        }
    }

}
