<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class Account extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['account_number', 'name', 'current_balance', 'user_id'];

    /**
     * Retrieve the current balance divided by 100 since
     * the system stores the amount in cents
     *
     * @return float
     */
    public function getDecimalCurrentBalanceAttribute(): float
    {
        return $this->current_balance / 100;
    }

    /**
     * Movement relationship
     */
    public function movements()
    {
        return $this->hasMany(Movement::class);
    }

    /**
     * Sets the account number
     *
     * @return void
     */
    public function setAccountNumber()
    {
        $timestamp = nowLocal()->getTimestamp();
        $userId = $this->user_id;
        $this->account_number = "{$timestamp}{$userId}";
    }

    /**
     * Retrieves the relationship with the user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function transfer($source, $destination, $amount)
    {
//        Las cuentas pueden realizar movimientos válidos (posee el saldo indicado)
        if ($source == null) {
            throw new InvalidArgumentException("Source account does not exist.");
        }
        if ($destination == null) {
            throw new InvalidArgumentException("Destination account does not exist.");
        }
        if ($source->current_balance < $amount) {
            throw new InvalidArgumentException("Insufficient funds.");
        }
        if ($amount <= 0) {
            throw new InvalidArgumentException("No transference.");
        }
//        La cuenta emisora del movimiento tiene la reducción del saldo y el movimiento se ve reflejado en su historial
        Movement::Register($source, $amount, "Transfer to" . $destination->name, Movement::WITHDRAW);
//        La cuenta receptora del movimiento tiene el incremento del saldo y el movimiento se ve reflejado en su historial
        Movement::Register($destination, $amount, "Transfer from" . $source->name, Movement::DEPOSIT);
    }
}
