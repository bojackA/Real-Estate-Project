@if ($user)
<h2>User Profile</h2>
<p>Name: {{ $user->name }}</p>
<p>Phone Number: {{ $user->phone }}</p>
    <h1>{{ $user->username }}</h1>
    <p>Email: {{ $user->email }}</p>
@else
    <p>User not found.</p>
@endif
