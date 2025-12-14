<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $primaryKey = 'expense_id';
    public $timestamps = false;

    protected $fillable = [
        'event_id',
        'activity_id',
        'description',
        'amount',
        'paid_by_user_id',
        'expense_date'
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }

    public function paidBy()
    {
        return $this->belongsTo(User::class, 'paid_by_user_id');
    }

    public function shares()
    {
        return $this->hasMany(ExpenseShare::class, 'expense_id');
    }
}
