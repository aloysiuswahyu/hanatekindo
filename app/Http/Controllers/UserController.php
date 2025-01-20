<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResources;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function auth(Request $request)
    {
        try {
            $param = $request->post();

            $email = $param['email'] ?? '';
            $password = $param['password'] ?? '';
            $return['errors'] = [
                'mesage' => 'username dan password tidak sesuai',
                'blocked' => 0,
            ];
            $return['success'] = false;
            $return['blocked'] = 0;
            $return['data'] = [];
            $checkUser = User::where('email', $email)->first();

            if (empty($checkUser)) {
                $return['errors'] = [
                    'mesage' => 'email tidak terdaftar',
                    'blocked' => 0,
                ];
                $return['success'] = false;
                $return['blocked'] = 0;
                $return['data'] = [];

                return response()->json($return, 422);
            }

            if (!Hash::check($password, $checkUser->password)) {
                $return['errors'] = [
                    'mesage' => 'password tidak sesuai',
                    'blocked' => 0,
                ];
                $return['success'] = false;
                $return['blocked'] = 0;
                $return['data'] = [];

                return response()->json($return, 422);
            } else {
                // $user = Auth::user();
                // dd($token);
                // $token = $user->createToken()->accessToken;
                $token = $checkUser->createToken('sss')->plainTextToken;
                // dd($token);
                $user = new UserResources($checkUser);

                $return['success'] = false;
                $return['blocked'] = 0;
                $return['data'] = $user;
                $return['token'] = $token;

                return response()->json($return, 200);
            }
            dd($return, 'sss');

            return response()->json($return, 422);
        } catch (\Throwable $e) {
            dd($e);
            $return['success'] = false;
            $return['data'] = [];
            $return['errors'] = [
                'mesage' => 'internal server error',
            ];

            return response()->json($return, 400);
        }
    }

    public function noToken(Request $request)
    {
        $return['success'] = false;
        $return['data'] = [];
        $return['message'] = 'token tidak ada';

        return response()->json($return, 403);
    }

    public function all(Request $request)
    {
        $getUser = User::paginate('4');
        $param = $request->post();

        $return['success'] = false;
        $return['data'] = [];

        if (!empty($getUser)) {
            $return['success'] = true;
            $return['blocked'] = 0;
            $return['data'] = UserResources::collection($getUser);

            return response()->json($return, 200);
        }

        return response()->json($return, 400);

        return response()->json($return, 422);
    }

    public function view(Request $request, $id)
    {
        try {
            $getUser = User::where('id', $id)->first();
            $param = $request->post();
            // dd($param);
            $return['success'] = false;
            $return['data'] = [];
            if (!empty($getUser)) {
                $return['success'] = true;
                $return['blocked'] = 0;
                $return['data'] = new UserResources($getUser);

                return response()->json($return, 200);
            }

            return response()->json($return, 400);
        } catch (\Throwable $e) {
            dd($e);
            $return['success'] = false;
            $return['data'] = [];
            $return['errors'] = [
                'mesage' => 'internal server error',
            ];

            return response()->json($return, 400);
        }
    }

    public function create(Request $request)
    {
        try {
            $param = $request->post();
            // dd($param);
            $name = $param['name'] ?? '';
            $password = $param['password'] ?? '';
            $email = $param['email'] ?? '';

            $return['success'] = false;
            $return['data'] = [];
            $param = [
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
            ];
            // dd($param);
            $user = User::create($param);
            // dd($user);
            if (!empty($user)) {
                // $return['errors'] = [
                //     'mesage' => 'password tidak sesuai',
                //     'blocked' => 0,
                // ];

                $return['success'] = true;
                $return['blocked'] = 0;
                $return['data'] = new UserResources($user);

                return response()->json($return, 200);
            }

            return response()->json($return, 400);
        } catch (\Throwable $e) {
            $return['success'] = false;
            $return['data'] = [];
            $return['errors'] = [
                'mesage' => 'internal server error',
            ];

            return response()->json($return, 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $getUser = User::where('id', $id)->first();
            $param = $request->post();
            // dd($getUser);/
            $return['success'] = false;
            $return['data'] = [];
            if (!empty($getUser)) {
                $param = $request->post();
                // dd($getUser->password);

                $getUser->name = $param['name'] ?? '';
                // $getUser->password = $param['password'] != '' ? Hash::make($param['password']) : $getUser->password;
                // $getUser->email = $param['email'] ?? '';
                $getUser->save();
                // $return['errors'] = [
                //     'mesage' => 'password tidak sesuai',
                //     'blocked' => 0,
                // ];

                $return['success'] = true;
                $return['blocked'] = 0;
                $return['data'] = new UserResources($getUser);

                return response()->json($return, 200);
            }

            return response()->json($return, 400);
        } catch (\Throwable $e) {
            $return['success'] = false;
            $return['data'] = [];
            $return['errors'] = [
                'mesage' => $e,
            ];

            return response()->json($return, 400);
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $getUser = User::where('id', $id)->first();
            $param = $request->post();
            // dd($param);
            $return['success'] = false;
            $return['data'] = [];
            if (!empty($getUser)) {
                $getUser->delete();

                $return['success'] = true;
                $return['blocked'] = 0;
                $return['data'] = [];

                return response()->json($return, 200);
            }

            return response()->json($return, 400);
        } catch (\Throwable $e) {
            $return['success'] = false;
            $return['data'] = [];
            $return['errors'] = [
                'mesage' => 'internal server error',
            ];

            return response()->json($return, 400);
        }
    }

    public function logout(Request $request, $id)
    {
        try {
            Auth::guard('web')->logout();
            $return['success'] = true;
            $return['data'] = [];

            return response()->json($return, 400);
        } catch (\Throwable $e) {
            $return['success'] = false;
            $return['data'] = [];
            $return['errors'] = [
                'mesage' => 'internal server error',
            ];

            return response()->json($return, 400);
        }
    }
}
