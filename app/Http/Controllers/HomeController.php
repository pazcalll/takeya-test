<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //
    public function index()
    {
        if (!Auth::check()) return view('home');

        $posts = Post::query()
            ->with('user')
            ->whereHas('user', fn ($query) => $query->where('id', Auth::id()))
            ->latest()
            ->paginate(10);

        return view('home', compact('posts'));
    }
}
