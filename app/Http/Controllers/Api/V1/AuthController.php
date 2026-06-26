<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * POST /api/v1/auth/login
     *
     * Issues a Sanctum personal access token for the student.
     * Scoped to 'student:read' and 'student:write' abilities.
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'       => ['required', 'email'],
            'password'    => ['required', 'string'],
            'device_name' => ['required', 'string', 'max:255'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Only students may access the mobile API
        if (! $user->isStudent()) {
            return response()->json([
                'message' => 'Access denied. This API is for students only.',
            ], 403);
        }

        // Revoke any previous tokens for this device name to avoid sprawl
        $user->tokens()->where('name', $request->device_name)->delete();

        $token = $user->createToken(
            $request->device_name,
            ['student:read', 'student:write']
        )->plainTextToken;

        return response()->json([
            'token'      => $token,
            'token_type' => 'Bearer',
            'user'       => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
            'student' => $user->student ? [
                'id'             => $user->student->id,
                'student_number' => $user->student->student_number,
                'full_name'      => $user->student->full_name,
                'status'         => $user->student->status,
            ] : null,
        ]);
    }

    /**
     * POST /api/v1/auth/logout
     *
     * Revokes only the current token (not all device tokens).
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }

    /**
     * GET /api/v1/auth/me
     *
     * Returns the authenticated user and linked student profile summary.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load('student');

        return response()->json([
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->role,
            'student' => $user->student ? [
                'id'             => $user->student->id,
                'student_number' => $user->student->student_number,
                'full_name'      => $user->student->full_name,
                'status'         => $user->student->status,
                'avatar_url'     => $user->student->avatar_url
                    ? asset('uploads/' . ltrim($user->student->avatar_url, '/'))
                    : null,
            ] : null,
        ]);
    }

    /**
     * POST /api/v1/auth/refresh
     *
     * Revokes the current token and issues a new one with the same device name.
     */
    public function refresh(Request $request): JsonResponse
    {
        $user       = $request->user();
        $deviceName = $request->user()->currentAccessToken()->name;

        $request->user()->currentAccessToken()->delete();

        $token = $user->createToken(
            $deviceName,
            ['student:read', 'student:write']
        )->plainTextToken;

        return response()->json([
            'token'      => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * POST /api/v1/auth/register
     *
     * Self-registration is currently handled via the web portal.
     * This stub returns 403 to prevent unapproved self-registration via API.
     * Enable and implement this if a mobile registration flow is desired.
     */
    public function register(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'Self-registration via the API is not enabled. Please register through the portal.',
        ], 403);
    }
}
