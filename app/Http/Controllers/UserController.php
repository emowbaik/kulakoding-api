<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    function Index() {
        $user = Auth::user();
        $project = Project::where("user_id", $user->id)->paginate(6);

        if ($project) {
            return response()->json([
                "data" => $project 
            ], 200);
        } else {
            return response()->json([
                "message" => "Tidak ada project"
            ], 404);
        }
    }
}
