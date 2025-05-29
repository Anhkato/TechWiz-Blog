<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechWiz Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
        }
        .post-card {
            background-color: #fff;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            transition: 0.3s ease-in-out;
        }
        .post-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        .post-header {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .post-meta {
            font-size: 0.875rem;
            color: #606770;
        }
    </style>
</head>
<body>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 mt-4">

                <h1 class="text-center mb-4 display-5 fw-bold">TechWiz Blog</h1>

                <form method="GET" action="{{ route('posts.index') }}" class="mb-4">
                    <div class="input-group rounded-pill shadow-sm bg-white px-3 py-2">
                        <input type="text" name="search" class="form-control border-0 bg-transparent" placeholder="Search posts..." value="{{ request('search') }}">
                        <button class="btn btn-link text-primary fw-bold" type="submit">Search</button>
                    </div>
                </form>

                @if($posts->isEmpty())
                    <div class="alert alert-info text-center">
                        @if(request('search'))
                            No posts found for "{{ request('search') }}".
                            <a href="{{ route('posts.index') }}" class="text-decoration-underline">Clear search</a>
                        @else
                            No posts to display yet.
                        @endif
                    </div>
                @else
                    @foreach ($posts as $post)
                        <div class="post-card">
                            <div class="post-header mb-2">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name) }}" alt="Avatar" class="avatar">
                                <div>
                                    <strong>{{ $post->user->name }}</strong><br>
                                    <span class="post-meta">{{ $post->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                            <div class="post-body">
                                <p class="mb-2 fw-bold fs-5">
                                    <a href="{{ route('posts.show', $post) }}" class="text-dark text-decoration-none">{{ $post->title }}</a>
                                </p>
                                <p class="text-muted">{{ Str::limit(strip_tags($post->content), 150) }}</p>
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-outline-primary">Read More</a>
                            </div>
                        </div>
                    @endforeach

                    <div class="d-flex justify-content-center mt-4">
                        {{ $posts->links() }}
                    </div>
                @endif

                <footer class="text-center mt-5 mb-4 text-muted small">
                    &copy; {{ date('Y') }} TechWiz Blog
                </footer>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>