<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmpleadoController extends Controller
{
    public function index()
    {
        return $this->vista(null);
    }

    private function vista(?Empleado $editar)
    {
        return view('htmla.usuarios', [
            'saludo'    => $this->saludo(),
            'empleados' => Empleado::orderByDesc('Id_empleado')->get(),
            'editar'    => $editar,
        ]);
    }

    private function reglas(?Empleado $actual = null): array
    {
        return [
            'nombre'     => 'required|string|max:20',
            'ap_paterno' => 'required|string|max:20',
            'telefono'   => ['required', 'string', 'max:10',
                Rule::unique('empleado', 'Telefono')->ignore($actual?->Id_empleado, 'Id_empleado')],
            'direccion'  => 'required|string|max:40',
            'puesto'     => 'required|string|max:40',
            'contrasena' => [$actual ? 'nullable' : 'required', 'string', 'min:6', 'max:100'],
            'rol'        => 'required|in:admin,vendedor,almacen,contador',
        ];
    }

    public function store(Request $request)
    {
        $d = $this->validar($request, $this->reglas());
        Empleado::create([
            'Nombre' => $d['nombre'], 'Ap_paterno' => $d['ap_paterno'], 'Telefono' => $d['telefono'],
            'Direccion' => $d['direccion'], 'Puesto' => $d['puesto'], 'Contrasena' => Hash::make($d['contrasena']), 'rol' => $d['rol'],
        ]);
        return redirect()->route('admin.usuarios.index')->with('ok', 'Empleado guardado correctamente.');
    }

    public function edit(Empleado $usuario)
    {
        return $this->vista($usuario);
    }

    public function update(Request $request, Empleado $usuario)
    {
        $d = $this->validar($request, $this->reglas($usuario));
        $usuario->fill([
            'Nombre' => $d['nombre'], 'Ap_paterno' => $d['ap_paterno'], 'Telefono' => $d['telefono'],
            'Direccion' => $d['direccion'], 'Puesto' => $d['puesto'], 'rol' => $d['rol'],
        ]);
        if (!empty($d['contrasena'])) {
            $usuario->Contrasena = Hash::make($d['contrasena']);
        }
        $usuario->save();
        return redirect()->route('admin.usuarios.index')->with('ok', 'Empleado actualizado correctamente.');
    }

    public function destroy(Empleado $usuario)
    {
        if ((int) $usuario->Id_empleado === (int) session('admin_id')) {
            return redirect()->route('admin.usuarios.index')->with('err', 'No puedes eliminar tu propio usuario.');
        }
        return $this->borrar(fn () => $usuario->delete(), 'Empleado eliminado.', route('admin.usuarios.index'));
    }
}
