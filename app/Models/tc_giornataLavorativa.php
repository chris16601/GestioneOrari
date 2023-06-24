<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class tc_giornataLavorativa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tc_giornatalavorativa';

    protected $primaryKey = 'id_giornataLavorativa';

    protected $delete = 'deleted_at';

    protected $fillable = [
        'id_giornataLavorativa',
        'id_utente',
        'data_giornata',
        'da_ora',
        'a_ora',
        'id_tipo_giornata',
    ];

    public function tipologiaGiornata(){
        return $this->belongsTo(tt_tipoGiornata::class, 'id_tipo_giornata', 'id_tipo_giornata');
    }
}
