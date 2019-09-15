<?php

namespace App\Http\Controllers;

//use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Categoria;

class CategoriaCotroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/'); //aca le decimos que si no es por ajax no ingresara

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $categorias = Categoria::orderby('id', 'desc')->paginate(2); // aca le decimos que cuentos registro se mostraran en este caso solo 2
            //como ya sabemos para que sirve order by pero en laravel se aplica asi
        } else {
            $categorias = Categoria::where($criterio, 'like', '%' . $buscar . '%')->orderby('id', 'desc')->paginate(2);
            //Al igual que el operador like
        }

        //DB::table('categoria')->paginate(2);
        //Categoria::all(); = para mostrar todos los registro
        return [
            'paginacion' => [
                'total' => $categorias->total(),
                'current_page' => $categorias->currentPage(),
                'per_page' => $categorias->perPage(),
                'last_page' => $categorias->lastPage(),
                'from' => $categorias->firstItem(),
                'to' => $categorias->lastItem()
            ],
            'categorias' => $categorias
        ];
    }
    public function selectCategoria(Request $request){
        if (!$request->ajax()) return redirect('/');
        $categorias = Categoria::where('condicion', '=','1')
        ->select('id','nombre')->orderBy('id','asc')->get();

        return ['categoria' => $categorias];
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $categorias = new Categoria;
        $categorias->nombre = $request->nombre;
        $categorias->descripcion = $request->descripcion;
        $categorias->condicion = '1';
        $categorias->save();
    }

    public function listarPdf()
    {
        $articulos = Articulos::join('categoria', 'articulos.id_categoria', '=', 'categoria.id')
            ->select('articulos.id', 'articulos.id_categoria', 'articulos.codigo', 
            'articulos.nombre', 'articulos.precio_venta', 'articulos.stock', 
            'articulos.descripcion', 'articulos.condicion', 
            'categoria.nombre as nombre_categoria')
            ->orderby('articulos.nombre', 'desc')->get();

        $cont = Articulos::count();

        $pdf = \PDF::loadView('pdf.articulospdf', ['articulos' => $articulos, 'cont' => $cont]);
        return $pdf->download('articulos.pdf');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $categorias = Categoria::findOrFail($request->id);
        $categorias->nombre = $request->nombre;
        $categorias->descripcion = $request->descripcion;
        $categorias->condicion = '1';
        $categorias->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $categorias = Categoria::findOrFail($request->id);
        $categorias->condicion = '0';
        $categorias->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $categorias = Categoria::findOrFail($request->id);
        $categorias->condicion = '1';
        $categorias->save();
    }
}
