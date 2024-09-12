<head></head>
<body>
@auth
<form action="/logout" method="POST" >
@csrf
    <button>logout</button>
</form>
<a href="{{ route('profile') }}">View Profile</a>
<a href="{{ route('view-feed') }}">view feed</a>
<a href="{{ route('view-other') }}">view listings</a>
<a href="{{ route('viewpeople') }}">view others</a>
<a href="{{ route('search') }}">Search</a>
<form action="/create" method="POST" enctype="multipart/form-data" >
@csrf
    <input name="title" type="text"  placeholder="post title">
    <textarea name = "body" type ="text" placeholder="body" ></textarea>
    <textarea name = "bathrooms" type ="text" placeholder="bathrooms" ></textarea>
    <textarea name = "rooms" type ="text" placeholder="rooms" ></textarea>
    <textarea name = "location" type ="text" placeholder="location" ></textarea>
    <textarea name = "address" type ="text" placeholder="address" ></textarea>
    <textarea name = "size" type ="text" placeholder="size" ></textarea>
    <textarea name = "price" type ="text" placeholder="price" ></textarea>
    <br>    
    <label for="rental">Rental</label>
<input type="radio" id="rental" name="type" value="rental">

<label for="for-sale">For Sale</label>
<input type="radio" id="for-sale" name="type" value="for sale">

    <label for="images">Images:</label>
    <input type="file" name="images[]" id="images" accept="image/*" multiple>

    <button>save post</button>
</form>

@else

<form action="/register" method = "POST">
@csrf

    <input name="name" type="text" placeholder="name">
    <input name="email" type="text" placeholder="email">
    <input name="phone" type = "text" placeholder = "phone">
    <input name="password" type="text" placeholder="password">
    <button> Register </button>
</form> 

<form method="POST" action="/login">
    @csrf
    <input name="loginname" type="text" placeholder="name">
    <input name="loginpass" type="text" placeholder="password">
    <button>Log in </button>
</form>
<a href="{{ route('view-users') }}">View Users</a><br>
<a href="{{ route('new') }}">Go to New View</a>


</body>
@endauth