@extends('admin._layouts.master')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    <h1>Danh sách người dùng</h1>
    <form method="GET" action="{{ route('admin.users.index') }}" style="margin-bottom: 20px;">
        <input type="text" style="width: 50%;" name="keyword" placeholder="Tìm theo tên, username hoặc email" value="{{ request('keyword') }}">
        <button type="submit">Tìm</button>
    </form>
    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>ID</th>
                <th>Họ và tên</th>
                <th>Tên đăng nhập</th>
                <th>Email</th>
                <th>Quyền</th>
                <th>Trạng thái</th>
                <th colspan="3">Thao tác</th>
            </tr>
        </thead>
        <tbody>
@foreach($users as $user)
    @php
        $isCurrentUser = Auth::id() === $user->id;
    @endphp
    <tr>
        <td>{{ $user->id }}</td>
        <td>{{ $user->fullname }}</td>
        <td>{{ $user->username }}</td>
        <td>{{ $user->email }}</td>
        <td>
            @if($user->account_type == 1)
                <span style="color: blue;">Admin</span>
            @else
                <span style="color: green;">Người dùng</span>
            @endif
        </td>
        <td>
            @if($user->status == 1)
                <span style="color: green;">Hoạt động</span>
            @else
                <span style="color: red;">Đã khóa</span>
            @endif
        </td>
        <td><a href="{{ route('admin.users.show', $user->id) }}"><i class="fas fa-eye" style="color: #007bff; font-size: 18px;"></i></a></td>
        <td><a href="{{ route('admin.users.edit', $user->id) }}"><i class="fas fa-edit" style="color: #007bff; font-size: 18px;"></i></a></td>

        <td>
            @if ($isCurrentUser)
            <button disabled>
        <i class="fas {{ $user->status == 1 ? 'fa-lock' : 'fa-unlock' }}"
        style="color: {{ $user->status == 1 ? '#cc0000' : '#009900' }}; font-size: 18px;">
        </i>
        </button>

            @else
                <form method="POST" action="{{ route('admin.users.updatestatus', $user->id) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="{{ $user->status == 1 ? 0 : 1 }}">
                    <button type="submit">
                    <i class="fas {{ $user->status == 1 ? 'fa-lock' : 'fa-unlock' }}" style="color: {{ $user->status == 1 ? '#cc0000' : '#009900' }}; font-size: 18px;"></i>
                    </button>
                </form>
            @endif
        </td>
    </tr>
@endforeach
</tbody>

    </table>

    {{ $users->links() }}
@endsection
