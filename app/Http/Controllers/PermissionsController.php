<?php

namespace App\Http\Controllers;

use App\Models\Permissions;
use App\Models\PermissionsCategory;
use Illuminate\Http\Request;

class PermissionsController extends Controller
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
           "data" =>  Permissions::get()
        ]);
    }

    public function store(Request $request) {
        $data = $request->all();

        $permCatExist = PermissionsCategory::where([
                "id" => $data["permission_category_id"]
        ])->count() || 0;

        if(!$permCatExist) {
            return response()->json([
                "msg" => "success",
                "data" =>  "There's any category with this id"
            ]);
        }

        $permExist = Permissions::where([
            "description" => $data["description"],
            "permission_category_id" => $data["permission_category_id"]
        ])->count() || 0;

        if($permExist) {
            return response()->json([
                "msg" => "success",
                "data" =>  "There's a permission with this name in this category"
            ]);
        }

        return response()->json([
            "msg" => "success",
            "data" =>  Permissions::create($data)
        ]);
    }

    public function update(Request $request, $permId) {
        $data =  $request->all();

        $permCatExist = PermissionsCategory::where([
            "id" => intVal($permId)
        ])->count() || 0;

        if(!$permCatExist) {
            return response()->json([
                "msg" => "success",
                "data" =>  "There's any category with this id"
            ]);
        }

        $perm = Permissions::where("id",$permId)->first();

        if($perm["description"] != $data["description"]) {
            $permExist = Permissions::where([
                "description" => $data["description"],
                "permission_category_id" => $data["permission_category_id"]
            ])->count() || 0;

            if($permExist) {
                return response()->json([
                    "msg" => "success",
                    "data" =>  "There's a permission with this name in this category"
                ]);
            }
        }

        $perm->description = $data["description"];
        return response()->json([
            "msg" => "success",
            "data" => $perm->save()
        ]);

    }

    public function delete($permId) {
        $permExist = Permissions::where("id",intVal($permId))->count() || 0;
        if(!$permExist) {
            return response()->json([
                "msg" => "error",
                "data" => "No Permission with this id"
            ]);
        }

        return response()->json([
            "msg" => "success",
            "data" => Permissions::find($permId)->delete()
        ]);
    }

    //
}
