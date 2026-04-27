<?php

namespace App\Http\Controllers;

use App\Models\Post;

class GremialController extends Controller
{
    public function index()
    {
        $posts = Post::query()
            ->where('category', 'gremial')
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        return view('gremial.index', compact('posts'));
    }
}
