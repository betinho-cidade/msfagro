<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{

    public function run()
    {
        if(DB::table('permissions')->get()->count() == 0){

            DB::table('permissions')->insert([
                [
                    'id' => 1,
                    'name' => 'view_usuario',
                    'description' => 'Visualizar as informações do usuário do sistema MSFagro',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 2,
                    'name' => 'edit_usuario',
                    'description' => 'Editar as informações do usuário do sistema MSFagro',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 3,
                    'name' => 'create_usuario',
                    'description' => 'Criar um novo usuário do sistema MSFagro',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 4,
                    'name' => 'delete_usuario',
                    'description' => 'Excluir o usuário do sistema MSFagro',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 5,
                    'name' => 'view_painel',
                    'description' => 'Visualizar as informações do Painel do Gestor',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 6,
                    'name' => 'view_categoria',
                    'description' => 'Visualizar as informações das categorias',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 7,
                    'name' => 'edit_categoria',
                    'description' => 'Editar as informações das categorias',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 8,
                    'name' => 'create_categoria',
                    'description' => 'Criar uma nova categoria',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 9,
                    'name' => 'delete_categoria',
                    'description' => 'Excluir uma categoria',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 10,
                    'name' => 'view_aliquota',
                    'description' => 'Visualizar as informações das alíquotas',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 11,
                    'name' => 'edit_aliquota',
                    'description' => 'Editar as informações das alíquotas',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 12,
                    'name' => 'create_aliquota',
                    'description' => 'Criar uma nova alíquota',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 13,
                    'name' => 'delete_aliquota',
                    'description' => 'Excluir uma alíquota',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 14,
                    'name' => 'view_cliente',
                    'description' => 'Visualizar as informações dos clientes',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 15,
                    'name' => 'edit_cliente',
                    'description' => 'Editar as informações dos clientes',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 16,
                    'name' => 'create_cliente',
                    'description' => 'Criar um novo cliente',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 17,
                    'name' => 'delete_cliente',
                    'description' => 'Excluir um cliente',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 18,
                    'name' => 'view_dashboard',
                    'description' => 'Visualizar as informações do Dashboard',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 19,
                    'name' => 'view_usuario_logado',
                    'description' => 'Visualizar informações do usuário logado',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 20,
                    'name' => 'view_forma_pagamento',
                    'description' => 'Visualizar as informações das formas de pagamento',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 21,
                    'name' => 'edit_forma_pagamento',
                    'description' => 'Editar as informações das formas de pagamento',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 22,
                    'name' => 'create_forma_pagamento',
                    'description' => 'Criar uma nova forma de pagamento',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 23,
                    'name' => 'delete_forma_pagamento',
                    'description' => 'Excluir uma forma de pagamento',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 24,
                    'name' => 'view_fazenda',
                    'description' => 'Visualizar as informações das fazendas',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 25,
                    'name' => 'edit_fazenda',
                    'description' => 'Editar as informações das fazendas',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 26,
                    'name' => 'create_fazenda',
                    'description' => 'Criar uma nova fazenda',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 27,
                    'name' => 'delete_fazenda',
                    'description' => 'Excluir uma fazenda',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 28,
                    'name' => 'view_empresa',
                    'description' => 'Visualizar as informações das empresas',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 29,
                    'name' => 'edit_empresa',
                    'description' => 'Editar as informações das empresas',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 30,
                    'name' => 'create_empresa',
                    'description' => 'Criar uma nova empresa',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 31,
                    'name' => 'delete_empresa',
                    'description' => 'Excluir uma empresa',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 32,
                    'name' => 'view_produtor',
                    'description' => 'Visualizar as informações dos produtores',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 33,
                    'name' => 'edit_produtor',
                    'description' => 'Editar as informações dos produtores',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 34,
                    'name' => 'create_produtor',
                    'description' => 'Criar um novo produtor',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 35,
                    'name' => 'delete_produtor',
                    'description' => 'Excluir um produtor',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 36,
                    'name' => 'view_lancamento',
                    'description' => 'Visualizar as informações dos lançamentos',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 37,
                    'name' => 'edit_lancamento',
                    'description' => 'Editar as informações dos lançamentos',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 38,
                    'name' => 'create_lancamento',
                    'description' => 'Criar um novo lançamento',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 39,
                    'name' => 'delete_lancamento',
                    'description' => 'Excluir um lançamento',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 40,
                    'name' => 'list_lancamento',
                    'description' => 'Listar o resumo mensal dos lançamentos',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 41,
                    'name' => 'delete_list_lancamento',
                    'description' => 'Excluir a lista mensal dos lançamentos',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
            ]);

        } else { echo "\e[31mTabela Permissions não está vazia. "; }

    }

}
