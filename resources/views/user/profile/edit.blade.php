<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa người dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    @include('user.index')

    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-xl">
            <h2 class="text-2xl font-bold mb-4 text-center">Chỉnh sửa người dùng</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Lỗi!</strong> Vui lòng kiểm tra lại thông tin bên dưới:<br><br>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('user.update', $user->id) }}" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-md">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Họ và tên</label>
                    <input type="text" name="fullname" class="form-control" value="{{ old('fullname', $user->fullname) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Tên đăng nhập</label>
                    <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                </div>

                <button type="submit" class="btn btn-primary w-full">Cập nhật</button>
            </form>
        </div>
    </div>
</body>
</html>
