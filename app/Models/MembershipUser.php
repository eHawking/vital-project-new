<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MembershipUser extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'membership_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'membership_id', 'status', 'denial_reason'];

    /**
     * The statuses available for a membership purchase.
     */
    const STATUS_IN_REVIEW = 'in_review';
    const STATUS_APPROVED = 'approved';
    const STATUS_DENIED = 'denied';

    /**
     * Relationship to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship to the membership.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    /**
     * Check if the membership is approved.
     *
     * @return bool
     */
    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if the membership is in review.
     *
     * @return bool
     */
    public function isInReview()
    {
        return $this->status === self::STATUS_IN_REVIEW;
    }

    /**
     * Check if the membership is denied.
     *
     * @return bool
     */
    public function isDenied()
    {
        return $this->status === self::STATUS_DENIED;
    }
}
