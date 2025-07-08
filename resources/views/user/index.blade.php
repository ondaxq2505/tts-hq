<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Trang chủ</title>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100">
  
  <div x-data="{ open: false }" class="fixed top-4 right-4 z-50">
  @if ($user->avatar)
    <img src="{{ asset('storage/' . $user->avatar) }}" class="w-12 h-12 rounded-full cursor-pointer border-2 border-gray-300" 
    @click="open = !open">
    @else
    <img 
      src="https://www.bing.com/th/id/OIP.kQyrx9VbuWXWxCVxoreXOgHaHN?w=211&h=206&c=8&rs=1&qlt=90&o=6&dpr=1.3&pid=3.1&rm=2" 
      class="w-12 h-12 rounded-full cursor-pointer border-2 border-gray-300" 
      @click="open = !open"
    >
    @endif
    

    <div 
      x-show="open" 
      @click.outside="open = false"
      x-transition
      class="mt-2 w-72 bg-white rounded-xl shadow-xl p-4"
    >
      <h2 class="text-lg font-semibold">Thông tin cá nhân</h2>
      <div class="mt-2 text-sm text-gray-700">
      <a href="{{ route('user.profile') }}">Thông tin cá nhân</a>
      </div>
      
      <div class="mt-2 text-sm text-gray-700">
      <a href="{{ route('user.change-password') }}">Đổi mật khẩu</a>
      </div>
        <form method="POST" action="{{ route('user.logout') }}">
        @csrf
        <button type="submit" class="text-red-600 hover:underline">Đăng xuất</button>
         </form>
      <button 
        class="mt-4 w-full bg-blue-500 text-white py-1.5 rounded hover:bg-blue-600"
        @click="open = false"
      >
        Đóng
      </button>
    </div>
  </div>
</body>
</html>
