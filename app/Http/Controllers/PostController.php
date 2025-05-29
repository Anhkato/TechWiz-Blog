<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostController extends Controller
{
    use AuthorizesRequests;

    /**
     * Constructor để áp dụng middleware.
     */
    public function __construct()
    {
       
        $this->middleware(['auth', 'verified'])->except(['index', 'show']);
    }

   
    public function index(Request $request)
    {
        $query = Post::where('status', 'published')->with('user')->orderByDesc('created_at');

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('content', 'like', "%{$searchTerm}%");
            });
        }

        $posts = $query->paginate(10); 
        return view('posts.index', compact('posts'));
    }


    public function dashboard(Request $request)
    {
        $user = Auth::user();

        if ($user->email === 'admin@gmail.com') {
            $query = Post::query()->with('user')->orderByDesc('created_at');
        } else {
            $query = $user->posts()->orderByDesc('created_at');
        }

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('content', 'like', "%{$searchTerm}%");
                if (Auth::user()->email === 'admin@gmail.com') {
                    $q->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                        $userQuery->where('name', 'like', "%{$searchTerm}%");
                    });
                }
            });
        }

        if ($request->filled('status_filter') && in_array($request->input('status_filter'), ['draft', 'published'])) {
            $query->where('status', $request->input('status_filter'));
        }

        $posts = $query->paginate(10);

        return view('posts.dashboard', compact('posts'));
    }

      public function create()
    {
        $this->authorize('create', Post::class);
        return view('posts.create');
    }

    
    public function store(StorePostRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = Auth::id();

      
        $post = Post::create($validatedData);

        return redirect()->route('dashboard')->with('success', 'Post created successfully!');
    }

  
    public function show(Post $post) 
    {
      if ($post->status === 'draft' && (!Auth::check() || (Auth::id() !== $post->user_id && Auth::user()->email !== 'admin@gmail.com'))) {
    abort(404);
    }


        $post->load(['user', 'comments' => function ($query) {
            $query->with('user')->latest(); 
        }]);
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post); 
        return view('posts.edit', compact('post'));
    }

       public function update(UpdatePostRequest $request, Post $post)
    {
        $this->authorize('update', $post); 

        $validatedData = $request->validated();

        
        $post->update($validatedData);

        return redirect()->route('dashboard')->with('success', 'Post updated successfully!');
    }

  
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post); 
        $post->delete();
        return redirect()->route('dashboard')->with('success', 'Post deleted successfully!');
    }
}