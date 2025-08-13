<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FineController extends Controller
{
    /**
     * Lista todos os usuários com multas ativas.
     */
    public function index()
    {
        $usersWithFines = User::where('debit', '>', 0)->get();

        return view('fines.index', compact('usersWithFines'));
    }

    /**
     * Zera a multa de um usuário.
     */
    public function clear(User $user)
    {
        $user->debit = 0;
        $user->save();

        return redirect()
            ->route('fines.index')
            ->with('success', "Multa do usuário {$user->name} foi quitada com sucesso.");
    }
}