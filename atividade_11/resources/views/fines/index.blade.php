@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Multas Pendentes</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($usersWithFines->isEmpty())
        <p>Nenhum usuário possui multas no momento.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>Email</th>
                    <th>Valor da Multa</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usersWithFines as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>R$ {{ number_format($user->debit, 2, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('fines.clear', $user) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">
                                    Quitar Multa
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
