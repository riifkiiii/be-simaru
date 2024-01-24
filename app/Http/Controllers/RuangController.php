<?php

namespace App\Http\Controllers;

use App\Models\Ruangan; //untuk memanggil database yg diperlukan
use Illuminate\Http\Request;
use Validator;

class RuangController extends Controller
{
    public function index()
    {
        return response()->json(Ruangan::All())->header('Access-Control-Allow-Origin', '*')
            ->header(
                'Access-Control-Allow-Methods',
                'GET, POST, PUT, DELETE, OPTIONS'
            )
            ->header(
                'Access-Control-Allow-Headers',
                'Accept,Authorization,Content-Type'
            );;
    }

    public function indexPage()
    {
        $userRole = auth()->user()->role;
        return view('ruangans/index', compact('userRole'));
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_ruangan' => 'required',
            'kapasitas' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $ruangan = Ruangan::create([
            'nama_ruangan' => $request->nama_ruangan,
            'keterangan' => $request->keterangan,
            'kapasitas' => $request->kapasitas,
        ]);
        return response()->json([
            'message' => 'Ruangan successfully created',
            'ruangan' => $ruangan
        ], 201);
    }

    public function edit(Ruangan $ruangan)
    {
        return response()->json($ruangan);
    }

    public function update(Request $request, Ruangan $ruangan)
    {
        $validator = Validator::make($request->all(), [
            'nama_ruangan' => 'required',
            'kapasitas' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $ruangan = Ruangan::where('id', $ruangan->id)->update([
            'nama_ruangan' => $request->nama_ruangan,
            'keterangan' => $request->keterangan,
            'kapasitas' => $request->kapasitas,
        ]);
        return response()->json([
            'message' => 'Ruangan successfully updated'
        ], 201);
    }

    public function delete($id)
    {
        $ruangan = Ruangan::find($id);
        $ruangan->delete();
        return response()->json([
            'message' => 'Ruangan successfully deleted',
        ], 201);
    }
}
