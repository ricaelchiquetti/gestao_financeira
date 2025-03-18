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
 */
class FinanceTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'company_id',
        'entity_id',
        'account_plan_id',
        'financial_account_id',
        'transaction_date',
        'code',
        'value',
        'document_number',
        'due_date',
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

    public function financialAccount(): BelongsTo
    {
        return $this->belongsTo(FinancialAccount::class);
    }
}
