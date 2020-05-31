<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Producto;
use App\Guia;

class GuiaController extends Controller
{
    public function index()
    {
        return view('guias', ['productos' => Producto::all(), 'guias' => Guia::all()]);
    }
    
    public function store(Request $request)
    {
        $rules = [
            'numero'      => 'required|alpha_num|unique:guias,numero_guia',
            'descripcion' => 'required|alpha_spaces',
            'producto'    => 'required'
        ];

        $errors = [
            'descripcion.required' => 'La descripcion es obligatoria.',
            'descripcion.alpha_spaces' => 'La descripcion solo acepta letras y espacios.',
            'producto.required' => 'El producto es obligatorio.'
        ];
        $validation = Validator::make($request->all(), $rules, $errors);

        if($validation->fails()){
            return response()->json([
                'error'   => true,
                'errors'  => $validation->errors()
            ]);
        }else{
            $guia = new Guia;
            $guia->descripcion = $request->input('descripcion');
            $guia->productos_id  = $request->input('producto');
            $guia->save();
            $guia =  Guia::latest('id')->first();
            $guia->numero_guia = str_pad($guia->id, 5, "P000", STR_PAD_LEFT);
            $saved = $guia->save();
            if($saved){
                return response()->json([
                    'success' => true,
                    'producto' => $guia
                ]);
            }else{
                return response()->json([
                    'error' => true
                ]);
            }
        }
    }

    public function last(Request $request)
    {
        $guia =  Guia::latest('id')->first();
        if($guia){
            $numero = $guia->id+1;
            $guia = str_pad($numero, 5, "P000", STR_PAD_LEFT);
        }else{
            $guia = "P0001";
        }
        return response()->json([
            'success' => true,
            'guia' => $guia
        ]);
    }

    
    public function show(Request $request, $id)
    {
        $data = Guia::find($id);
        $guia = [
            'id'            => $data->id,
            'numero'        => $data->numero_guia,
            'descripcion'   => $data->descripcion,
            'producto'      => $data->productos_id
        ];

        return response()->json([
            'success' => true,
            'guia' => $guia
        ]);
    }

    public function update(Request $request)
    {
        $rules = [
            'numero'      => 'required|alpha_num|unique:guias,numero_guia,'.$request->input('id'),
            'descripcion' => 'required|alpha_spaces',
            'producto'    => 'required'
        ];

        $errors = [
            'descripcion.required' => 'La descripcion es obligatoria.',
            'descripcion.alpha_spaces' => 'La descripcion solo acepta letras y espacios.',
            'producto.required' => 'El producto es obligatorio.'
        ];
        $validation = Validator::make($request->all(), $rules, $errors);

        if($validation->fails()){
            return response()->json([
                'error'   => true,
                'errors'  => $validation->errors()
            ]);
        }else{
            $guia = Guia::find($request->input('id'));
            $guia->descripcion   = $request->input('descripcion');
            $guia->productos_id  = $request->input('producto');
            $saved = $guia->save();
            if($saved){
                return response()->json([
                    'success' => true,
                    'guia'    => $guia
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
        $guia   = Guia::find($request->id);
        $delete = $guia->delete();

        if($delete){
            return response()->json(['success' => true]);
        }else{
            return response()->json(['error' => true]);
        }
    }
}
