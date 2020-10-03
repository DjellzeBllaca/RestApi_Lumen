<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    public function create(Request $request)
    {
        try {

            $this->validate($request, [
                'comment' => 'required'
            ]);
            if (Auth::user()) {
                $comment = new Comment();
                $comment->comment = $request->input('comment');
                $comment->post_id = $request->input('post_id');
                $comment->user_id = Auth::user()->id;

                $comment->save();

                $code = 201;
                $output = [
                    'code'=> $code,
                    'message'=>"Commented updated successfully!"
                ];
            }
            else{
                $code = 409;
                $output = [
                    'code'=> $code,
                    'message'=>"Comment failed!"
                ];
            }


        } catch (\Exception $e) {
            $code = 409;
            $output = [
                'code'=> $code,
                'message'=>"A problem occurred, Comment isn't saved!"
            ];
        }

        return response()->json($output, $code);
    }
}
