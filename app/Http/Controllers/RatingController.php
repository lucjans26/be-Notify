<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function like()
    {
        $post_id = request('post_id');
        $post = Post::find($post_id);
        $post->likes()->create([
            'user_id' => auth()->id()
        ]);
    }

    public function dislike()
    {
        $post_id = request('post_id');
        $post = Post::find($post_id);
        $post->likes()->where('user_id', auth()->id())->delete();
    }
}
