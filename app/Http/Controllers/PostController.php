<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Carbon\Carbon;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $posts = Post::query()
            ->with('user')
            ->isNotDraft()
            ->published()
            ->latest()
            ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        //
        $validatedData = $request->validated();

        try {
            $validatedData['is_draft'] = @$validatedData['is_draft'] == 'on'
                ? true
                : false;
            $validatedData['publish_date'] = @$validatedData['publish_date']
                ? Carbon::parse($validatedData['publish_date'])->format('Y-m-d')
                : null;
            $validatedData['user_id'] = Auth::id();
            $validatedData['content'] = Str::trim($validatedData['content']);

            Post::create($validatedData);

            return to_route('home')->with('success', 'Post created successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
        if ($post->is_draft) abort(404);

        $post->load('user');

        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        //
        $validatedData = $request->validated();
        $post->fill($validatedData)->save();

        return redirect()->back()->with('success', 'Post updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
        $post->delete();

        return redirect()->back()->with('success', 'Post deleted successfully');
    }
}
