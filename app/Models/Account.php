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


    private static function transfer_1P($source, $destination, $amount)
    {
        return [
            Movement::Register($source, $amount, "Transfer to" . $destination->name, Movement::TRANSFER),
            Movement::Register($destination, $amount, "Transfer from" . $source->name, Movement::RECEPTION)
        ];
    }

    private static function transfer_3P($source, $destination, $amount)
    {
        return [
            Movement::Register($source, $amount, "Transfer to" . $destination->name, Movement::TRANSFER_3P),
            Movement::Register($destination, $amount, "Transfer from" . $source->name, Movement::RECEPTION_3P)
        ];
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
        if ($source == $destination) {
            throw new InvalidArgumentException("Accounts are the same");
        }
        if ($source->user()->id == $destination->user()->id) {
            return self::transfer_1P($source, $destination, $amount);
        } else {
            return self::transfer_3P($source, $destination, $amount);
        }
    }
}
