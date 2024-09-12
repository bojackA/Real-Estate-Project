<table>
    <thead>
        <tr>
            
            <th>Name</th>
           
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            @if ($user->role == 0)
                <tr>
                    
                    <td><a href="{{ route('otherProfile', $user->id) }}">{{ $user->name }}</a></td>
                    
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
