<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountPlanType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'company_id'
    ];

    protected $casts = [
        'type' => 'string',
    ];

    public function accountPlans()
    {
        return $this->hasMany(AccountPlan::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
