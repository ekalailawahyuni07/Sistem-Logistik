<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_user' => [
                'required',
                'string',
                'max:100'
            ],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:100',
                Rule::unique(User::class)
                    ->ignore($this->user()->id_user, 'id_user')
            ],

            'foto_profile' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048'
            ],

            'no_telp' => [
                'nullable',
                'regex:/^[0-9]{11,13}$/'
            ],

            'alamat' => [
                'nullable',
                'string'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_user.required' => 'Nama user wajib diisi.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',

            'foto_profile.image' => 'File harus berupa gambar.',
            'foto_profile.mimes' => 'Foto harus berformat JPG, JPEG, atau PNG.',
            'foto_profile.max' => 'Ukuran foto maksimal 2 MB.',

            'no_telp.regex' => 'Nomor telepon harus terdiri dari 11 sampai 13 digit angka.',
        ];
    }
}