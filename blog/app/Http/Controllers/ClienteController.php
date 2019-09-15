<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Persona;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/'); //aca le decimos que si no es por ajax no ingresara

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $personas = Persona::orderby('id', 'desc')->paginate(2); // aca le decimos que cuentos registro se mostraran en este caso solo 2
            //como ya sabemos para que sirve order by pero en laravel se aplica asi
        } else {
            $personas = Persona::where($criterio, 'like', '%' . $buscar . '%')->orderby('id', 'desc')->paginate(2);
            //Al igual que el operador like
        }

        //DB::table('categoria')->paginate(2);
        //Categoria::all(); = para mostrar todos los registro
        return [
            'paginacion' => [
                'total' => $personas->total(),
                'current_page' => $personas->currentPage(),
                'per_page' => $personas->perPage(),
                'last_page' => $personas->lastPage(),
                'from' => $personas->firstItem(),
                'to' => $personas->lastItem()
            ],
            'persona' => $personas
        ];
    }

    public function selectCliente(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $clientes = Persona::where('nombre', 'like', '%'. $filtro . '%')
        ->orWhere('num_documento', 'like', '%'. $filtro . '%')
        ->select('id','.nombre','num_documento')
        ->orderBy('nombre', 'asc')->get();
 
        return ['clientes' => $clientes];
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $personas = new Persona();
        $personas->nombre = $request->nombre;
        $personas->tipo_documento = $request->tipo_documento;
        $personas->num_documento = $request->num_documento;
        $personas->direccion = $request->direccion;
        $personas->telefono = $request->telefono;
        $personas->email = $request->email;
        $personas->save();
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $personas = Persona::findOrFail($request->id);
        $personas->nombre = $request->nombre;
        $personas->tipo_documento = $request->tipo_documento;
        $personas->num_documento = $request->num_documento;
        $personas->direccion = $request->direccion;
        $personas->telefono = $request->telefono;
        $personas->email = $request->email;
        $personas->save();
    }
}
