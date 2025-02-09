<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string|null $cnpj
 * @property bool $active
 */
class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cnpj',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    // Tipagem para o método
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function entities(): HasMany
    {
        return $this->hasMany(Entity::class);
    }

    public function financeTransactions(): HasMany
    {
        return $this->hasMany(FinanceTransaction::class);
    }

    public function accountPlans(): HasMany
    {
        return $this->hasMany(AccountPlan::class);
    }

    public function accountPlansType(): HasMany
    {
        return $this->hasMany(AccountPlanType::class);
    }
}
