<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    <x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-5 text-dark">
            Bảng điều khiển Bài viết của tôi
        </h2>
        
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body">
                    <a href="{{ route('posts.create') }}" class="btn btn-success mb-4">
                        Tạo bài viết mới
                    </a>
                     <a href="{{ route('posts.index') }}" class="btn btn-success mb-4">
                        về lại trang bài đăng
                    </a>

                    {{-- Search Form --}}
                    <form method="GET" action="{{ route('dashboard') }}" class="mb-4 row gx-2 gy-2 align-items-center">
                        <div class="col-sm-auto flex-grow-1">
                            <input type="text" name="search" placeholder="Tìm bài viết..." value="{{ request('search') }}" class="form-control">
                        </div>
                        <div class="col-sm-auto">
                            <select name="status_filter" class="form-select">
                                <option value="">Tất cả trạng thái</option>
                                <option value="draft" {{ request('status_filter') == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                                <option value="published" {{ request('status_filter') == 'published' ? 'selected' : '' }}>Đã xuất bản</option>
                            </select>
                        </div>
                        <div class="col-sm-auto">
                            <button type="submit" class="btn btn-primary w-100">Lọc</button>
                        </div>
                    </form>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                        </div>
                    @endif

                    @if($posts->isEmpty())
                        <p class="text-muted">Bạn chưa tạo bài viết nào.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="text-uppercase small">Tiêu đề</th>
                                        <th scope="col" class="text-uppercase small">Trạng thái</th>
                                        <th scope="col" class="text-uppercase small text-end">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($posts as $post)
                                    <tr>
                                        <td>{{ $post->title }}</td>
                                        <td>{{ ucfirst($post->status === 'draft' ? 'Bản nháp' : 'Đã xuất bản') }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-info me-1">Xem</a>
                                            @can('update', $post)
                                            <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-warning me-1">Sửa</a>
                                            @endcan
                                            @can('delete', $post)
                                            <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này không?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                            </form>
                                            @endcan
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 d-flex justify-content-center">
                            {{ $posts->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>