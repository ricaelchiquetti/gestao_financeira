<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $company_id
 * @property string $code
 * @property string $description
 * @property string $type
 */
class AccountPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'code',
        'description',
        'account_plan_type_id',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function accountPlanType()
    {
        return $this->belongsTo(AccountPlanType::class);
    }
}
