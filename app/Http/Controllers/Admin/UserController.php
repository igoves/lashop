<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\View\View;

class UserController extends AdminController
{
    public function index(): View
    {
        $query = User::withCount('orders')->orderByDesc('id');

        if ($search = request('search')) {
            $like = '%'.addcslashes($search, '%_\\').'%';
            $query->where(function ($q) use ($like) {
                $q->where('name', 'like', $like)
                  ->orWhere('email', 'like', $like);
            });
        }

        $users = $query->paginate(20)->appends(request()->only('search'));

        return view('admin.users.index', compact('users'));
    }
}
