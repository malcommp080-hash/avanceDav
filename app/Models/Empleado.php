<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleado';
    protected $primaryKey = 'Id_empleado';
    public $timestamps = false;

    protected $fillable = ['Nombre', 'Ap_paterno', 'Telefono', 'Direccion', 'Puesto', 'Contrasena', 'rol'];

    protected $hidden = ['Contrasena'];
}
