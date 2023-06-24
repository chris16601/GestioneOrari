<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tt_tipoGiornata extends Model
{
    use HasFactory;

    protected $table = 'tt_tipogiornata';

    protected $primaryKey = 'id_TipoGiornata';

    protected $fillable = [
        'id_tipo_giornata',
        'descrizione',
    ];
}
