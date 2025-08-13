<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10); // Paginação para 10 usuários por página
        return view('users.index', compact('users'));
    }

    public function show(User $user)
    {
        // Recupera os livros emprestados pelo usuário com os relacionamentos
        $borrowings = $user->books()
            ->with(['author', 'publisher', 'category'])
            ->withPivot('borrowed_at', 'returned_at', 'id')
            ->get();

        // Recupera todos os usuários para popular o select na view
        $users = User::all();

        return view('users.show', compact('user', 'borrowings', 'users'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $user->update($request->only('name', 'email'));

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso.');
    }
}
