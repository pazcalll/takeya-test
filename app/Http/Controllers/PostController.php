<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Carbon\Carbon;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth', only: ['create', 'store', 'edit', 'update'])
        ];
    }
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
        return view('posts.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
        return view('posts.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
