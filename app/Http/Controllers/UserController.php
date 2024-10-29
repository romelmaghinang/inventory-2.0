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

        return response()->json(['message' => 'User created successfully', 'User' => $newUser], 201);
    }
/**
 * @OA\Post(
 *     path="/api/login",
 *     tags={"Authentication"},
 *     summary="User login",
 *     description="Logs in a user with email and password. If login is successful, an API token will be generated based on the user's permissions. The token must be used as a Bearer token in the Authorization header for authenticated API requests.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"email", "password"},
 *             @OA\Property(property="email", type="string", example="user@example.com", description="User's email address"),
 *             @OA\Property(property="password", type="string", example="password123", description="User's password")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Login successful",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="token", type="string", description="The generated API token to be used as a Bearer token for API requests")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Invalid credentials",
 *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Invalid credentials"))
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation Error",
 *         @OA\JsonContent(@OA\Property(property="message", type="object", description="Validation errors"))
 *     )
 * )
 */


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
/**
 * @OA\Post(
 *     path="/api/create-permission",
 *     tags={"User Permissions"},
 *     summary="Create a new permission",
 *     description="Creates a new permission and optionally assigns it to a role.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"name"},
 *             @OA\Property(property="name", type="string", example="edit-post", description="The name of the permission"),
 *             @OA\Property(property="role", type="string", example="admin", description="Optional role to assign the permission to")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Permission created successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="permission", type="object", description="The created permission details")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation Error",
 *         @OA\JsonContent(@OA\Property(property="message", type="object", description="Validation errors"))
 *     )
 * )
 */

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

    /**
 * @OA\Post(
 *     path="/api/create-role",
 *     tags={"User Roles"},
 *     summary="Create a new role",
 *     description="Creates a new role with optional permissions.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"name"},
 *             @OA\Property(property="name", type="string", example="admin", description="The name of the role"),
 *             @OA\Property(property="permissions", type="array", @OA\Items(type="string"), description="List of permissions to assign to the role")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Role created successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="role", type="object", description="The created role details")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation Error",
 *         @OA\JsonContent(@OA\Property(property="message", type="object", description="Validation errors"))
 *     )
 * )
 */

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
 /**
     * @OA\Post(
     *     path="/api/assign-role",
     *     tags={"User Roles"},
     *     summary="Assign a role to a user",
     *     description="Assigns a role to an existing user.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"role", "userId"},
     *             @OA\Property(property="role", type="string", example="admin", description="The name of the role"),
     *             @OA\Property(property="userId", type="integer", example=1, description="The ID of the user to assign the role to")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role assigned successfully",
     *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Role assigned successfully"))
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(@OA\Property(property="message", type="object", description="Validation errors"))
     *     )
     * )
     */

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
/**
 * @OA\Post(
 *     path="/api/assign-permission",
 *     tags={"User Permissions"},
 *     summary="Assign permissions to a role",
 *     description="Assigns one or more permissions to an existing role.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"role", "permissions"},
 *             @OA\Property(property="role", type="string", example="admin", description="The name of the role"),
 *             @OA\Property(property="permissions", type="string", example="create-post,edit-post", description="Comma-separated list of permissions")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Permissions assigned successfully",
 *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Permissions assigned successfully"))
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation Error",
 *         @OA\JsonContent(@OA\Property(property="message", type="object", description="Validation errors"))
 *     )
 * )
 */

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
    
 /**
     * @OA\Get(
     *     path="/api/permissions",
     *  *     tags={"User Permissions"},

     *     summary="Get User Permissions",
     *     @OA\Response(
     *         response=200,
     *         description="Permissions retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="permissions", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
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
