<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Transaction;

class Book extends Model
{
    protected $table = 'book';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function transaction()
    {
        return $this->hasMany(Transaction::class, 'book_id', 'id');
    }
}
