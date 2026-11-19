<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    protected array $mensajes = [
        'required'  => 'El campo :attribute es obligatorio.',
        'numeric'   => 'El campo :attribute debe ser un número.',
        'integer'   => 'El campo :attribute debe ser un número entero.',
        'min'       => [
            'numeric' => 'El campo :attribute debe ser al menos :min.',
            'string'  => 'El campo :attribute debe tener al menos :min caracteres.',
        ],
        'max'       => [
            'numeric' => 'El campo :attribute no puede ser mayor a :max.',
            'string'  => 'El campo :attribute no puede tener más de :max caracteres.',
        ],
        'digits'    => 'El campo :attribute debe tener :digits dígitos.',
        'same'      => 'Las contraseñas no coinciden.',
        'date'      => 'El campo :attribute debe ser una fecha válida.',
        'exists'    => 'El valor seleccionado en :attribute no es válido.',
        'in'        => 'El valor seleccionado en :attribute no es válido.',
        'unique'    => 'Ese :attribute ya está registrado.',
        'telefono.unique' => 'Este número ya está registrado.', // RF005
        'regex'     => 'El formato de :attribute no es válido.',
        'file'      => 'Debes seleccionar un archivo válido.',
    ];

    protected array $atributos = [
        'nombre' => 'nombre', 'ap_paterno' => 'apellido paterno', 'telefono' => 'teléfono',
        'contrasena' => 'contraseña', 'direccion' => 'dirección', 'cp' => 'código postal',
        'num_piezas' => 'número de piezas', 'total_piezas' => 'total de piezas',
        'id_cliente' => 'cliente', 'id_categoria' => 'categoría', 'id_proovedor' => 'proveedor',
        'preciopro' => 'precio proveedor', 'precioneto' => 'precio de venta',
        'confirmar' => 'confirmación', 'nueva' => 'nueva contraseña',
    ];

    /** Valida con mensajes en español. */
    protected function validar(Request $request, array $reglas): array
    {
        return $request->validate($reglas, $this->mensajes, $this->atributos);
    }

    /** Saludo según la hora (zona horaria de la app). */
    protected function saludo(): string
    {
        $h = (int) now()->format('H');
        if ($h >= 6 && $h < 12) return '☀️ ¡Buenos días!';
        if ($h >= 12 && $h < 19) return '🌤️ ¡Buenas tardes!';
        return '🌙 ¡Buenas noches!';
    }

    /** Ejecuta un borrado y responde con mensaje; si hay registros relacionados (FK) avisa en vez de tronar. */
    protected function borrar(callable $accion, string $ok, string $volver)
    {
        try {
            $accion();
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect($volver)->with('err', 'No se puede eliminar: tiene registros relacionados en otras tablas.');
        }
        return redirect($volver)->with('ok', $ok);
    }
}
