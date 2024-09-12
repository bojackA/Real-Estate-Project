<!-- resources/views/search.blade.php -->

<h2>Search for Properties:</h2>

<form method="get" action="{{ route('search') }}">
    <!-- Add input fields for various search criteria -->
    <input type="text" name="price" placeholder="Price">
    <input type="text" name="location" placeholder="Location">
    <input type="text" name="type" placeholder="Type">
    <input type="text" name="size" placeholder="Size">
    <input type="text" name="rooms" placeholder="Number of Rooms">

    <button type="submit">Search</button>
</form>

<!-- Display Search Results -->
@if ($results && $results->count() > 0)
    <h3>Search Results:</h3>
    <ul>
        @foreach ($results as $result)
            @if ($result->permission == 1)
                <li>
                    <strong>{{ $result->title }}</strong><br>
                    {{ $result->body }}
                    <p>Price: {{ $result->price }}</p>
                    <p>Location: {{ $result->location }}</p>
                    <p>Type: {{ $result->type }}</p>
                    <p>Size: {{ $result->size }}</p>
                    <p>Number of Rooms: {{ $result->rooms }}</p>

                    <!-- Display Images -->
                    @if($result->images)
                        <ul>
                            @foreach($result->images as $image)
                                <li>
                                    <img src="{{ asset($image) }}" alt="Post Image">
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endif
        @endforeach
    </ul>
@else
    <p>No matching posts found.</p>
@endif
