<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Provincia;
use App\Helpers\CustomCrypt;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\ParamSystemEnum;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'provincia_id',
        'name',
        'nombre_fiscal',
        'cif',
        'telefono',
        'direccion',
        'email',
        'email_comercial',
        'ciudad',
        'cuenta',
        'password',
        'role',
        'avatar',
        'filetoken',
        'has_electronic_billing',
        'postal_code'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'real_password'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'has_electronic_billing' => 'boolean'
    ];

    protected $appends = [
        'tipo_usuario',
        'real_password',
        'role_str',
    ];

    public function getRealPasswordAttribute()
    {
        try {
            return CustomCrypt::Decrypt($this->password);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return 'formato desconocido';
        }
    }

    public function getTipoUsuarioAttribute()
    {
        if ($this->role == 1) {
            return 'administrador';
        } elseif ($this->role == 3) {
            return 'gestor';
        } elseif ($this->role == 4) {
            return 'empleado';
        } else {
            return 'usuario';
        }
    }

    public function getRoleStrAttribute()
    {
        if ($this->role == 1) {
            return 'Administrador';
        } elseif ($this->role == 2) {
            return 'Cliente';
        } elseif ($this->role == 3) {
            return 'Gestor';
        } elseif ($this->role == 4) {
            return 'Empleado';
        } else {
            return 'Gestor';
        }
    }

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'provincia_id');
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = $password;
    }

    public function metodos_pago()
    {
        return $this->hasOne(UserMetodoPago::class);
    }

    public function tipo_gasto()
    {
        return $this->hasMany(TiposGasto::class);
    }

    public function password_token()
    {
        return $this->hasMany(PasswordToken::class);
    }

    public function systemParams()
    {
        return $this->hasMany(SystemParam::class, 'business_id', 'id');
    }

    public function invoiceFooter()
    {
        return $this->systemParams()->where('param', ParamSystemEnum::INVOICE_FOOTER->value)->first();
    }

    public function companyLogoParam()
    {
        return $this->systemParams()->where('param', ParamSystemEnum::COMPANY_LOGO->value)->first();
    }

    public function companySignatureParam()
    {
        return $this->systemParams()->where('param', ParamSystemEnum::COMPANY_SIGNATURE->value)->first();
    }

    public function recibos()
    {
        return $this->hasMany(Recibo::class, 'user_id', 'id');
    }

    public function series()
    {
        return $this->hasMany(InvoiceSerie::class, 'user_id', 'id');
    }

    // Relaciones para gestor-clientes
    public function clientesAsociados()
    {
        return $this->belongsToMany(User::class, 'gestor_clientes', 'gestor_id', 'cliente_id')
            ->where('role', 2)
            ->withTimestamps();
    }

    public function gestoresAsociados()
    {
        return $this->belongsToMany(User::class, 'gestor_clientes', 'cliente_id', 'gestor_id')
            ->where('role', 3)
            ->withTimestamps();
    }
}
