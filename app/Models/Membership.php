<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'memberships';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'type', 
        'fee', 
        'description'
    ];

    /**
     * The users that belong to the membership.
     * 
     * Defines a many-to-many relationship between memberships and users.
     */
public function users()
{
    return $this->belongsToMany(User::class, 'membership_user') // Specify the pivot table name
                ->using(MembershipUser::class) // Specify the custom pivot model
                ->withPivot('status', 'denial_reason') // Add custom pivot attributes
                ->withTimestamps(); // Automatically manage created_at and updated_at
}
}
