<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserStepProgress;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function progressList()
    {
        $users = User::all();
        $progress = [];

        foreach ($users as $user) {
            $userProgress = UserStepProgress::with('step')
                ->where('user_id', $user->id)
                ->get();

            $progress[] = [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->role,
                'progress' => $userProgress->map->only(['step_id', 'completed_at', 'progress_percent', 'step']),
                ];
        }

        return view('admin.progress', compact('progress'));
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }


    public function updateRole(Request $request, $userId)
    {
        $request->validate([
            'role' => 'required|in:user,admin',
        ]);

        $user = User::findOrFail($userId);
        $user->role = $request->role;
        $user->save();

        return redirect()->route('admin.progress')->with('success', '用户角色更新成功');
    }
}
