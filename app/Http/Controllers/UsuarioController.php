<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       
        $users = DB::table('tipo_usuarios')
            ->join('users', 'tipo_usuarios.id', '=', 'user.user_id')
            ->select('users.*','user_id.tipo' )
            ->get();
            return response()->json(['Usuarios'=>$users]);
    }






    public function login(Request $request){
        $validateUsuario = Validator::make(
            $request->all(),
            [
                'email' => 'required',
                'password' => 'required'
            ]
        );
        if ($validateUsuario->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validaciones requeridas',
                'errors' => $validateUsuario->errors()
            ], 401);
        }

        if (!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'status' => false,
                'message' => 'Credenciales incorrectas',
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        return response()->json([
            'usuario' =>  $user,
            'message' => 'Usuario logeado correctamente',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], 200);
    }


    ////////////////////////////////////////////////////////////
    public function registar(Request $request){
        $validateUsuario = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                "user_id"=> "required"
            ]
        );
        if ($validateUsuario->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validaciones requeridas',
                'errors' => $validateUsuario->errors()
            ], 401);
        }

        $usuario = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'user_id' => $request->user_id,
        ]);

         // Creación del token para el usuario recién creado
        $token = $usuario->createToken("API TOKEN")->plainTextToken;

        // Respuesta JSON con el usuario creado y el token
        return response()->json([
            'usuario' => $usuario,
            'message' => 'Usuario creado correctamente',
            'token' => $token
        ], 201);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user=User::find($id);
        if(!$user){
          return response()->json(['Message'=>"usuario no encontrado"],404);
  
        }
        return response()->json(['Usuarios'=>$user],200);
  
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
