<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rol;

class RolController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/'); //aca le decimos que si no es por ajax no ingresara

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $rol = Rol::orderby('id', 'desc')->paginate(3); // aca le decimos que cuentos registro se mostraran en este caso solo 2
            //como ya sabemos para que sirve order by pero en laravel se aplica asi
        } else {
            $rol = Rol::where($criterio, 'like', '%' . $buscar . '%')->orderby('id', 'desc')->paginate(3);
            //Al igual que el operador like
        }

        //DB::table('categoria')->paginate(2);
        //Categoria::all(); = para mostrar todos los registro
        return [
            'paginacion' => [
                'total' => $rol->total(),
                'current_page' => $rol->currentPage(),
                'per_page' => $rol->perPage(),
                'last_page' => $rol->lastPage(),
                'from' => $rol->firstItem(),
                'to' => $rol->lastItem()
            ],
            'rol' => $rol
        ];
    }

    public function selectRol(){
        $rol = Rol::where('condicion', '=', '1')
        ->select('id','nombre')
        ->orderBy('nombre', 'asc')->get();

        return ['roles' => $rol];
    }
}
