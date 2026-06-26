<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Transforms a Student model into a clean, mobile-safe JSON structure.
 *
 * @mixin \App\Models\Student
 */
class StudentProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'student_number' => $this->student_number,
            'full_name'      => $this->full_name,   // uses getFullNameAttribute()
            'first_name'     => $this->first_name,
            'middle_name'    => $this->middle_name,
            'last_name'      => $this->last_name,
            'email'          => $this->email,
            'phone'          => $this->phone,
            'address'        => $this->address,
            'sex'            => $this->sex,
            'branch'         => $this->branch,
            'status'         => $this->status,     // active | inactive | graduated
            'avatar_url'     => $this->avatar_url
                ? asset('uploads/' . ltrim($this->avatar_url, '/'))
                : null,
            'course' => [
                'name'     => $this->selected_course_name,
                'code'     => $this->selected_course_code,
                'duration' => $this->duration,
            ],
            'financials' => [
                'fees_paid'     => (float) ($this->fees_paid ?? 0),
                'balance_due'   => (float) ($this->balance_due ?? 0),
                'total_balance' => (float) ($this->total_balance ?? 0),
            ],
            'guardian' => [
                'name'         => $this->guardian_name,
                'phone'        => $this->guardian_phone,
                'email'        => $this->guardian_email,
                'relationship' => $this->guardian_relationship,
            ],
            'dates' => [
                'date_of_birth'     => $this->date_of_birth?->toDateString(),
                'start_date'        => $this->start_date?->toDateString(),
                'registration_date' => $this->registration_date?->toDateString(),
            ],
        ];
    }
}
