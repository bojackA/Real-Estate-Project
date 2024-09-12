<!-- resources/views/user/posts.blade.php -->

<h2>Approved Posts</h2>
@if ($approvedPosts->count() > 0)
    <ul>
        @foreach ($approvedPosts as $post)
            <li>
                <strong>{{ $post->title }}</strong><br>
                {{ $post->body }}
            </li>
        @endforeach
    </ul>
@else
    <p>No approved posts found.</p>
@endif
