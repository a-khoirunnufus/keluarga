<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Models\Person;

class PersonController extends Controller
{
    public function index()
    {
        $persons = Person::all();

        return view('index', [
            'persons' => $persons
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'jenis_kelamin' => 'required'
        ]);

        $data = $request->only(['nama', 'jenis_kelamin', 'parent_id']);

        if($data['parent_id'] === null || $data['parent_id'] === 'null') unset($data['parent_id']);

        try {
            DB::beginTransaction();

            Person::create($data);
            
            DB::commit();

            $request->session()->flash('status.type', 'success');
            $request->session()->flash('status.message', 'Berhasil menambahkan data.');
            return redirect()->back();

        } catch (QueryException $ex) {
            DB::rollback();

            $request->session()->flash('status.type', 'danger');
            $request->session()->flash('status.message', 'Gagal menambahkan data: '.$ex);
            return redirect()->back();
        }
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'jenis_kelamin' => 'required'
        ]);

        $data = $request->only(['nama', 'jenis_kelamin', 'parent_id']);

        if($data['parent_id'] === "null") $data['parent_id'] = null;

        try {
            DB::beginTransaction();

            Person::where(['id' => $id])->update($data);
            
            DB::commit();

            $request->session()->flash('status.type', 'success');
            $request->session()->flash('status.message', 'Berhasil mengedit data.');
            return redirect()->back();

        } catch (QueryException $ex) {
            DB::rollback();

            $request->session()->flash('status.type', 'danger');
            $request->session()->flash('status.message', 'Gagal mengedit data: '.$ex);
            return redirect()->back();
        }
    }

    public function destroy($id, Request $request)
    {
        try {
            DB::beginTransaction();

            Person::destroy($id);
            
            DB::commit();

            $request->session()->flash('status.type', 'success');
            $request->session()->flash('status.message', 'Berhasil menghapus data.');
            return redirect()->back();

        } catch (QueryException $ex) {
            DB::rollback();

            $request->session()->flash('status.type', 'danger');
            $request->session()->flash('status.message', 'Gagal menghapus data: '.$ex);
            return redirect()->back();
        }
    }
}
