<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function createUser(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => $e->errors()], 422);
        }

        $newUser = (new \App\Models\User)->createUser($request);
        $newUser->assignRole('user');

        $token = $newUser->createToken('API Token')->plainTextToken;

        return response()->json(['message' => 'User created successfully', 'User' => $newUser, 'token' => $token], 201);
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => $e->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $permissions = $user->getAllPermissions()->pluck('name');
        $token = $user->createToken('API Token', $permissions->toArray())->plainTextToken;

        return response()->json(['token' => $token]);
    }

    public function createPermission(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:permissions',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => $e->errors()], 422);
        }

        $permission = Permission::create(['name' => $request->name]);

        if ($request->role) {
            $permission->assignRole($request->role);
        }

        return response()->json(['permission' => $permission], 201);
    }

    public function createRole(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:roles'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => $e->errors()], 422);
        }

        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return response()->json(['role' => $role], 201);
    }

    public function assignRole(Request $request)
    {
        try {
            $request->validate([
                'role' => 'required|string|exists:roles,name',
                'userId' => 'required|int|exists:users,id',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => $e->errors()], 422);
        }

        $role = Role::where('name', $request->role)->first();
        $user = User::find($request->userId);
        $user->assignRole($role);

        return response()->json(['message' => 'Role assigned successfully']);
    }

    public function assignPermission(Request $request)
    {
        try {
            $request->validate([
                'permissions' => 'array'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => $e->errors()], 422);
        }

        $role = Role::findByName($request->role);
        $permissions = explode(',', $request->permissions);

        foreach ($permissions as $permission) {
            $role->givePermissionTo($permission);
        }

        return response()->json(['message' => 'Permissions assigned successfully'], 200);
    }

    public function getUserPermissions(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $permissions = $user->getAllPermissions()->pluck('name');

        return response()->json(['permissions' => $permissions]);
    }
}
