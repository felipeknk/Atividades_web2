<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book;
use App\Models\Borrowing;

class BorrowingController extends Controller
{
    public function store(Request $request, Book $book)
    {
        $user = auth()->user();

        // 🔹 Impede empréstimo se houver débito
        if ($user->debit > 0) {
            return redirect()->back()
                ->withErrors('Você possui débitos pendentes. Regularize antes de fazer um novo empréstimo.');
        }

        // 🔹 Verifica se o livro já está emprestado
        $emprestimoAberto = Borrowing::where('book_id', $book->id)
            ->whereNull('returned_at')
            ->exists();

        if ($emprestimoAberto) {
            return redirect()->back()
                ->withErrors('Este livro já está emprestado e ainda não foi devolvido.');
        }

        // 🔹 Verifica limite de 5 livros
        $emprestimosAtivos = Borrowing::where('user_id', $user->id)
            ->whereNull('returned_at')
            ->count();

        if ($emprestimosAtivos >= 5) {
            return redirect()->back()
                ->withErrors('Você já possui 5 livros emprestados. Devolva um antes de pegar outro.');
        }

        // 🔹 Registra o empréstimo
        Borrowing::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrowed_at' => now(),
        ]);

        return redirect()
            ->route('books.show', $book)
            ->with('success', 'Empréstimo registrado com sucesso.');
    }

    public function returnBook(Borrowing $borrowing)
    {
        $hoje = now();
        $diasEmprestado = $borrowing->borrowed_at->diffInDays($hoje);
        $diasPermitidos = 15;
        $multaPorDia = 0.50;

        // 🔹 Calcula multa se houver atraso
        if ($diasEmprestado > $diasPermitidos) {
            $diasAtraso = $diasEmprestado - $diasPermitidos;
            $multa = $diasAtraso * $multaPorDia;

            // Atualiza o débito do usuário
            $user = $borrowing->user;
            $user->debit += $multa;
            $user->save();
        }

        // 🔹 Marca devolução
        $borrowing->update([
            'returned_at' => $hoje,
        ]);

        return redirect()
            ->route('books.show', $borrowing->book_id)
            ->with('success', 'Devolução registrada com sucesso.');
    }

    public function userBorrowings(User $user)
    {
        $borrowings = $user->books()->withPivot('borrowed_at', 'returned_at')->get();
        return view('users.borrowings', compact('user', 'borrowings'));
    }
}