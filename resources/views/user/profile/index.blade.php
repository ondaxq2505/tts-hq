<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông tin cá nhân</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" >
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .avatar-container {
            position: relative;
            display: inline-block;
        }
        .avatar-edit-icon {
            position: absolute;
            top: -8px;
            right: -8px;
            cursor: pointer;
            color: #007bff;
            font-size: 14px;
            background-color: white;
            border-radius: 50%;
            padding: 5px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body class="bg-gray-100">
@include('user.index')
<div class="container py-5">
    <h1 class="text-2xl font-bold mb-4" style="text-align: center;">Thông tin cá nhân</h1>
    @if (isset($user))
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="d-flex justify-content-center">
        <div class="avatar-container">
            @if ($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="rounded mb-2" style="width: 100px; height: 100px;">
            @else
                <img src="https://www.bing.com/th/id/OIP.kQyrx9VbuWXWxCVxoreXOgHaHN?w=211&h=206&c=8&rs=1&qlt=90&o=6&dpr=1.3&pid=3.1&rm=2" alt="Default Avatar" class="rounded mb-2" style="width: 100px; height: 100px;">
            @endif
            <form action="{{ route('user.updateAvatar', $user->id) }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center ms-2">
                @csrf
                @method('POST')
                <label for="avatar-upload" class="avatar-edit-icon">
                    <i class="fas fa-edit"></i>
                </label>
                <input type="file" name="avatar" id="avatar-upload" style="display: none;" onchange="this.form.submit()">
            </form>
        </div>
    </div>

    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <strong>ID:</strong>
                <p>{{ $user->id }}</p>
            </div>
            <div>
                <strong>Họ và tên:</strong>
                <p>{{ $user->fullname }}</p>
            </div>
            <div>
                <strong>Tên đăng nhập:</strong>
                <p>{{ $user->username }}</p>
            </div>
            <div>
                <strong>Email:</strong>
                <p>{{ $user->email }}</p>
            </div>
            <div>
                <strong>Quyền:</strong>
                <p>
                    @if($user->account_type == 1)
                        <span class="badge bg-primary">Admin</span>
                    @else
                        <span class="badge bg-success">Người dùng</span>
                    @endif
                </p>
            </div>
            <div>
                <strong>Trạng thái:</strong>
                <p>
                    @if($user->status == 1)
                        <span class="badge bg-success">Hoạt động</span>
                    @else
                        <span class="badge bg-danger">Đã khóa</span>
                    @endif
                </p>
            </div>
            <div class="md:col-span-2 mt-4">
                <a href="{{ route('user.edit', $user->id) }}" class="btn btn-warning">Chỉnh sửa thông tin</a>
            </div>
        </div>
    </div>
</div>
@else
        <p>Không tìm thấy thông tin người dùng.</p>
    @endif
</body>
</html>
