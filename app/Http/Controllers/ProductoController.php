<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

use App\Producto;

class ProductoController extends Controller
{
    public function index()
    {
        return view('productos', ['productos' => Producto::all()]);
    }
    
    public function store(Request $request)
    {
        $rules = [
            'nombre' => 'required|alpha_spaces',
            'total' => 'required|numeric'
        ];

        $errors = [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.alpha_spaces' => 'El nombre solo acepta letras y espacios.',
            'total.required' => 'El total es obligatorio.',
            'total.numeric' => 'El total solo acepta numeros.',
        ];
        $validation = Validator::make($request->all(), $rules, $errors);

        if($validation->fails()){
            return response()->json([
                'error'   => true,
                'errors'  => $validation->errors()
            ]);
        }else{
            $producto = new Producto;
            $producto->nombre = $request->input('nombre');
            $producto->total  = $request->input('total');
            $saved = $producto->save();
            if($saved){
                return response()->json([
                    'success' => true,
                    'producto' => $producto
                ]);
            }else{
                return response()->json([
                    'error' => true
                ]);
            }
        }
    }

    
    public function show(Request $request, $id)
    {
        $data = Producto::find($id);
        $producto = [
            'id'        => $data->id,
            'nombre'    => $data->nombre,
            'total'     => $data->total
        ];

        return response()->json([
            'success' => true,
            'producto' => $producto
        ]);
    }

    public function update(Request $request)
    {
        $rules = [
            'nombre' => 'required|alpha_spaces',
            'total' => 'required|numeric'
        ];

        $errors = [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.alpha_spaces' => 'El nombre solo acepta letras y espacios.',
            'total.required' => 'El total es obligatorio.',
            'total.numeric' => 'El total solo acepta numeros.',
        ];
        $validation = Validator::make($request->all(), $rules, $errors);

        if($validation->fails()){
            return response()->json([
                'error'   => true,
                'errors'  => $validation->errors()
            ]);
        }else{
            $producto = Producto::find($request->input('id'));
            $producto->nombre = $request->input('nombre');
            $producto->total = $request->input('total');
            $saved = $producto->save();
            if($saved){
                return response()->json([
                    'success' => true,
                    'producto' => $producto
                ]);
            }else{
                return response()->json([
                    'error' => true
                ]);
            }
        }
    }

    public function delete(Request $request, $id)
    {
        $producto = Producto::find($request->id);
        $delete = $producto->delete();

        if($delete){
            return response()->json(['success' => true]);
        }else{
            return response()->json(['error' => true]);
        }
    }
}
