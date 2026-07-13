<?php

namespace App\Http\Controllers;

use App\Services\User\UserService;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {
    }

    public function index(Request $request)
    {
        if (!$request->user()?->can('user.list')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to view the user list.',
            ], 403);
        }

        $users = $this->userService->getUserList($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Users retrieved successfully.',
            'data' => $users,
        ], 200);
    }

    public function store(RegisterRequest $request)
    {
        if (!$request->user()?->can('user.create')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to create a new user.',
            ], 403);
        }

        $user = $this->userService->createUser($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'User created successfully.',
            'data' => $user,
        ], 201);
    }

    public function show(Request $request, $id)
    {
        if (!$request->user()?->can('user.view')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to view this user.',
            ], 403);
        }

        $user = $this->userService->getUser($id);

        return response()->json([
            'success' => true,
            'message' => 'User retrieved successfully.',
            'data' => $user,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        if (!$request->user()?->can('user.edit')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to update this user.',
            ], 403);
        }

        $user = $this->userService->updateUser($id, $request->all());

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully.',
            'data' => $user,
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        if (!$request->user()?->can('user.delete')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to delete this user.',
            ], 403);
        }

        $this->userService->deleteUser($id);

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully.',
        ], 200);
    }
}
