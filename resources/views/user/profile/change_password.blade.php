<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi mật khẩu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
@include('user.index')
    <div class="container mx-auto max-w-lg mt-10">
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
            <h2 class="text-2xl font-bold text-center mb-6">Đổi mật khẩu</h2>

            {{-- Hiển thị lỗi --}}
            @if ($errors->any())
                <div class="mb-4 bg-red-100 text-red-700 p-3 rounded">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Thông báo thành công --}}
            @if (session('success'))
                <div class="mb-4 bg-green-100 text-green-700 p-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('user.change-password') }}">
                @csrf

                <div class="mb-4">
                    <label class="block mb-1 font-medium text-gray-700">Mật khẩu hiện tại</label>
                    <input type="password" name="old_password" placeholder="Nhập mật khẩu cũ"
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                    @error('old_password')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium text-gray-700">Mật khẩu mới</label>
                    <input type="password" name="password" placeholder="Nhập mật khẩu mới"
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                    @error('password')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium text-gray-700">Xác nhận mật khẩu mới</label>
                    <input type="password" name="password_confirmation" placeholder="Xác nhận mật khẩu mới"
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                    @error('password_confirmation')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition duration-200">
                    Đổi mật khẩu
                </button>
            </form>
        </div>
    </div>

</body>
</html>
