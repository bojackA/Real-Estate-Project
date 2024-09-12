<head></head>
<body>
    <h2>All Posts:</h2>

    @if ($allPosts->count() > 0)
        <ul>
            @foreach ($allPosts as $post)
            @if($post->permission == 0)
                <li>
                    <strong>{{ $post->title }}</strong><br>
                    {{ $post->body }}
                    
                    @if($post->user)
                        <p>Posted by: {{ $post->user->name }} (User ID: {{ $post->user->id }})</p>
                    @endif

                    <!-- Display Images -->
                    @if(!empty($post->images))
                        <ul>
                            @foreach($post->images as $image)
                                <li>
                                    @if ($post->image)
                                    <img src="{{ asset('/storage/app/public/post_images/' .$post->image) }}"
                                    width="80px" heigh="90px" alt="Post Image">
            
                                @endif
                                
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    
                @endif

                    @if(auth()->user()->role == 1 && $post->permission == 0)
                        <form action="{{ route('accept.post', $post->id) }}" method="post">
                            @csrf
                            @method('PATCH')
                            <button type="submit">Accept</button>
                        </form>

                        <form action="{{ route('delete.post', $post->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    @endif
                </li>
            @endforeach
        </ul>
    @else
        <p>No posts found.</p>
    @endif
</body>
