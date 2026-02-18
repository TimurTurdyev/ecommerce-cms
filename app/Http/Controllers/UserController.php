<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Tables\Renderers\TableRenderer;
use App\Tables\UsersTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $table = new UsersTable($request);
        $tableRenderer = new TableRenderer($table, $request);

        if ($request->ajax()) {
            return $tableRenderer->render();
        }

        return view('user.index', compact('tableRenderer'));
    }


    public function createOrEdit(User $user, Request $request)
    {
        $roles = User::ROLES;

        return view('user.create', compact('user', 'roles'));
    }

    public function delete()
    {

    }

    public function store(UserRequest $request)
    {
        $requestData = $request->validated();

        if (empty($requestData['password'])) {
            $requestData['password'] = Hash::make(Str::random(10));
        }

        $user = User::query()->create($requestData);

        return redirect()->route('user.edit', $user);
    }

    public function update(User $user, UserRequest $request)
    {
        $requestData = $request->validated();

        $user = $user->update($requestData);

        return redirect()->route('user.edit', $user);
    }
}
