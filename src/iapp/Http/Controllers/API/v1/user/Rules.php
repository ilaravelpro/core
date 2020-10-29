<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/10/20, 12:49 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\User;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Rules
{
    public function rules(Request $request, $action, $arg = null, $unique = null)
    {
        $rules = [];
        switch ($action) {
            case 'profile':
                $rules = [
                    'avatar' => 'nullable|mimes:jpeg,jpg,png,gif|max:5120|dimensions:ratio=1',
                    'name' => 'nullable|string',
                    'family' => 'nullable|string',
                    'password' => 'nullable|min:6',
                    'website' => "nullable|max:191|regex:/^(?!:\/\/)([a-zA-Z0-9-_]+\.)*[a-zA-Z0-9][a-zA-Z0-9-_]+\.[a-zA-Z]{2,11}?$/",
                    'gender' => 'nullable|in:male,female',
                ];
                if (!$request->password) {
                    unset($rules['password']);
                }
                break;
            case 'store':
            case 'update':
                $rules = [
                    'creator_id' => "nullable",
                    'name' => 'nullable|string',
                    'family' => 'nullable|string',
                    'username' => "nullable|max:16|regex:/^[a-z0-9_-]{3,16}$/",
                    'password' => 'nullable|min:6',
                    'email' => "nullable|max:191|email",
                    'email.name' => "nullable|max:191|regex:/^[a-zA-Z0-9._-]*$/",
                    'email.domain' => "nullable|max:191|regex:/^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\.[a-zA-Z]{2,}$/",
                    'website' => "nullable|max:191|regex:/^(?!:\/\/)([a-zA-Z0-9-_]+\.)*[a-zA-Z0-9][a-zA-Z0-9-_]+\.[a-zA-Z]{2,11}?$/",
                    'status' => 'nullable|in:' . join(config('bit.status', ['awaiting', 'active', 'disable']), ','),
                    'role' => 'nullable|in:' . join(config('bit.types', ['admin', 'user']), ','),
                    //'mobile' => 'nullable|regex:\+(9[976]\d|8[987530]\d|6[987]\d|5[90]\d|42\d|3[875]\d|2[98654321]\d|9[8543210]|8[6421]|6[6543210]|5[87654321]|4[987654310]|3[9643210]|2[70]|7|1)\W*\d\W*\d\W*\d\W*\d\W*\d\W*\d\W*\d\W*\d\W*(\d{1,2})$',
                    'mobile.country' => 'nullable|numeric',
                    'mobile.number' => 'nullable|numeric',
                    'gender' => 'nullable|in:male,female',
                    'groups' => 'nullable',
                    //'mobile' => 'nullable',
                    'avatar_file' => 'nullable|max:5120',
                ];
                if (!$request->password) {
                    unset($rules['password']);
                }
                if ($arg == null || (isset($arg->email) && $arg->email != $request->email)) $rules['email'] .= '|unique:users,email';
                if ($arg == null || (isset($arg->mobile) && $arg->mobile != $request->mobile)) $rules['mobile'] .= '|unique:users,mobile';
                if ($arg == null || (isset($arg->website) && $arg->website != $request->website)) $rules['website'] .= '|unique:users,website';
                break;
        }
        $unique = $request->has('unique') ? $request->unique : $unique;
        if ($unique) return str_replace(['required'], ['nullable'], $rules[$unique]);
        return $rules;
    }
}
