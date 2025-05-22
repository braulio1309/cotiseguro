<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Core\Auth\Traits\Attribute\UserAttribute;
use App\Models\Core\Auth\Traits\Boot\UserBootTrait;
use App\Models\Core\Auth\Traits\Method\HasRoles;
use App\Models\Core\Auth\Traits\Method\UserMethod;
use App\Models\Core\Auth\Traits\Method\UserStatus;
use App\Models\Core\Auth\Traits\Relationship\UserRelationship;
use App\Models\Core\Auth\Traits\Rules\UserRules;
use App\Models\Core\Auth\Traits\Scope\UserScope;
use Spatie\Activitylog\Traits\CausesActivity;
use Altek\Eventually\Eventually;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Core\Auth\User;

class Cotizacion extends Model
{
    use UserAttribute,
        UserMethod,
        UserRelationship,
        UserScope,
        HasRoles,
        UserRules,
        UserBootTrait,
        Eventually,
        Notifiable,
        CausesActivity,
        UserStatus,
        HasFactory;



    protected $table = 'cotizaciones';
        protected $fillable = [
        'cliente_id',
        'agente',
        'titular',
        'email',
        'telefono',
        'edades',
        'descuento',
        'vida',
        'maternidad',
        'funerarios',
        'viajes',
        'pdf_path', 

    ];

    protected $casts = [
        'edades' => 'array',
        'vida' => 'boolean',
        'maternidad' => 'boolean',
        'servicios_funerarios' => 'boolean',
        'viajes' => 'boolean',
    ];

    // Una cotización pertenece a un cliente
    public function client()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relación con el usuario (si aplica)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function companias()
    {
        return $this->belongsToMany(Compania::class, 'compania_cotizacion');
    }
}
