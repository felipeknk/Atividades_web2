@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Detalhes do Livro -->
@forelse($borrowings as $book)
    <div class="card mb-4">
        <div class="card-header">Detalhes do Livro</div>
        <div class="card-body">
            <h5 class="card-title">{{ $book->title }}</h5>
            <p class="card-text"><strong>Autor:</strong> {{ $book->author->name }}</p>
            <p class="card-text"><strong>Editora:</strong> {{ $book->publisher->name }}</p>
            <p class="card-text"><strong>Categoria:</strong> {{ $book->category->name }}</p>
            <p class="card-text"><strong>Data de Empréstimo:</strong> {{ $book->pivot->borrowed_at }}</p>
            <p class="card-text"><strong>Data de Devolução:</strong> {{ $book->pivot->returned_at ?? 'Em aberto' }}</p>
        </div>
    </div>
@empty
    <p>Este usuário não possui livros emprestados.</p>
@endforelse


    <!-- Formulário para Empréstimos -->
    <div class="card mb-4">
        <div class="card-header">Registrar Empréstimo</div>
        <div class="card-body">
            <form action="{{ route('books.borrow', $book) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="user_id" class="form-label">Usuário</label>
                    <select class="form-select" id="user_id" name="user_id" required>
                        <option value="" selected disabled>Selecione um usuário</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Registrar Empréstimo</button>
            </form>
        </div>
    </div>

    <!-- Histórico de Empréstimos -->
    <div class="card">
        <div class="card-header">Histórico de Empréstimos</div>
        <div class="card-body">
            @if($book->users->isEmpty())
                <p>Nenhum empréstimo registrado.</p>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Usuário</th>
                            <th>Data de Empréstimo</th>
                            <th>Data de Devolução</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($book->users as $user)
                            <tr>
                                <td>
                                    <a href="{{ route('users.show', $user->id) }}">
                                        {{ $user->name }}
                                    </a>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($user->pivot->borrowed_at)->format('d/m/Y H:i') }}</td>
                                <td>
                                    {{ $user->pivot->returned_at ? \Carbon\Carbon::parse($user->pivot->returned_at)->format('d/m/Y H:i') : 'Em Aberto' }}
                                </td>
                                <td>
                                    @if(is_null($user->pivot->returned_at))
                                        <form action="{{ route('borrowings.return', $user->pivot->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-warning btn-sm">Devolver</button>
                                        </form>
                                    @else
                                        <span class="text-success">Devolvido</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
