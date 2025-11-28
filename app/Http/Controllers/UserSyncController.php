<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class UserSyncController extends Controller
{
    public function syncUsers()
    {
        $usersFromDb2 = DB::connection('mysql2')->table('users')->get();

        $existingEmailsInDb1 = DB::connection('mysql')->table('users')->pluck('email')->toArray();

        foreach ($usersFromDb2 as $user) {
            if (!empty($user->email)) {
                $existingUser = DB::connection('mysql')->table('users')->where('email', $user->email)->first();

                if (!$existingUser) {
                    DB::connection('mysql')->table('users')->insert([
                        'name' => $user->username,
                        'f_name' => $user->firstname,
                        'l_name' => $user->lastname,
                        'email' => $user->email,
                        'phone' => $user->mobile ?? 'N/A',
                        'password' => $user->password,  
                        'street_address' => $user->address ?? null,
                        'country' => $user->country ?? null,
                        'city' => $user->city ?? null,
                        'zip' => $user->zip ?? null,
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at,
                    ]);
                } else {
                    continue;
                }
            }
        }

        return "Users synced successfully.";
    }
}
