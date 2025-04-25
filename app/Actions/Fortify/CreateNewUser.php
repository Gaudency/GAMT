<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                 'string',
                  'max:255',
                   'unique:users',
                    'regex:/^[A-Za-z0-9_]+$/',
                     'not_in:admin,root,superuser,moderator,administrator',
                      'not_in:username,login,email,mail',
                       'not_in:webmaster,hosting,server,root,admin,administrator,moderator,superuser,username,user,login,email,mail',
                        'not_in:admin,root,superuser,moderator,administrator',
            ],
            'phone' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'usertype' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:4'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'username' => $input['username'],
            'phone' => $input['phone'],
            'address' => $input['address'],
            'position' => $input['position'],
            'usertype' => $input['usertype'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
