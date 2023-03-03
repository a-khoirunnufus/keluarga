<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Models\Person;

class PersonApiController extends Controller
{
    public function getTree()
    {
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
            
            return response()->json([
                "status" => "success",
                "data" => $tree,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => $th->getMessage(),
            ]);
        }
    }

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
