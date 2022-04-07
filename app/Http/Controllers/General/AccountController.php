<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Repositories\UsersHistories\UserHistory;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        return view('account.index',['user' => $this->currentUser()]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'password' => 'nullable|string|min:6',
        ]);

        if ($request->filled('password')) {
            $data = $request->merge([
                        'password_plain' => $request->password,
                        'password'       => bcrypt($request->password)
                    ])->all();
        } else {
            $data = $request->except('password');
        }

        $user = $this->currentUser();
        $user->update($data);

        _addHistory(UserHistory::UPDATED,"Actualizó la cuenta");

        return back()->with('success', 'Información actualizada');
    }
}
