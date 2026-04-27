<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostComment;
use Illuminate\Http\Request;

class NovedadesController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->string('category')->toString();

        $categories = Post::query()
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->all();

        if ($category && ! in_array($category, $categories, true)) {
            $category = '';
        }

        $posts = Post::query()
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->when($category, fn ($query) => $query->where('category', $category))
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(9)
            ->withQueryString();

        return view('novedades.index', compact('posts', 'categories', 'category'));
    }

    public function show(string $slug)
    {
        $post = Post::query()
            ->with(['comments' => fn ($query) => $query->where('status', 'approved')->latest()])
            ->where('slug', $slug)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        return view('novedades.show', compact('post'));
    }

    public function comentar(Request $request, string $slug)
    {
        $post = Post::query()
            ->where('slug', $slug)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:160'],
            'content' => ['required', 'string', 'min:8', 'max:1500'],
        ]);

        if (auth()->check()) {
            $data['name'] = auth()->user()->name;
            $data['email'] = auth()->user()->email;
        }

        $data['post_id'] = $post->id;
        $data['user_id'] = auth()->id();
        $data['status'] = 'pending';

        PostComment::create($data);

        return back()->with('status', 'Tu comentario fue enviado y quedará visible cuando sea aprobado.');
    }
}
