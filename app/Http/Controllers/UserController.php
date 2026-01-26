<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $usuarios = User::when($request->has('filtro'), function ($query) use ($request) {
                $filtros = explode(';', $request->filtro);
                foreach ($filtros as $filtro) {
                    $c = explode(':', $filtro);
                    if (isset($c[2])) {
                        if ($c[1] == 'like') {
                            $query->where($c[0], 'like', '%' . $c[2] . '%');
                        } else {
                            $query->where($c[0], $c[1], $c[2]);
                        }
                    }
                }
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return response()->json($usuarios);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json($user, 201);
    }

    public function show($id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['erro' => 'Usuário não encontrado'], 404);
        }
        
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['erro' => 'Usuário não encontrado'], 404);
        }
        
        $validacao = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ];
        
        if ($request->has('password') && $request->password) {
            $validacao['password'] = ['required', 'confirmed', Rules\Password::defaults()];
        }
        
        $request->validate($validacao);
        
        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->has('password') && $request->password) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();
        
        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['erro' => 'Usuário não encontrado'], 404);
        }
        
        // Evitar que o próprio usuário se delete
        if (auth()->id() == $user->id) {
            return response()->json(['erro' => 'Você não pode remover sua própria conta'], 403);
        }
        
        $user->delete();
        
        return response()->json(['msg' => 'Usuário removido com sucesso']);
    }
}