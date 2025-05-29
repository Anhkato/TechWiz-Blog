<x-guest-layout>
<a href="{{ route('dashboard') }}"> quay lại trang đăng bài</a>

    <div class="py-12 bg-gray-100 dark:bg-gray-900">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        {{ $post->title }}
                    </h1>

                    <div class="mb-6 text-sm text-gray-500 dark:text-gray-400">
                        <span>{{ __('Published on') }} {{ $post->created_at->format('F j, Y, g:i a') }}</span>
                        @if($post->user)
                            <span>{{ __('by') }} 
                                <a href="#" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                    {{ $post->user->name }}
                                </a>
                            </span>
                        @endif
                        @if($post->created_at->ne($post->updated_at) && $post->updated_at->diffInSeconds($post->created_at) > 60)
                            <span class="italic ml-2">
                                ({{ __('Last updated') }} {{ $post->updated_at->format('F j, Y, g:i a') }})
                            </span>
                        @endif
                    </div>

                    <div class="prose prose-lg dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 leading-relaxed">
                        {!! nl2br(e($post->content)) !!}
                    </div>

                    @auth
                        @can('update', $post)
                            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <a href="{{ route('posts.edit', $post) }}"
                                   class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:border-yellow-700 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    {{ __('Edit Post') }}
                                </a>
                            </div>
                        @endcan
                    @endauth

                    <div class="mt-10 pt-8 border-t border-gray-200 dark:border-gray-700">
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">{{ __('Comments') }}</h2>

                        @if($post->comments->isNotEmpty())
                            <div class="mt-6 space-y-4">
                                @foreach($post->comments()->latest()->get() as $comment)
                                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                                        <div class="flex items-center justify-between mb-2">
                                            <div>
                                                <p class="font-semibold text-gray-800 dark:text-white">{{ $comment->user->name }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $comment->created_at->diffForHumans() }}</p>
                                            </div>

                                            @auth
                                                @if(Auth::id() === $comment->user_id || Auth::user()->email === 'admin@gmail.com')
                                                   <form action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bình luận này không?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="text-red-600 dark:text-red-400 text-sm hover:underline">
                                                            {{ __('Delete') }}
                                                        </button>
                                                    </form>
                                                @endif
                                            @endauth
                                        </div>
                                        <p class="text-gray-700 dark:text-gray-300">{{ $comment->body }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                                <p class="text-gray-600 dark:text-gray-400">{{ __('No comments yet.') }}</p>
                            </div>
                        @endif

                        {{-- Comment form --}}
                        @auth
                            <form action="{{ route('comments.store', $post) }}" method="POST" class="mt-6">
                                @csrf
                                <div class="mb-4">
                                    <label for="comment_body" class="sr-only">{{ __('Your Comment') }}</label>
                                    <textarea name="body" id="comment_body" rows="3"
                                              class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:text-gray-200 sm:text-sm @error('body', 'comment') border-red-500 @enderror"
                                              placeholder="{{ __('Write a comment...') }}" required></textarea>
                                </div>
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    {{ __('Post Comment') }}
                                </button>
                            </form>
                        @else
                            <p class="mt-6 text-gray-600 dark:text-gray-400">
                                <a href="{{ route('login') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ __('Log in') }}</a>
                                {{ __('or') }}
                                <a href="{{ route('register') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ __('register') }}</a>
                                {{ __('to post a comment.') }}
                            </p>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
