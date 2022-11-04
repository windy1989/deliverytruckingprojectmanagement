<?php

namespace App\Http\Controllers;

use App\Models\Coa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class CoaController extends Controller
{

    public function index()
    {
        $data = [
            'title'   => 'Digitrans - COA',
            'content' => 'accounting.coa'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function getParent()
    {
        $data = Coa::where('parent_id', 0)->get();
        return response()->json($data);
    }


    public function getLatestSub(Request $request)
    {
        if($request->id == 0){
            $data = Coa::where('parent_id', $request->id)->latest('id')->first();
        }else{
            $data = Coa::where('parent_id', $request->id)->latest('code')->first();
        }
   

        
        return response()->json($data);
    }
    
    public function getSub(Request $request)
    {
        $parent   = Coa::where('code', $request->code)->first();
        $data     = Coa::where('parent_id', $parent->id)->oldest('code')->get();
        $response = [];

        foreach ($data as $d) {
            $response[] = [
                'code'        => $d->code,
                'name'        => $d->name,
                'description' => $d->description ? $d->description : '',
                'status'      => $d->status(),
                'action'      => '
                    <a href="javascript:void(0);" class="text-warning" onclick="show(' . $d->id . ')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>
                    <a href="javascript:void(0);" class="text-danger" onclick="destroy(' . $d->id . ')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></a>
                '
            ];
        }

        return response()->json($response);
    }

    public function datatable(Request $request)
    {
        $column = [
            'id',
            'code',
            'name',
            'description',
            'status'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Coa::where('parent_id', 0)
            ->count();

        $query_data = Coa::where('parent_id', 0)
            ->where(function ($query) use ($search, $request) {
                if ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('name', 'like', "%$search%")
                            ->orWhere('description', 'like', "%$search%");
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderByRaw('LENGTH(code)', 'ASC')
            ->orderBy(DB::raw('substr(`code`,1,1)'), 'ASC')
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Coa::where('parent_id', 0)
            ->where(function ($query) use ($search, $request) {
                if ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('name', 'like', "%$search%")
                            ->orWhere('description', 'like', "%$search%");
                    });
                }
            })
            ->count();

        $response['data'] = [];
        if ($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach ($query_data as $val) {
                $response['data'][] = [
                    '<span class="badge badge-success"><i class="fas fa-plus"></i></span>',
                    $nomor,
                    $val->code,
                    $val->name,
                    $val->description,
                    $val->status(),
                    '
                        <button type="button" class="btn btn-warning btn-sm" onclick="show(' . $val->id . ')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Edit</button>
                        <button type="button" class="btn btn-danger btn-sm" onclick="destroy(' . $val->id . ')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg> Hapus</button>
                    '
                ];
                $nomor++;
            }
        }

        $response['recordsTotal'] = 0;
        if ($total_data <> FALSE) {
            $response['recordsTotal'] = $total_data;
        }

        $response['recordsFiltered'] = 0;
        if ($total_filtered <> FALSE) {
            $response['recordsFiltered'] = $total_filtered;
        }

        return response()->json($response);
    }

    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'code'   => 'required|unique:coas,code',
            'name'   => 'required',
            'status' => 'required'
        ], [
            'code.required'   => 'Mohon mengisi kode.',
            'code.unique'     => 'Kode telah digunakan.',
            'name.required'   => 'Mohon mengisi nama.',
            'status.required' => 'Mohon memilih status.'
        ]);

        if ($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Coa::create([
                'code'        => $request->code,
                'name'        => $request->name,
                'parent_id'   => $request->parent_id,
                'description' => $request->description,
                'status'      => $request->status
            ]);

            if ($query) {
                activity()
                    ->performedOn(new Coa())
                    ->causedBy(session('id'))
                    ->withProperties($query)
                    ->log('Menambah data COA ' . $query->code);

                $response = [
                    'status'  => 200,
                    'message' => 'Data telah diproses.'
                ];
            } else {
                $response = [
                    'status'  => 500,
                    'message' => 'Data gagal diproses.'
                ];
            }
        }

        return response()->json($response);
    }

    public function show(Request $request)
    {
        $data = Coa::find($request->id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'code'   => ['required', Rule::unique('coas', 'code')->ignore($id)],
            'name'   => 'required',
            'status' => 'required'
        ], [
            'code.required'   => 'Mohon mengisi kode.',
            'code.unique'     => 'Kode telah digunakan.',
            'name.required'   => 'Mohon mengisi nama.',
            'status.required' => 'Mohon memilih status.'
        ]);

        if ($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Coa::where('id', $id)->update([
                'code'        => $request->code,
                'name'        => $request->name,
                'parent_id'   => $request->parent_id,
                'description' => $request->description,
                'status'      => $request->status
            ]);

            if ($query) {
                activity()
                    ->performedOn(new Coa())
                    ->causedBy(session('id'))
                    ->log('Mengubah data COA');

                $response = [
                    'status'  => 200,
                    'message' => 'Data telah diproses.'
                ];
            } else {
                $response = [
                    'status'  => 500,
                    'message' => 'Data gagal diproses.'
                ];
            }
        }

        return response()->json($response);
    }

    public function destroy(Request $request)
    {
        $query = Coa::where('id', $request->id)->delete();
        if ($query) {
            activity()
                ->performedOn(new Coa())
                ->causedBy(session('id'))
                ->log('Menghapus data COA');

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

        return response()->json($response);
    }
}
