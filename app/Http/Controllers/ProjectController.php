<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Js;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    function Index() {
        $user = Auth::user();

        $project = Project::where("user_id", $user->id)->get();

        return response()->json($project, 200);
    }

    function Show($id) {
        $project = Project::firstWhere("id", $id);

        if ($project) {
            return response()->json($project, 200);
        } else {
            return response()->json([
                "message" => "Data tidak ditemukan"
            ], 404);
        }

    }

    function Store(Request $request) {
        $validation = Validator::make($request->all(), [
            "nama_project" => "required",
            "image" => "required",
            "deskripsi" => "required",
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 401);
        }

        $user = Auth::user();

        $file = $request->file("image");
        $extension = $file->extension();
        $dir = "storage/project";
        $name = Str::random(32) . "." . $extension;
        $image = $dir . $name;

        $payload = [
            "user_id" => $user->id,
            "nama_project" => $request->nama_project,
            "image" => $image,
            "deskripsi" => $request->deskripsi,
            "github" => $request->github
        ];

        $project = Project::create($payload);

        $file->move($dir, $name);

        return response()->json([
            "message" => "Project berhasil diupload!"
        ], 201);
    }

    function Update($id, Request $request) {
        $user = Auth::user();
        $project = Project::firstWhere("id", $id);

        if ($project->user_id == $user->id) {
            $project->update([
                "nama_project" => $request->nama_project,
                "deskripsi" => $request->deskripsi
            ]);
            $project->save();
    
            return response()->json([
                "message" => "Data berhasil diupdate!"
            ], 200);
        } else {
            return response()->json([
                "message" => "Anda tidak memiliki akses untuk mengubah data ini!"
            ], 401);
        }
    }

    function Destroy($id) {
        $user = Auth::user();
        $project = Project::firstWhere("id", $id);

        if ($project->user_id == $user->id) {
            $project->delete();
    
            return response()->json([
                "message" => "Data berhasil dihapus"
            ], 200);
        } else {
            return response()->json([
                "message" => "Anda tidak memiliki akses untuk mengubah data ini!"
            ], 401);
        }
    }
}
