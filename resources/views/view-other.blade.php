<!-- resources/views/user/posts.blade.php -->

<h2>Other Posts</h2>
@if ($approvedPosts->count() > 0)
    <ul>
        @foreach ($approvedPosts as $post)
            <li>
                <strong>{{ $post->title }}</strong><br>
                {{ $post->body }}

                <!-- Add to Cart Button -->
                <form action="{{ route('cart.add', $post->id) }}" method="post">
                    @csrf
                    <button type="submit">Add to Cart</button>
                </form>
            </li>
        @endforeach
    </ul>
@else
    <p>No approved posts found.</p>
@endif
