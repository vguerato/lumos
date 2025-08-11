<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $account_id
 * @property string $type
 * @property float $amount
 * @property string $description
 * @property-read Account $account
 */
class Transaction extends Model
{
    use HasUlids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'account_id',
        'type',
        'amount',
        'description'
    ];
    protected $casts = [
        'amount' => 'decimal:2'
    ];

    public function account(): BelongsTo {
        return $this->belongsTo(Account::class);
    }
}
