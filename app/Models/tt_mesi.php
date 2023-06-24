<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tt_mesi extends Model
{
    use HasFactory;

    protected $table = 'tt_mesi';

    protected $primaryKey = 'id_mese';

    protected $fillable = [
        'id_mese',
        'descrizione',
    ];
}
