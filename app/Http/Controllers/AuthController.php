<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    //

    public function login(Request $request)
    { 
        $credenciasis = $request->all('email', 'password');
        //Autenticação (email e senha)
        $token =  auth('api')->attempt($credenciasis);
        if($token){// usuário autenticado com sucesso
          return response()->json(['token' => $token]);
        }else{// erro de usu[ario ou senha
           return response()->json(['erro' => 'Usuário ou senha inválidos'], 403);
        }

         //retorna um token JWT

        return 'login';
    }

      public function logout()
    {
        auth('api')->logout();
        return response()->json(['msg' => 'Logout foi realizado com sucesso!']);
    }

      public function refresh()
    {
       $token = auth('api')->refresh();
       return response()->json(['token' => $token]);
    }

      public function me()
      {
          return response()->json(auth()->user());
      }
}
