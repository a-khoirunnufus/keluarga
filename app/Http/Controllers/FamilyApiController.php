<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Models\Person;
use Illuminate\Support\Facades\Validator;

class FamilyApiController extends Controller
{
    public function grandparent($personId)
    {
        if (Person::where('id', $personId)->doesntExist()) {
            return response()->json(
                ["error" => "Person tidak tersedia"], 
                404
            );
        }

        $result = DB::select("
            SELECT DISTINCT
                kakek.id,
                kakek.nama,
                kakek.jenis_kelamin,
                kakek.parent_id
            FROM person AS kakek
                LEFT JOIN person AS ortu ON kakek.id = ortu.parent_id
                LEFT JOIN person AS anak ON ortu.id = anak.parent_id
            WHERE anak.id = ?
            LIMIT 1
        ", [$personId]);

        if(empty($result)) {
            return response()->json(["result" => null], 200); 
        }

        $grandparent = $result[0];

        $res['id'] = $grandparent->id;
        $res['nama'] = $grandparent->nama;
        $res['jenisKelamin'] = $grandparent->jenis_kelamin;
        $res['parentId'] = $grandparent->parent_id;

        return response()->json(["result" => $res], 200);
    }

    public function parent($personId)
    {
        if (Person::where('id', $personId)->doesntExist()) {
            return response()->json(
                ["error" => "Person tidak tersedia"], 
                404
            );
        }

        $result = DB::select("
            SELECT
                id,
                nama,
                jenis_kelamin,
                parent_id
            FROM person
            WHERE id = (
                SELECT parent_id 
                FROM person
                WHERE id = ?
                LIMIT 1
            )
        ", [$personId]);

        if(empty($result)) {
            return response()->json(["result" => null], 200); 
        }

        $parent = $result[0];

        $res['id'] = $parent->id;
        $res['nama'] = $parent->nama;
        $res['jenisKelamin'] = $parent->jenis_kelamin;
        $res['parentId'] = $parent->parent_id;

        return response()->json(["result" => $res], 200);
    }

    public function child($personId, Request $request) 
    {
        if (Person::where('id', $personId)->doesntExist()) {
            return response()->json(
                ["error" => "Person tidak tersedia"], 
                404
            );
        }

        $filter = "";
        if ($request->query('jenisKelamin')) {
            $validator = Validator::make($request->query(), [
                'jenisKelamin' => 'string|in:laki-laki,perempuan'
            ]);
    
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors, 400);
            }

            $filter = "AND jenis_kelamin = '".$request->query('jenisKelamin')."'";
        }

        $results = DB::select("
            SELECT 
                id,
                nama,
                jenis_kelamin,
                parent_id 
            FROM person
            WHERE parent_id = ?
            {$filter}
        ", [$personId]);

        if(empty($results)) {
            return response()->json(["result" => []], 200); 
        }

        $res = array_map(function($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'jenisKelamin' => $item->jenis_kelamin,
                'parentId' => $item->parent_id
            ];
        }, $results);

        return response()->json(["result" => $res], 200);
    }

    public function grandchild($personId, Request $request) 
    {
        if (Person::where('id', $personId)->doesntExist()) {
            return response()->json(
                ["error" => "Person tidak tersedia"], 
                404
            );
        }

        $filter = "";
        if ($request->query('jenisKelamin')) {
            $validator = Validator::make($request->query(), [
                'jenisKelamin' => 'string|in:laki-laki,perempuan'
            ]);
    
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors, 400);
            }

            $filter = "AND jenis_kelamin = '".$request->query('jenisKelamin')."'";
        }

        $results = DB::select("
            SELECT 
                id,
                nama, 
                jenis_kelamin,
                parent_id
            FROM person
            WHERE parent_id IN (
                SELECT id 
                FROM person
                WHERE parent_id = ?
            )
            {$filter}
        ", [$personId]);

        if(empty($results)) {
            return response()->json(["result" => []], 200); 
        }

        $res = array_map(function($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'jenisKelamin' => $item->jenis_kelamin,
                'parentId' => $item->parent_id
            ];
        }, $results);

        return response()->json(["result" => $res], 200);
    }

    public function aunt($personId)
    {
        if (Person::where('id', $personId)->doesntExist()) {
            return response()->json(
                ["error" => "Person tidak tersedia"], 
                404
            );
        }

        $results = DB::select("
            SELECT DISTINCT
                bibi.id,
                bibi.nama, 
                bibi.jenis_kelamin,
                bibi.parent_id
            FROM person AS bibi
                LEFT JOIN person AS kakek ON kakek.id = bibi.parent_id
                LEFT JOIN person AS ortu ON kakek.id = ortu.parent_id
                LEFT JOIN person AS anak ON ortu.id = anak.parent_id
                    AND anak.parent_id != bibi.id
            WHERE anak.id = ?
                AND bibi.jenis_kelamin = 'perempuan';
        ", [$personId]);

        if(empty($results)) {
            return response()->json(["result" => []], 200); 
        }

        $res = array_map(function($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'jenisKelamin' => $item->jenis_kelamin,
                'parentId' => $item->parent_id
            ];
        }, $results);

        return response()->json(["result" => $res], 200);
    }

    public function uncle($personId)
    {
        if (Person::where('id', $personId)->doesntExist()) {
            return response()->json(
                ["error" => "Person tidak tersedia"], 
                404
            );
        }

        $results = DB::select("
            SELECT DISTINCT
                paman.id,
                paman.nama, 
                paman.jenis_kelamin,
                paman.parent_id
            FROM person AS paman
                LEFT JOIN person AS kakek ON kakek.id = paman.parent_id
                LEFT JOIN person AS ortu ON kakek.id = ortu.parent_id
                LEFT JOIN person AS anak ON ortu.id = anak.parent_id
                    AND anak.parent_id != paman.id
            WHERE anak.id = ?
                AND paman.jenis_kelamin = 'laki-laki';
        ", [$personId]);

        if(empty($results)) {
            return response()->json(["result" => []], 200); 
        }

        $res = array_map(function($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'jenisKelamin' => $item->jenis_kelamin,
                'parentId' => $item->parent_id
            ];
        }, $results);

        return response()->json(["result" => $res], 200);
    }

    public function cousin($personId, Request $request) 
    {
        if (Person::where('id', $personId)->doesntExist()) {
            return response()->json(
                ["error" => "Person tidak tersedia"], 
                404
            );
        }

        $filter = "";
        if ($request->query('jenisKelamin')) {
            $validator = Validator::make($request->query(), [
                'jenisKelamin' => 'string|in:laki-laki,perempuan'
            ]);
    
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors, 400);
            }

            $filter = "AND sepupu.jenis_kelamin = '".$request->query('jenisKelamin')."'";
        }

        $results = DB::select("
            SELECT DISTINCT 
                sepupu.id,
                sepupu.nama, 
                sepupu.jenis_kelamin,
                sepupu.parent_id
                FROM person AS kakek
                LEFT JOIN person AS ortu ON kakek.id = ortu.parent_id
                LEFT JOIN person AS anak ON ortu.id = anak.parent_id
                LEFT JOIN person AS paman ON kakek.id = paman.parent_id
                    AND paman.id != ortu.id
                LEFT JOIN person AS sepupu ON paman.id = sepupu.parent_id
            WHERE anak.id = ?
                AND sepupu.id IS NOT NULL
                {$filter}
        ", [$personId]);

        if(empty($results)) {
            return response()->json(["result" => []], 200); 
        }

        $res = array_map(function($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'jenisKelamin' => $item->jenis_kelamin,
                'parentId' => $item->parent_id
            ];
        }, $results);

        return response()->json(["result" => $res], 200);
    }

    public function getTree()
    {
        if (DB::select("SELECT COUNT(*) FROM person")[0]->count == 0) {
            return response()->json(
                ["message" => "Hirarki keluarga tidak tersedia"], 
                404
            );
        }

        try {
            $root = Person::where(['parent_id' => null])->first();
            $root_node = [
                "text" => [
                    "name" => $root->nama,
                    "data-gender" => $root->jenis_kelamin === 'laki-laki' ? 'male' : 'female',
                ],
                "children" => array(),
            ];
    
            $persons = Person::all()->toArray();
            $root_node['children'] = $this->buildTree($persons, $root->id);
            $tree = $root_node;
            
            return response()->json($tree, 200);
        } catch (\Throwable $th) {
            return response()->json(
                ["error" => "Gagal mendapatkan hirarki keluarga"],
                500
            );
        }
    }

    // utility function
    private function buildTree(array $elements, $parentId = 1) {
        $branch = array();
    
        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                $node_element = [
                    "text" => [
                        "name" => $element['nama'],
                        "data-gender" => $element['jenis_kelamin'] === 'laki-laki' ? 'male' : 'female',
                    ]
                ];
                if ($children) {
                    $node_element['children'] = $children;
                }
                $branch[] = $node_element;
            }
        }
    
        return $branch;
    }
}
