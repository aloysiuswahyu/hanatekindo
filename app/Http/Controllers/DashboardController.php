<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function all(Request $request)
    {
        $getTotalUser = User::get()->count();
        $date = date('Y-m-d');
        $getUserNew = User::whereDate('created_at', '=', $date)->count();
        $return['success'] = true;
        $return['data'] = [
            'total_user' => $getTotalUser,
            'user_new' => $getUserNew,
        ];

        return response()->json($return, 200);
    }
}
