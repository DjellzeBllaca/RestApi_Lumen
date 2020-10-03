<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller
{
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
        $this->validate($request, [
            'title' => 'required|min:3|max:255',
            'description' => 'required'
        ]);

        if (Auth::user()) {
            $post = new Post;
            $post->title = $request->input('title');
            $post->description = $request->input('description');
            $post->user_id = Auth::user()->id;

            $post->save();
            //record has been saved
            $code = 201;
            $output = [
                'code'=> $code,
                'message'=>"Post has been created."
            ];
        }else {
            $code = 409;
            $output = [
                'code'=> $code,
                'message'=>"Error: Could not create post!"
            ];
        }
        return response()->json($output,$code);
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
        try{
            $this->validate($request, [
                'title' => 'required|min:3|max:255',
                'description' => 'required'
            ]);

            if (Auth::user()) {
                $post = Post::where(['user_id' => Auth::user()->id, 'id' => $id])->first();

                if (!$post) {
                    $code = 409;
                    $output = [
                        'code'=> $code,
                        'message'=>"You don't have permission to update this post!"
                    ];
                }

                $post->title = $request->input('title');
                $post->description = $request->input('description');
                $post->save();

                $code = 201;
                $output = [
                    'code'=> $code,
                    'message'=>"Post updated successfully!"
                ];
            }
            return response()->json($output, $code);
        }catch (\Exception $e) {
            $code = 409;
            $output = [
                'code'=> $code,
                'message'=>"Post Failed to update!"
            ];
            return response()->json($output, $code);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        try{
            if (Auth::user()) {
                $post = Post::where(['id' => $id], ['user_id', Auth::user()->id])->first();
                Comment::where('post_id', $id)->delete();

                if ($post->delete()){
                    $code = 201;
                    $output = [
                        'code'=> $code,
                        'message'=>"Post deleted successfully!"
                    ];
                }
                }
            else{
                $code = 409;
                $output = [
                    'code'=> $code,
                    'message'=>"Post failed to delete!!"
                ];
            }

        } catch (\Exception $e) {
            $code = 409;
            $output = [
                'code'=> $code,
                'message'=>"Post failed to delete!"
            ];
        }
        return response()->json($output, $code);

    }
}
