<!DOCTYPE html>
<html>
<head>
    <title>Admin - View Users</title>
</head>
<form action="/logoutadmin" method="POST" >
    @csrf
        <button>logout</button>
    </form>
<body>
    <a href="{{ route('new') }}">New Page</a>
    <h1>View Users</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Action</th> <!-- New column for the delete button -->
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            @if($user->role==0)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>
            @endif
                        @if(auth()->user()->role == 1)
                            <form action="{{ route('delete.user', $user->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                             
                        @else
                            <!-- You can add a message or other action for non-admin users -->
                            Not allowed
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
