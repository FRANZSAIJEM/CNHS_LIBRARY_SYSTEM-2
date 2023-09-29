<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class ProfileUpdateRequest extends FormRequest
{
 /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Make sure the user is authorized to update their profile (you can define your logic here)
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'max:255'],
            'grade_level' => ['string', 'max:255', 'nullable'],
            'contact' => ['string', 'nullable'],
            'gender' => ['string', 'nullable'],
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

       /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if the 'gender' field has changed in the request
            if ($this->has('gender') && $this->input('gender') !== $this->user()->gender) {
                // Gender has changed, update the image based on the new gender
                $gender = $this->input('gender');
                $user = $this->user();

                if ($gender === 'Male') {
                    $user->image = 'Male.png'; // Replace with the actual path for the male default image
                } elseif ($gender === 'Female') {
                    $user->image = 'Female.png'; // Replace with the actual path for the female default image
                } else {
                    $user->image = 'Other.png'; // Default image for other genders
                }

                $user->save();
            }
        });

        return redirect()->back()->with('success', 'Updated successfully');

    }
}
