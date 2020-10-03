<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class AdminController extends Controller
{
    public function register(Request $request)
    {
        //validate data
        $this->validate($request, [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

//        Another way to get request
//        $input = $request->only('name','email','password');

        //register user
        try {
            $admin = new Admin;
//            $user->name = $input['name'];
            $admin->name = $request->input('name');
            $admin->email = $request->input('email');
            $password = $request->input('password');
            $admin->password = app('hash')->make($password); //this will create hash string

            if ($admin->save() ){
                $code = 200;
                $output = [
                    'admin' => $admin,
                    'code'=> $code,
                    'message'=>"Admin created successfully."
                ];
            }else{
                $code = 500;
                $output = [
                    'code'=> $code,
                    'message'=>"An error occurred while creating admin."
                ];
            }

        }catch (Exception $e){
//            dd($e->getMessage());
            $code = 500;
            $output = [
                'code'=> $code,
                'message'=>"An error occurred while creating admin."
            ];
        }

        //return response
        return response()->json($output, $code);

    }

    public function login(Request $request){

        Config::set('jwt.user', 'App\Models\Admin');
        Config::set('auth.providers.users.model', \App\Models\Admin::class);

        //validate data
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $input = $request->only('email','password');

        if(! $authorized = Auth::attempt($input)){
            $code = 401;
            $output = [
                'code'=> $code,
                'message'=>"Admin is not authorized."
            ];
        } else {
            $code = 201;
            $token = $this->respondWithToken($authorized);
            $output = [
                'code'=> $code,
                'message'=>"Admin logged in succesfully.",
                'token' => $token
            ];
        }
        return response()->json($output,$code);

    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
