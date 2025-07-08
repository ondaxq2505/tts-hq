@extends('admin._layouts.master')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Chi tiết người dùng</h1>

    <div class="card shadow p-4">
        <div class="d-flex align-items-center mb-4">
            <div style="position: relative; display: inline-block;">
                @if ($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="rounded" style="width: 100px; height: 100px;">
                @else
                    <img src="https://www.bing.com/th/id/OIP.kQyrx9VbuWXWxCVxoreXOgHaHN?w=211&h=206&c=8&rs=1&qlt=90&o=6&dpr=1.3&pid=3.1&rm=2" alt="Default Avatar" class="rounded" style="width: 100px; height: 100px;">
                @endif
                <form action="{{ route('user.updateAvatar', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <label for="avatar-upload" style="position: absolute; top: -8px; right: -8px; background: white; border-radius: 50%; padding: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.2); cursor: pointer;">
                        <i class="fas fa-edit text-primary"></i>
                    </label>
                    <input type="file" name="avatar" id="avatar-upload" style="display: none;" onchange="this.form.submit()">
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label><strong>Họ và tên:</strong></label>
                <div>{{ $user->fullname }}</div>
            </div>
            <div class="col-md-6 mb-3">
                <label><strong>Tên đăng nhập:</strong></label>
                <div>{{ $user->username }}</div>
            </div>
            <div class="col-md-6 mb-3">
                <label><strong>Email:</strong></label>
                <div>{{ $user->email }}</div>
            </div>
            <div class="col-md-6 mb-3">
                <label><strong>Quyền:</strong></label>
                <div class="text-{{ $user->account_type == 1 ? 'primary' : 'success' }}">
                    {{ $user->account_type == 1 ? 'Admin' : 'Người dùng' }}
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <label><strong>Trạng thái:</strong></label>
                <div class="text-{{ $user->status == 1 ? 'success' : 'danger' }}">
                    {{ $user->status == 1 ? 'Hoạt động' : 'Đã khóa' }}
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Chỉnh sửa
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                Quay lại danh sách
            </a>
        </div>
    </div>
</div>
@endsection
