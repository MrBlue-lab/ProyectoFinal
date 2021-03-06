<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RolUsuario;

class AuthController extends Controller {

    public function register(Request $request) {

        if (User::where('email', '=', $request->input('email'))->count() == 1) {
            return response()->json(['message' => ['correcto' => false, 'message' => 'Registro incorrecto. Revise las credenciales'], 'code' => 400], 400);
        }

        $validatedData = $request->validate([
            'nombre' => 'required',
            'apellidos' => 'required',
            'email' => 'email|required|unique:users',
            'password' => 'required',
            'estado' => 'required'
        ]);

        $validatedData['password'] = \Hash::make($request->input("password"));

        $user = User::create($validatedData);
        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json(['message' => ['correcto' => true, 'user' => $user, 'access_token' => $accessToken], 'code' => 201], 201);
    }

    /**
     * Registro profesor dirigido a la tabla persona
     * @param Request $request
     * @return json
     */
    /*
    public function register_persona(Request $request) {
        if (Persona::where('dni', $request->input('dni'))->count() == 1) {
            $persona = Persona::where('dni', $request->input('dni'))->first();
            $persona->dni = $request->input("dni");
            $persona->nombre = $request->input("nombre");
            $persona->apellidos = $request->input("apellidos");
            $persona->localidad = $request->input("localidad");
            $persona->residencia = $request->input("residencia");
            $persona->correo = $request->input("correo");
            $persona->tlf = $request->input("tlf");
            $persona->save();
            RolUsuario::create([
                'role_id' => $request->input("rol"),
                'user_dni' => $request->input("dni")
            ]);

            return response()->json(['message' => ['user' => $persona], 'code' => 201], 201);
        }
        $validatedData = [
            'dni' => $request->input("dni"),
            'apellidos' => $request->input("apellidos"),
            'nombre' => $request->input("nombre"),
            'localidad' => $request->input("localidad"),
            'residencia' => $request->input("residencia"),
            'correo' => $request->input("correo"),
            'tlf' => $request->input("tlf"),
            'foto' => 0
        ];
        $persona = Persona::create($validatedData);
        RolUsuario::create([
            'role_id' => 5,
            'user_dni' => $request->input("dni")
        ]);

        RolUsuario::create([
            'role_id' => $request->input("rol"),
            'user_dni' => $request->input("dni")
        ]);

        return response()->json(['message' => ['user' => $persona], 'code' => 201], 201);
    }
*/
    /**
     * funcion para comprobar si una persona existe, si existe devuelve los datos si no null.
     * @param Request $request
     * @return type
     *//*
    public function isPersona(Request $request) {
        if (Persona::where('dni', $request->input('dni'))->count() == 1) {
            $persona = Persona::where('dni', $request->input('dni'))->first();
            return response()->json(['message' => ['persona' => $persona], 'code' => 201], 201);
        } else {
            return response()->json(['message' => ['persona' => null], 'code' => 201], 201);
        }
    }
*/
    /**
     * Update profesor
     * @param Request $request
     * @return json
     *//*
    public function mod_user(Request $request) {
        if (Persona::where('dni', $request->input('olddni'))->count() != 1) {
            return response()->json(['message' => 'datos no encontrados', 'code' => 201], 201);
        }
        $persona = Persona::where('dni', $request->input('olddni'))->first();
        $persona->apellidos = $request->input("apellidos");
        $persona->nombre = $request->input("nombre");
        $persona->localidad = $request->input("localidad");
        $persona->residencia = $request->input("residencia");
        $persona->correo = $request->input("correo");
        $persona->tlf = $request->input("tlf");
        $persona->save();
        if ($request->input('olddni') != $request->input('dni')) {
            if (User::where('dni', '=', $request->input('olddni'))->count() == 1) {
                $user = User::where('dni', '=', $request->input('olddni'))->first();
                $cursos = Curso::where('dniTutor', '=', $request->input('olddni'))->get();
                $rol = RolUsuario::where('user_dni', '=', $request->input('olddni'))->first();
                RolUsuario::where('user_dni', $request->input('olddni'))->delete();
                foreach ($cursos as $curso) {
                    $curso->dniTutor = null;
                    $curso->save();
                }
                $user->dni = $request->input('dni');
                $user->save();
                foreach ($cursos as $curso) {
                    $curso->dniTutor = $request->input('dni');
                    $curso->save();
                }
                $persona->dni = $request->input('dni');
                $persona->save();
                RolUsuario::create([
                    'role_id' => $rol->role_id,
                    'user_dni' => $request->input('dni')
                ]);
            }
        }
        return response()->json(['message' => ['user' => $persona], 'code' => 201], 201);
    }
*/
    /**
     * Funci??n para cambiar contrase??a
     * @param Request $request
     * @return type
     */
    public function mod_user_pass(Request $request) {
        $user = User::where('email', $request->input('email'))->first();

        if (\Hash::check($request->input("password"), $user->password)) {
            return response()->json(['message' => 'Contrase??a incorrectas. Revise las credenciales.', 'code' => 400], 400);
        }

        $user->password = \Hash::make($request->input("newpassword"));
        $user->save();

        return response()->json(['message' => ['user' => $user], 'code' => 201], 201);
    }

    /**
     * funci??n para modificar email
     * @param Request $request
     * @return type
     */
    public function mod_user_email(Request $request) {
        if (User::where('email', $request->input('email'))->count() != 1) {
            return response()->json(['message' => 'Revise las credenciales.', 'code' => 400], 400);
        }

        if (User::where('email', $request->input('newemail'))->count() == 1) {
            return response()->json(['message' => 'El email ya existe', 'code' => 400], 400);
        }

        $user = User::where('email', $request->input('email'))->first();
        $user->email = $request->input("newemail");
        $user->save();

        return response()->json(['message' => ['user' => $user], 'code' => 201], 201);
    }

    public function login(Request $request) {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);
        
        if (!auth()->attempt($loginData,true)) {
            //return response(['message' => 'Login incorrecto. Revise las credenciales.'], 400);
            return response()->json(['message' => 'Login incorrecto. Revise las credenciales.', 'code' => 400], 400);
        }

        //Comprobar que la cuenta este activada
        $usu = User::where('email', '=', $request->input('email'))
                ->where('estado', '=', 0)
                ->get();
        if (count($usu) == 0) {
            return response()->json(['message' => 'Cuenta desactivada, contacte con el director.', 'code' => 400], 400);
        }
        $accessToken = auth()->user()->createToken('authToken')->accessToken;
        //Buscamos el dni del email introducido para posteriormente buscarlo en personas; ya que puede tener un correo diferente al registrarse
        //que el que tiene registrado en la BD
        $user = User::select('id')
                ->where('email', '=', $request->input('email'))
                ->get();
        //cambiando aqui***********************************************

        //Obtener el rol del usuario
        /*
        $rol = RolUsuario::where("user_dni", "=", $persona->dni)->get();
    
        if ($rol[0]->role_id == 1) {
            $rolDescripcion = "Director";
        } else if ($rol[1]->role_id == 2) {
            $rolDescripcion = "Jefe de estudios";
        } else if ($rol[1]->role_id == 3) {
            $rolDescripcion = "Tutor";
        }*/
        return response()->json(['message' => ['user' => auth()->user(), 'access_token' => $accessToken], 'code' => 200], 200);
    }
}
