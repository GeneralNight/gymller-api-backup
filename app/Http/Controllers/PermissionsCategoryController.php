<?php

namespace App\Http\Controllers;

use App\Models\PermissionsCategory;
use Illuminate\Http\Request;

class PermissionsCategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function all() {
        return response()->json([
            "msg" => "success",
            "data" => PermissionsCategory::get()
        ]);
    }

    public function store(Request $request) {
        $data = $request->all();
        $descExist = PermissionsCategory::where("description",$data["description"])->count() || 0;
        if($descExist) {
            return response()->json([
                "msg" => "error",
                "data" => "A category with this description already exist"
            ]);
        }

        return response()->json([
            "msg" => "success",
            "data" => PermissionsCategory::create($data)
        ]);
    }

    public function update(Request $request, $permCatId) {
        $data = $request->all();

        $descExist = PermissionsCategory::where("id",$permCatId)->count() || 0;

        if(!$descExist) {
            return response()->json([
                "msg" => "error",
                "data" => "There's no category with this id"
            ]);
        }

        $desc = PermissionsCategory::where("id",intVal($permCatId))->first();

        if($desc["description"] != $data["description"]) {
            $descExistDesc = PermissionsCategory::where("description",$data["description"])->count() || 0;

            if($descExistDesc) {
                return response()->json([
                    "msg" => "error",
                    "data" => "There's other category with this description"
                ]);
            }
        }

        $desc->description = $data["description"];

        return response()->json([
            "msg" => "success",
            "data" => $desc->save()
        ]);
    }

    public function delete($permCatId) {
        $catExist = PermissionsCategory::where("id",$permCatId)->count() || 0;

        if(!$catExist) {
            return response()->json([
                "msg" => "error",
                "data" => "There's any permission category with this id"
            ]);
        }

        return response()->json([
            "msg" => "success",
            "data" => PermissionsCategory::find($permCatId)->delete()
        ]);
    }

    //
}
