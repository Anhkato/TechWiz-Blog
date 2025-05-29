<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Posts Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a href="{{ route('posts.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
                        Create New Post
                    </a>

                    {{-- Search Form --}}
                    <form method="GET" action="{{ route('dashboard') }}" class="mb-4">
                        <input type="text" name="search" placeholder="Search my posts..." value="{{ request('search') }}" class="border p-2 rounded">
                        <select name="status_filter" class="border p-2 rounded">
                            <option value="">All Statuses</option>
                            <option value="draft" {{ request('status_filter') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ request('status_filter') == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        <button type="submit" class="bg-blue-500 text-white px-3 py-2 rounded">Filter</button>
                    </form>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if($posts->isEmpty())
                        <p>You haven't created any posts yet.</p>
                    @else
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($posts as $post)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $post->title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($post->status) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('posts.show', $post) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">View</a>
                                        @can('update', $post)
                                        <a href="{{ route('posts.edit', $post) }}" class="text-yellow-600 hover:text-yellow-900 mr-2">Edit</a>
                                        @endcan
                                        @can('delete', $post)
                                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $posts->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>