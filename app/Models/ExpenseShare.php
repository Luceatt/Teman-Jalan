<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseShare extends Model
{
    use HasFactory;

    protected $primaryKey = 'share_id';
    public $timestamps = false;

    protected $fillable = [
        'expense_id',
        'user_id',
        'amount_owed',
        'is_settled'
    ];

    protected $casts = [
        'amount_owed' => 'decimal:2',
        'is_settled' => 'boolean'
    ];

    public function expense()
    {
        return $this->belongsTo(Expense::class, 'expense_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

