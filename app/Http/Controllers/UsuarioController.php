<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

use App\Usuario;

class UsuarioController extends Controller
{

    public function index()
    {
        return view('usuarios', ['usuarios' => Usuario::all()]);
    }
    
    public function store(Request $request)
    {
        $rules = [
            'nombre' => 'required|alpha_spaces',
            'apellido' => 'required|alpha_spaces',
            'documento' => 'required|numeric|unique:usuarios,documento',
        ];

        $errors = [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.alpha_spaces' => 'El nombre solo acepta letras y espacios.',
            'apellido.required' => 'El apellido es obligatorio.',
            'apellido.alpha_spaces' => 'El apellido solo acepta letras y espacios.',
            'documento.required' => 'El documento es obligatorio.',
            'documento.numeric' => 'El documento solo acepta numeros.',
            'documento.unique' => 'El documento ingresado, ya existe!'
        ];
        $validation = Validator::make($request->all(), $rules, $errors);

        if($validation->fails()){
            return response()->json([
                'error'   => true,
                'errors'  => $validation->errors()
            ]);
        }else{
            $usuario = new Usuario;
            $usuario->documento = $request->input('documento');
            $usuario->nombre = $request->input('nombre');
            $usuario->apellido = $request->input('apellido');
            $saved = $usuario->save();
            if($saved){
                return response()->json([
                    'success' => true,
                    'usuario' => $usuario
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
        $data = Usuario::find($id);
        $usuario = [
            'id'            => $data->id,
            'documento'     => $data->documento,
            'nombre'        => $data->nombre,
            'apellido'      => $data->apellido
        ];

        return response()->json([
            'success' => true,
            'usuario' => $usuario
        ]);
    }

    public function update(Request $request)
    {
        $rules = [
            'nombre' => 'required|alpha_spaces',
            'apellido' => 'required|alpha_spaces',
            'documento' => 'required|numeric|unique:usuarios,documento,'.$request->input('id'),
        ];

        $errors = [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.alpha_spaces' => 'El nombre solo acepta letras y espacios.',
            'apellido.required' => 'El apellido es obligatorio.',
            'apellido.alpha_spaces' => 'El apellido solo acepta letras y espacios.',
            'documento.required' => 'El documento es obligatorio.',
            'documento.numeric' => 'El documento solo acepta numeros.',
            'documento.unique' => 'El documento ingresado, ya existe!'
        ];
        $validation = Validator::make($request->all(), $rules, $errors);

        if($validation->fails()){
            return response()->json([
                'error'   => true,
                'errors'  => $validation->errors()
            ]);
        }else{
            $usuario = Usuario::find($request->input('id'));
            $usuario->documento = $request->input('documento');
            $usuario->nombre = $request->input('nombre');
            $usuario->apellido = $request->input('apellido');
            $saved = $usuario->save();
            if($saved){
                return response()->json([
                    'success' => true,
                    'usuario' => $usuario
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
        $usuario = Usuario::find($request->id);
        $delete = $usuario->delete();

        if($delete){
            return response()->json(['success' => true]);
        }else{
            return response()->json(['error' => true]);
        }
    }
}
