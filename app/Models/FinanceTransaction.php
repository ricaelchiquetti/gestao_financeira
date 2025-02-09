<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $company_id
 * @property int $entity_id
 * @property string $type
 * @property string $transaction_date
 * @property string $code
 * @property float $value
 * @property string $due_date
 * @property string $payment_method
 */
class FinanceTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'entity_id',
        'account_plan_id',
        'transaction_date',
        'code',
        'value',
        'due_date',
        'payment_method',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'due_date' => 'date',
        'value' => 'decimal:2',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    public function accountPlan(): BelongsTo
    {
        return $this->belongsTo(AccountPlan::class);
    }
}
