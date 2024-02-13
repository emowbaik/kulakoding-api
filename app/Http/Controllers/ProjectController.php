<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Image;
use App\Models\Komentar;
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

        $project = Project::where("user_id", $user->id)->get()->load("Image");

        return response()->json([
            'data' => $project,
            'status' => 'success',
            'message' => 'Data berhasil diambil',
        ], 200);
    }

    function Show($id) {
        $project = Project::firstWhere("id", $id)->load("Image");

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
            "deskripsi" => "required",
            "image" => "image|file|required"
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $user = Auth::user();

        $payload = [
            "nama_project" => $request->nama_project,
            "deskripsi" => $request->deskripsi,
            "user_id" => $user->id,
            "tool_id" => $request->tool
        ];

        $project = Project::create($payload);

        $image = $request->file("image");

            $extension = $image->extension();
            $dir = "storage/project/";
            $name = Str::random(32) . '.' . $extension;
            $foto = $dir . $name;
            $image->move($dir, $name);
            
            Image::create([
                "project_id" => $project->id,
                "image" => $foto
            ]);


        return response()->json([
            "message" => "Project berhasil diupload!"
        ], 201);
    }

    function Update($id, ProjectRequest $request) {
        $user = Auth::user();
        $project = Project::firstWhere("id", $id);

        $payload = $request->validated();


        if ($project) {
            if ($project->user_id == $user->id) {
                $project->update($payload);
                        
                return response()->json([
                    "message" => "Data berhasil diupdate!"
                ], 200);
            } else {
                return response()->json([
                    "message" => "Anda tidak memiliki akses untuk mengubah data ini!"
                ], 401);
            }
        } else {
            return response()->json([
                "message" => "Data tidak ditemukan"
            ], 404);
        }
    }

    function Destroy($id)
    {
        $user = Auth::user();
        $project = Project::firstWhere("id", $id);

        if ($project->user_id == $user->id) {
            $komentar = Komentar::where("project_id", $id);
            $komentar->delete();
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
