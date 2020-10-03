<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class UserController extends Controller
{

    public function register(Request $request)
    {
        //validate data
        $this->validate($request, [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'address' => 'required|string',
            'phone' => 'required|string',
            'age' => 'required|integer'
        ]);

//        Another way to get request
//        $input = $request->only('name','email','password');

        //register user
        try {
            $user = new User;
//            $user->name = $input['name'];
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $password = $request->input('password');
            $user->password = app('hash')->make($password); //this will create hash string

            $user->save();

            $user_details = new UserDetails;
            $user_details->user_id = $user->id;
            $user_details->phone = $request->input('phone');
            $user_details->address = $request->input('address');
            $user_details->age = $request->input('age');
            $user_details->save();

            $code = 200;
            $output = [
                'user' => $user,
                'user_details' => $user_details,
                'code'=> $code,
                'message'=>"User created successfully."
            ];

        }catch (Exception $e){
//            dd($e->getMessage());
            $code = 500;
            $output = [
                'code'=> $code,
                'message'=>"An error ocurred while creating user."
            ];
        }

        //return response
        return response()->json($output, $code);

    }

    public function login(Request $request){

        Config::set('jwt.user', 'App\Models\User');
        Config::set('auth.providers.users.model', \App\Models\User::class);

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
                'message'=>"User is not authorized."
            ];
        } else {
            $code = 201;
            $token = $this->respondWithToken($authorized);
            $output = [
                'code'=> $code,
                'message'=>"User logged in succesfully.",
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
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */

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
