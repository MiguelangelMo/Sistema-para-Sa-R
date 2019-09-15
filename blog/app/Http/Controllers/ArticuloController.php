<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Articulos;
use App\Http\Controllers\CategoriaCotroller;

class ArticuloController extends Controller
{
    public function index(Request $request)
    {

        if (!$request->ajax()) return redirect('/'); //aca le decimos que si no es por ajax no ingresara

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $articulos = Articulos::join('categoria', 'articulos.id_categoria', '=', 'categoria.id')
                ->select('articulos.id', 'articulos.id_categoria', 'articulos.codigo', 'articulos.nombre', 'articulos.precio_venta', 'articulos.stock', 'articulos.descripcion', 'articulos.condicion', 'categoria.nombre as nombre_categoria')
                ->orderby('articulos.id', 'desc')->paginate(2);
            // aca le decimos que cuentos registro se mostraran en este caso solo 2
            //como ya sabemos para que sirve order by pero en laravel se aplica asi
        } else {
            $articulos =
                Articulos::join('categoria', 'articulos.id_categoria', '=', 'categoria.id')
                ->select('articulos.id', 'articulos.id_categoria', 'articulos.codigo', 'articulos.nombre', 'articulos.precio_venta', 'articulos.stock', 'articulos.descripcion', 'articulos.condicion', 'categoria.nombre as nombre_categoria')
                ->where('articulos.' . $criterio, 'like', '%' . $buscar . '%')
                ->orderby('articulos.id', 'desc')->paginate(2);
            //Al igual que el operador like
        }

        //DB::table('categoria')->paginate(2);
        //Categoria::all(); = para mostrar todos los registro
        return [
            'paginacion' => [
                'total' => $articulos->total(),
                'current_page' => $articulos->currentPage(),
                'per_page' => $articulos->perPage(),
                'last_page' => $articulos->lastPage(),
                'from' => $articulos->firstItem(),
                'to' => $articulos->lastItem()
            ],
            'articulo' => $articulos
        ];
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
    public function listarArticulo(Request $request)
    {

        if (!$request->ajax()) return redirect('/'); //aca le decimos que si no es por ajax no ingresara

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $articulos = Articulos::join('categoria', 'articulos.id_categoria', '=', 'categoria.id')
                ->select('articulos.id', 'articulos.id_categoria', 'articulos.codigo', 'articulos.nombre', 'articulos.precio_venta', 'articulos.stock', 'articulos.descripcion', 'articulos.condicion', 'categoria.nombre as nombre_categoria')
                ->orderby('articulos.id', 'desc')->paginate(10);
            // aca le decimos que cuentos registro se mostraran en este caso solo 2
            //como ya sabemos para que sirve order by pero en laravel se aplica asi
        } else {
            $articulos =
                Articulos::join('categoria', 'articulos.id_categoria', '=', 'categoria.id')
                ->select('articulos.id', 'articulos.id_categoria', 'articulos.codigo', 'articulos.nombre', 'articulos.precio_venta', 'articulos.stock', 'articulos.descripcion', 'articulos.condicion', 'categoria.nombre as nombre_categoria')
                ->where('articulos.' . $criterio, 'like', '%' . $buscar . '%')
                ->orderby('articulos.id', 'desc')->paginate(10);
            //Al igual que el operador like
        }

        //DB::table('categoria')->paginate(2);
        //Categoria::all(); = para mostrar todos los registro
        return [
            'articulos' => $articulos
        ];
    }
    public function listarArticuloVenta(Request $request)
    {

        if (!$request->ajax()) return redirect('/'); //aca le decimos que si no es por ajax no ingresara

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $articulos = Articulos::join('categoria', 'articulos.id_categoria', '=', 'categoria.id')
                ->select('articulos.id', 'articulos.id_categoria', 'articulos.codigo', 'articulos.nombre', 'articulos.precio_venta', 'articulos.stock', 'articulos.descripcion', 'articulos.condicion', 'categoria.nombre as nombre_categoria')
                ->where('articulos.stock', '>', '0')
                ->orderby('articulos.id', 'desc')->paginate(10);
            // aca le decimos que cuentos registro se mostraran en este caso solo 2
            //como ya sabemos para que sirve order by pero en laravel se aplica asi
        } else {
            $articulos =
                Articulos::join('categoria', 'articulos.id_categoria', '=', 'categoria.id')
                ->select('articulos.id', 'articulos.id_categoria', 'articulos.codigo', 'articulos.nombre', 'articulos.precio_venta', 'articulos.stock', 'articulos.descripcion', 'articulos.condicion', 'categoria.nombre as nombre_categoria')
                ->where('articulos.' . $criterio, 'like', '%' . $buscar . '%')
                ->where('articulos.stock', '>', '0')
                ->orderby('articulos.id', 'desc')->paginate(10);
            //Al igual que el operador like
        }

        //DB::table('categoria')->paginate(2);
        //Categoria::all(); = para mostrar todos los registro
        return [
            'articulos' => $articulos
        ];
    }
    public function buscarArticulo(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $articulos = Articulos::where('codigo', '=', $filtro)
            ->select('id', 'nombre')
            ->orderBy('nombre', 'asc')
            ->take(1)
            ->get();

        return ['articulos' => $articulos];
    }
    public function buscarArticuloVenta(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $articulos = Articulos::where('codigo', '=', $filtro)
            ->select('id', 'nombre', 'stock', 'precio_venta')
            ->where('stock', '>', '0')
            ->orderBy('nombre', 'asc')
            ->take(1)
            ->get();

        return ['articulos' => $articulos];
    }
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $articulos = new Articulos;
        $articulos->id_categoria = $request->id_categoria;
        $articulos->codigo = $request->codigo;
        $articulos->nombre = $request->nombre;
        $articulos->precio_venta = $request->precio_venta;
        $articulos->stock = $request->stock;
        $articulos->descripcion = $request->descripcion;
        $articulos->condicion = '1';
        $articulos->save();
    }
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $articulos = Articulos::findOrFail($request->id);
        /*findorfail: lo que hace es buscar un objeto que coincida con el id que se envia a traves
         de ajax y sino un error nos dara*/
        $articulos->id_categoria = $request->id_categoria;
        $articulos->codigo = $request->codigo;
        $articulos->nombre = $request->nombre;
        $articulos->precio_venta = $request->precio_venta;
        $articulos->stock = $request->stock;
        $articulos->descripcion = $request->descripcion;
        $articulos->condicion = '1';
        $articulos->save();
    }
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $articulos = Articulos::findOrFail($request->id);
        $articulos->condicion = '0';
        $articulos->save();
    }
    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $articulos = Articulos::findOrFail($request->id);
        $articulos->condicion = '1';
        $articulos->save();
    }
}
