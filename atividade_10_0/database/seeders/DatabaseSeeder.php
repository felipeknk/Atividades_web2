<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Criar um usuário admin padrão
        User::create([
            'name' => 'Admin',
            'email' => 'admin@biblioteca.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Opcional: usuário bibliotecário
        User::create([
            'name' => 'Bibliotecário',
            'email' => 'bibliotecario@biblioteca.com',
            'password' => Hash::make('senha123'),
            'role' => 'bibliotecario',
        ]);

        // Opcional: usuário cliente
        User::create([
            'name' => 'Cliente',
            'email' => 'cliente@biblioteca.com',
            'password' => Hash::make('senha123'),
            'role' => 'cliente',
        ]);

        // Rodar os outros seeders normalmente
        $this->call([
            CategorySeeder::class,
            AuthorPublisherBookSeeder::class,
            UserBorrowingSeeder::class,
        ]);
    }
}
