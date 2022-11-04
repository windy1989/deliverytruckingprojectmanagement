<?php

namespace App\Http\Controllers\Api;

use App\Models\Coa;
use App\Models\Order;
use App\Models\LetterWay;
use App\Models\OrderDestination;
use App\Models\Driver;
use App\Models\Vendor;
use App\Models\Customer;
use App\Models\OrderCustomerDetail;
use App\Models\OrderTransport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LetterWayController extends Controller {

    public function index(Request $request)
    {
        $result      = [];
        $transport   = [];
        $letterways  = [];
        $customer  = [];
        $destination = [];
        $customers   = [];

        $query       = Order::where(function($query) use ($request) {
            if($request->id) {
                $query->where('id', $request->id);
            }
        })
        ->whereIn('status', [1, 2])
        ->get();

        if($query) {
            foreach($query as $q) {
                $data     = OrderDestination::where('order_id', $q->id)->get();
                foreach($data as $d) {
                    $transport[] = [
                        'id'          => $d->destination_id,
                        'destination' => $d->destination->cityOrigin->name . ' &rarr; ' . $d->destination->cityDestination->name
                    ];
                }

                foreach($q->orderCustomerDetail as $key => $ocd) {
                    $customers[] = [
                        'customer_id'                  => $ocd->customer->id,
                        'customer_name'                => $ocd->customer->name,
                    ];
                }

                $sLetterWay = LetterWay::select('id', 'number', 'weight', 'status')
                ->whereNotExists(function($sLetterWay) {
                    $sLetterWay->select(DB::raw(1))
                    ->from('invoice_details')
                    ->whereColumn('invoice_details.letter_way_id', 'letter_ways.id');
                    })
                    ->where('order_id', $q->id)
                    ->get();

                foreach($sLetterWay as $LW) {
                    $letterways[] = [
                        'id'     => $LW->id,
                        'number' => $LW->number,
                        'weight' => $LW->weight . ' Kg',
                        'status' => $LW->status()
                    ];
                }

                $result[] = [
                    'id'          => $q->id,
                    'user'        => $q->user->name,
                    'customers'    => $customers,
                    'vendor_id'   => $q->vendor->id,
                    'vendor_name' => $q->vendor->name,
                    'code'        => $q->code,
                    'weight'      => $q->weight,
                    'qty'         => $q->qty,
                    'date'        => $q->date,
                    'deadline'    => $q->deadline,
                    'tolerance'   => $q->tolerance,
                    'status'      => $q->status(),
                    'created_at'  => $q->created_at,
                    'updated_at'  => $q->updated_at,
                    'transport'   => $transport,
                    'letterways'  => $letterways
                ];
            }
            $response = [
                    'status'  => 200,
                    'message' => 'Data found',
                    'result'  => [
                        'data'       => $result
                    ]
            ];
        } else {
            $response = [
                'status'  => 500,
                'message' => 'Server error'
            ];
        }

        return response()->json($response, $response['status']);
    }



    public function create(Request $request, $order_id)
    {
        $validation = Validator::make($request->all(), [
            'number'                        => 'required',
            'weight'                        => 'required',
            'qty'                           => 'required',
            'send_back_attachment'          => 'max:2048|mimes:jpeg,jpg,png',
            'legalize_attachment'           => 'max:2048|mimes:jpeg,jpg,png',
            'legalize_send_back_attachment' => 'max:2048|mimes:jpeg,jpg,png',
            'ttbr_attachment'               => 'max:2048|mimes:jpeg,jpg,png',
            'destination_id'                => 'required',
            'status'                        => 'required'
        ], [
            'number.required'                     => 'Mohon mengisi no surat jalan.',
            'weight.required'                     => 'Mohon mengisi berat.',
            'qty.required'                        => 'Mohon mengisi jumlah.',
            'send_back_attachment.max'            => 'Lampiran sj balik maksimal 2MB.',
            'send_back_attachment.mimes'          => 'Lampiran sj balik harus berformat jpeg, jpg, png.',
            'legalize_attachment.max'             => 'Lampiran sj legalisir maksimal 2MB.',
            'legalize_attachment.mimes'           => 'Lampiran sj legalisir harus berformat jpeg, jpg, png.',
            'legalize_send_back_attachment.max'   => 'Lampiran sj legalisir balik maksimal 2MB.',
            'legalize_send_back_attachment.mimes' => 'Lampiran sj legalisir balik harus berformat jpeg, jpg, png.',
            'ttbr_attachment.max'                 => 'Lampiran sj TTBR maksimal 2MB.',
            'ttbr_attachment.mimes'               => 'Lampiran sj TTBR harus berformat jpeg, jpg, png.',
            'destination_id.required'             => 'Mohon memilih tujuan',
            'status.required'                     => 'Mohon memilih status.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            if($request->has('send_back_attachment')) {
                $send_back_attachment = $request->file('send_back_attachment')->store('public/letter_way/send_back');
            } else {
                $send_back_attachment = null;
            }

            if($request->has('legalize_attachment')) {
                $legalize_attachment = $request->file('legalize_attachment')->store('public/letter_way/legalize');
            } else {
                $legalize_attachment = null;
            }

            if($request->has('legalize_send_back_attachment')) {
                $legalize_send_back_attachment = $request->file('legalize_send_back_attachment')->store('public/letter_way/legalize_back');
            } else {
                $legalize_send_back_attachment = null;
            }

            if($request->has('ttbr_attachment')) {
                $ttbr_attachment = $request->file('ttbr_attachment')->store('public/letter_way/ttbr');
            } else {
                $ttbr_attachment = null;
            }

            $query = LetterWay::create([
                'order_id'                         => $order_id,
                'destination_id'                   => $request->destination_id,
                'number'                           => $request->number,
                'weight'                           => $request->weight,
                'qty'                              => $request->qty,
                'received_date'                    => $request->received_date,
                'send_back_attachment'             => $send_back_attachment,
                'legalize_attachment'              => $legalize_attachment,
                'legalize_received_date'           => $request->legalize_received_date,
                'legalize_send_back_attachment'    => $legalize_send_back_attachment,
                'legalize_send_back_received_date' => $request->legalize_send_back_received_date,
                'ttbr_qty'                         => $request->ttbr_qty,
                'ttbr_attachment'                  => $ttbr_attachment,
                'status'                           => $request->status
            ]);

            if($query) {
                Order::where('id', $order_id)->update(['status' => 2]);

                activity()
                    ->performedOn(new LetterWay())
                    ->causedBy(session('id'))
                    ->withProperties($query)
                    ->log('Menambah data surat jalan ' . $query->number);

                    $response = [
                        'status'  => 201,
                        'message' => 'Data successfully process.',
                        'result'  => $query
                    ];
            } else {
                $response = [
                    'status'  => 500,
                    'message' => 'Data gagal diproses.'
                ];
            }
        }

        return response()->json($response, $response['status']);
    }

    public function show(Request $request)
    {
        $data = LetterWay::find($request->id);

        $result[] = [
            'order_id'                         => $data->order_id,
            'destination_id'                   => $data->destination_id,
            'number'                           => $data->number,
            'weight'                           => $data->weight,
            'qty'                              => $data->qty,
            'received_date'                    => $data->received_date,
            'send_back_attachment'             => $data->sendBackAttachment(),
            'legalize_attachment'              => $data->legalizeAttachment(),
            'legalize_received_date'           => $data->legalize_received_date,
            'legalize_send_back_attachment'    => $data->legalizeSendBackAttachment(),
            'legalize_send_back_received_date' => $data->legalize_send_back_received_date,
            'ttbr_qty'                         => $data->ttbr_qty,
            'ttbr_attachment'                  => $data->ttbrAttachment(),
            'status'                           => $data->status
        ];

        $response = [
            'status'  => 200,
            'message' => 'Data found',
            'result'  => [
                'data'       => $result
            ]
        ];

        return response()->json($response, $response['status']);
    }

    public function update(Request $request, $id)
    {
        $data       = LetterWay::find($id);
        $validation = Validator::make($request->all(), [
            'number'                        => 'required',
            'weight'                        => 'required',
            'qty'                           => 'required',
            'send_back_attachment'          => 'max:2048|mimes:jpeg,jpg,png',
            'legalize_attachment'           => 'max:2048|mimes:jpeg,jpg,png',
            'legalize_send_back_attachment' => 'max:2048|mimes:jpeg,jpg,png',
            'ttbr_attachment'               => 'max:2048|mimes:jpeg,jpg,png',
            'destination_id'                => 'required',
            'status'                        => 'required'
        ], [
            'number.required'                     => 'Mohon mengisi no surat jalan.',
            'weight.required'                     => 'Mohon mengisi berat.',
            'qty.required'                        => 'Mohon mengisi jumlah.',
            'send_back_attachment.max'            => 'Lampiran sj balik maksimal 2MB.',
            'send_back_attachment.mimes'          => 'Lampiran sj balik harus berformat jpeg, jpg, png.',
            'legalize_attachment.max'             => 'Lampiran sj legalisir maksimal 2MB.',
            'legalize_attachment.mimes'           => 'Lampiran sj legalisir harus berformat jpeg, jpg, png.',
            'legalize_send_back_attachment.max'   => 'Lampiran sj legalisir balik maksimal 2MB.',
            'legalize_send_back_attachment.mimes' => 'Lampiran sj legalisir balik harus berformat jpeg, jpg, png.',
            'ttbr_attachment.max'                 => 'Lampiran sj TTBR maksimal 2MB.',
            'ttbr_attachment.mimes'               => 'Lampiran sj TTBR harus berformat jpeg, jpg, png.',
            'destination_id.required'             => 'Mohon memilih tujuan',
            'status.required'                     => 'Mohon memilih status.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            if($request->has('send_back_attachment')) {
                if(Storage::exists($data->send_back_attachment)) {
                    Storage::delete($data->send_back_attachment);
                }

                $send_back_attachment = $request->file('send_back_attachment')->store('public/letter_way/send_back');
            } else {
                $send_back_attachment = $data->send_back_attachment;
            }

            if($request->has('legalize_attachment')) {
                if(Storage::exists($data->legalize_attachment)) {
                    Storage::delete($data->legalize_attachment);
                }

                $legalize_attachment = $request->file('legalize_attachment')->store('public/letter_way/legalize');
            } else {
                $legalize_attachment = $data->legalize_attachment;
            }

            if($request->has('legalize_send_back_attachment')) {
                if(Storage::exists($data->legalize_send_back_attachment)) {
                    Storage::delete($data->legalize_send_back_attachment);
                }

                $legalize_send_back_attachment = $request->file('legalize_send_back_attachment')->store('public/letter_way/legalize_back');
            } else {
                $legalize_send_back_attachment = $data->legalize_send_back_attachment;
            }

            if($request->has('ttbr_attachment')) {
                if(Storage::exists($data->ttbr_attachment)) {
                    Storage::delete($data->ttbr_attachment);
                }

                $ttbr_attachment = $request->file('ttbr_attachment')->store('public/letter_way/ttbr');
            } else {
                $ttbr_attachment = $data->ttbr_attachment;
            }

            $query = LetterWay::where('id', $id)->update([
                'order_id'                         => $request->order_id,
                'destination_id'                   => $request->destination_id,
                'number'                           => $request->number,
                'weight'                           => $request->weight,
                'qty'                              => $request->qty,
                'received_date'                    => $request->received_date,
                'send_back_attachment'             => $send_back_attachment,
                'legalize_attachment'              => $legalize_attachment,
                'legalize_received_date'           => $request->legalize_received_date,
                'legalize_send_back_attachment'    => $legalize_send_back_attachment,
                'legalize_send_back_received_date' => $request->legalize_send_back_received_date,
                'ttbr_qty'                         => $request->ttbr_qty,
                'ttbr_attachment'                  => $ttbr_attachment,
                'status'                           => $request->status
            ]);

            if($query) {
                activity()
                    ->performedOn(new LetterWay())
                    ->causedBy(session('id'))
                    ->log('Mengubah data surat jalan');

                    $response = [
                        'status'  => 200,
                        'message' => 'Data successfully process.',
                        'result'  => $query
                    ];
            } else {
                $response = [
                    'status'  => 500,
                    'message' => 'Data gagal diproses.'
                ];
            }
        }

        return response()->json($response, $response['status']);
    }

    public function checkFinishOrder(Request $request)
    {
        $check_data = LetterWay::where('order_id', $request->order_id)
            ->whereNotExists(function($query) {
                $query->select(DB::raw(1))
                    ->from('invoice_details')
                    ->whereColumn('invoice_details.letter_way_id', 'letter_ways.id');
            });

        if($check_data->count() > 0) {
            if($check_data->where('status', 1)->count() > 0) {
                $response = [
                    'data' => $check_data->where('status', 1)->count(),
                    'code' => null
                ];
            } else {
                $response = [
                    'status' => 200,
                    'result' => [
                        'data'     => 0,
                        'code'     => Order::find($request->order_id)->code,
                        'order_id' => $request->order_id
                    ],
                ];
            }
        } else {
            $response = [
                'status' => 200,
                'result' => [
                    'data' => 1,
                    'code' => null
                ],
            ];
        }

        return response()->json($response, $response['status']);
    }

    function finish(Request $request)
    {
        $query = Order::where('id', $request->order_id)->update(['status' => 3]);
        if($query) {
            $response = [
                'status' => 200
            ];
        } else {
            $response = [
                'status' => 500
            ];
        }

        return response()->json($response, $response['status']);
    }

    public function destroy(Request $request)
    {
        $query = LetterWay::where('id', $request->id)->delete();

        if($query) {
            activity()
                ->performedOn(new LetterWay())
                ->causedBy(session('id'))
                ->log('Menghapus data surat jalan');

            $response = [
                'status'  => 200,
                'message' => 'Data telah dihapus.'
            ];
        } else {
            $response = [
                'status'  => 500,
                'message' => 'Data gagal dihapus.'
            ];
        }

        return response()->json($response, $response['status']);
    }

}
