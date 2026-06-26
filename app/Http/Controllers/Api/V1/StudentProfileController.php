<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\StudentProfileResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class StudentProfileController extends Controller
{
    /**
     * GET /api/v1/student
     *
     * Returns the authenticated student's full profile.
     */
    public function show(Request $request): StudentProfileResource
    {
        $student = $request->user()->student;

        abort_if(! $student, 404, 'Student profile not found for this user.');

        return new StudentProfileResource($student);
    }

    /**
     * PATCH /api/v1/student
     *
     * Allows the student to update limited personal fields.
     * Core identity fields (name, email, student_number) are read-only.
     */
    public function update(Request $request): StudentProfileResource
    {
        $student = $request->user()->student;

        abort_if(! $student, 404, 'Student profile not found.');

        $validated = $request->validate([
            'phone'                 => ['sometimes', 'string', 'max:20'],
            'address'               => ['sometimes', 'string', 'max:500'],
            'guardian_name'         => ['sometimes', 'string', 'max:255'],
            'guardian_phone'        => ['sometimes', 'string', 'max:20'],
            'guardian_email'        => ['sometimes', 'email', 'max:255'],
            'guardian_relationship' => ['sometimes', 'string', 'max:100'],
        ]);

        $student->update($validated);

        return new StudentProfileResource($student->fresh());
    }

    /**
     * POST /api/v1/student/avatar
     *
     * Uploads a new profile avatar. Stores to the 'public' disk.
     */
    public function uploadAvatar(Request $request): JsonResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $student = $request->user()->student;

        abort_if(! $student, 404, 'Student profile not found.');

        $path = $request->file('avatar')->store('avatars', 'public');

        $student->update(['avatar_url' => $path]);

        return response()->json([
            'message'    => 'Avatar updated successfully.',
            'avatar_url' => asset('storage/' . $path),
        ]);
    }

    /**
     * POST /api/v1/student/change-password
     *
     * Changes the user's password. Revokes ALL tokens on success
     * to force re-login across all devices for security.
     */
    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = $request->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect.',
                'errors'  => ['current_password' => ['The current password does not match our records.']],
            ], 422);
        }

        $user->update(['password' => Hash::make($request->password)]);

        // Revoke all tokens — user must re-login on all devices
        $user->tokens()->delete();

        return response()->json([
            'message' => 'Password changed successfully. Please log in again on all devices.',
        ]);
    }

    /**
     * GET /api/v1/student/documents
     *
     * Returns public asset URLs for the student's uploaded documents.
     * Returns null for any document that hasn't been uploaded yet.
     */
    public function documents(Request $request): JsonResponse
    {
        $student = $request->user()->student;

        abort_if(! $student, 404, 'Student profile not found.');

        $storageUrl = fn ($path) => $path ? asset('storage/' . ltrim($path, '/')) : null;

        return response()->json([
            'data' => [
                'photo'             => $storageUrl($student->photo_path),
                'birth_certificate' => $storageUrl($student->birth_certificate_path),
                'jamb'              => $storageUrl($student->jamb_path),
                'neco'              => $storageUrl($student->neco_path),
                'waec'              => $storageUrl($student->waec_path),
            ],
        ]);
    }
}
