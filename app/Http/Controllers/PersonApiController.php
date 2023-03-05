<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Models\Person;
use Illuminate\Support\Facades\Validator;

class PersonApiController extends Controller
{
    public function get($personId) {
        $person = Person::find($personId);

        if($person !== null) {
            $res['id'] = $person->id;
            $res['nama'] = $person->nama;
            $res['jenisKelamin'] = $person->jenis_kelamin;
            $res['parentId'] = $person->parent_id;
            return response()->json($res, 200);
        }

        return response()->json(
            ["error" => "Person tidak tersedia"], 
            404
        );
    }

    public function getAll() {
        $people = Person::all()->toArray();
        $res = array();

        foreach ($people as $person) {
            $res[] = [
                'id' => $person['id'],
                'nama' => $person['nama'],
                'jenisKelamin' => $person['jenis_kelamin'],
                'parentId' => $person['parent_id']
            ];
        }

        return response()->json($res, 200);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|min:1',
            'jenisKelamin' => 'required|string|in:laki-laki,perempuan',
            'parentId' => 'required|integer|min:1|nullable'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors, 400);
        }

        $req = $request->only(['nama', 'jenisKelamin', 'parentId']);
        
        if (Person::where('id', $req['parentId'])->doesntExist()) {
            return response()->json(
                ['error' => "Tidak dapat menemukan parentId"],
                409
            );
        }

        $data['nama'] = $req['nama'];
        $data['jenis_kelamin'] = $req['jenisKelamin'];
        $data['parent_id'] = $req['parentId'];

        try {
            DB::beginTransaction();
            $person = Person::create($data);
            DB::commit();

            $res['id'] = $person->id;
            $res['nama'] = $person->nama;
            $res['jenisKelamin'] = $person->jenis_kelamin;
            $res['parentId'] = $person->parent_id;
            return response()->json($res, 200);
        
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(
                ['error' => "Gagal menambahkan person baru"],
                409
            );
        }
    }

    public function update($personId, Request $request) {
        if (Person::where('id', $personId)->doesntExist()) {
            return response()->json(
                ["error" => "Person tidak tersedia"], 
                404
            );
        } 

        $validator = Validator::make($request->all(), [
            'nama' => 'string|min:1',
            'jenisKelamin' => 'string|in:laki-laki,perempuan',
            'parentId' => 'integer|min:1|nullable'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors, 400);
        }

        $req = $request->only(['nama', 'jenisKelamin', 'parentId']);
        
        if (
            isset($req['parentId']) 
            && Person::where('id', $req['parentId'])->doesntExist()
        ) {
            return response()->json(
                ['error' => "Tidak dapat menemukan parentId"],
                409
            );
        }

        $data = array();
        isset($req['nama']) && $data['nama'] = $req['nama'];
        isset($req['jenisKelamin']) && $data['jenis_kelamin'] = $req['jenisKelamin'];
        isset($req['parentId']) && $data['parent_id'] = $req['parentId'];

        try {
            DB::beginTransaction();
            $person = Person::find($personId);
            isset($req['nama']) && $person->nama = $req['nama'];
            isset($req['jenisKelamin']) && $person->jenis_kelamin = $req['jenisKelamin'];
            isset($req['parentId']) && $person->parent_id = $req['parentId'];
            $person->save();
            DB::commit();

            $res['id'] = $person->id;
            $res['nama'] = $person->nama;
            $res['jenisKelamin'] = $person->jenis_kelamin;
            $res['parentId'] = $person->parent_id;
            return response()->json($res, 200);
        
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(
                ['error' => "Gagal mengedit data person"],
                409
            );
        }
    }

    public function destroy($personId, Request $request) {
        if (Person::where('id', $personId)->doesntExist()) {
            return response()->json(
                ["error" => "Person tidak tersedia"], 
                404
            );
        }

        try {
            DB::beginTransaction();
            Person::destroy($personId);
            DB::commit();

            return response()->json(
                ["success" => "Berhasil menghapus person"], 
                200
            );
        
        } catch (QueryException $ex) {
            if ($ex->getCode() === "23503") {
                DB::rollback();
                return response()->json(
                    ['error' => "Person ini memiliki children"],
                    409
                );
            }
            throw $ex;
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(
                ['error' => "Gagal menghapus person"],
                409
            );
        }
    }
}
