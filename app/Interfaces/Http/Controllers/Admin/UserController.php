<?php

namespace App\Interfaces\Http\Controllers\Admin;
use App\Interfaces\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Profile\UpdateRequest;
use App\Core\Application\Services\UserService;
use Illuminate\Http\Request;
use Telegram\Bot\Objects\Update;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $keyword = $request->get('keyword');
        $users = $this->userService->searchPaginate($keyword, 10);
    
        $page_title = 'Danh sách người dùng';
        $page_breadcrumbs = [
            [
                'page' => route('admin.dashboard'),
                'title' => 'Trang chủ',
            ],
            [
                'page' => route('admin.users.index'),
                'title' => 'Người dùng',
            ],
        ];
    
        return view('admin.user.index', compact('users', 'page_title', 'page_breadcrumbs'));
    }
    public function show($id)
    {
        $user = $this->userService->getUserById($id);
        if (!$user) {
            return redirect()->route('admin.users.index')->withErrors('Người dùng không tồn tại.');
        }

        $page_title = 'Chi tiết người dùng';
        $page_breadcrumbs = [
            [
                'page' => route('admin.dashboard'),
                'title' => 'Trang chủ',
            ],
            [
                'page' => route('admin.users.index'),
                'title' => 'Người dùng',
            ],
            [
                'page' => route('admin.users.show', $id),
                'title' => 'Chi tiết',
            ],
        ];

        return view('admin.user.show', compact('user', 'page_title', 'page_breadcrumbs'));
    }
    public function edit($id)
    {
        $user = $this->userService->getUserById($id);
        if (!$user) {
            return redirect()->route('admin.users.index')->withErrors('User không tồn tại');
        }
    
        $page_title = 'Chỉnh sửa người dùng';
        $page_breadcrumbs = [
            [
                'page' => route('admin.dashboard'),
                'title' => 'Trang chủ',
            ],
            [
                'page' => route('admin.users.index'),
                'title' => 'Người dùng',
            ],
            [
                'page' => route('admin.users.edit', $id),
                'title' => 'Chỉnh sửa',
            ],
        ];
    
        return view('admin.user.edit', compact('user', 'page_title', 'page_breadcrumbs'));
    }
    
    
    public function update(UpdateRequest $request, $id)
    {
        $data = $request->only(['username', 'fullname', 'email']);
        $this->userService->updateUser($id, $data);
        return redirect()->route('admin.users.index')->with('success', 'Cập nhật thành công!');
    }
    public function updatestatus(Request $request, $id)
    {
        $this->userService->updateUser($id, $request->only(['status']));
    
        return redirect()->route('admin.users.index')->with('success', 'Cập nhật thành công!');
    }
    public function updateRole(Request $request, $id)
    {
        $this->userService->updateUser($id, $request->only(['account_type']));
    
        return redirect()->route('admin.users.index')->with('success', 'Cập nhật thành công!');
    }
}
