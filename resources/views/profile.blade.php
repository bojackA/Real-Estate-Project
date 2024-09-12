<head></head>
<body>
    <h2>User Profile</h2>
    <p>Name: {{ $user->name }}</p>
    <p>User ID: {{ $user->id }}</p>
    <h3>Your Posts:</h3>
    @if ($userPosts->count() > 0)
        <ul>
            @foreach ($userPosts as $post)
                @if($post->permission == 1)
                <li>
                    <strong>{{ $post->title }}</strong><br>
                    {{ $post->body }}
                </li>
                @endif
            @endforeach
        </ul>
    @else
        <p>No posts found.</p>
    @endif
</body>
