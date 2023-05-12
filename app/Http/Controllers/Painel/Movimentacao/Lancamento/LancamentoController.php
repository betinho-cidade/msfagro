<?php

namespace App\Http\Controllers\Painel\Movimentacao\Lancamento;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Empresa;
use App\Models\Produtor;
use App\Models\Categoria;
use App\Models\Lancamento;
use App\Models\Movimentacao;
use App\Models\FormaPagamento;
use App\Models\Fazenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Movimentacao\Lancamento\CreateRequest;
use App\Http\Requests\Movimentacao\Lancamento\UpdateRequest;
use Carbon\Carbon;



class LancamentoController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }


    public function index(Request $request)
    {
        if(Gate::denies('view_lancamento')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        if($user->cliente->tipo != 'AG'){
            $lancamentos = Lancamento::where('lancamentos.cliente_id', $user->cliente->id)
                                        ->leftJoin('movimentacaos', 'movimentacaos.lancamento_id', '=', 'lancamentos.id')
                                        ->groupBy(DB::raw('concat(LPAD(MONTH(lancamentos.data_programada), 2, 0), \'-\', YEAR(lancamentos.data_programada))'))
                                        ->select(DB::raw('concat(YEAR(lancamentos.data_programada), LPAD(MONTH(lancamentos.data_programada), 2, 0)) AS mes_ordem,
                                                        concat(LPAD(MONTH(lancamentos.data_programada), 2, 0), \'-\', YEAR(lancamentos.data_programada)) AS mes_referencia,
                                                        count( lancamentos.id ) AS total,
                                                        SUM(CASE WHEN lancamentos.tipo = (\'CP\') THEN 1 ELSE 0 END) as compra,
                                                        SUM(CASE WHEN lancamentos.tipo = (\'VD\') THEN 1 ELSE 0 END) as venda,
                                                        SUM(CASE WHEN lancamentos.tipo = (\'EG\') THEN 1 ELSE 0 END) as engorda'))
                                        ->orderBy('lancamentos.data_programada', 'desc')
                                        ->get();
        }

        return view('painel.movimentacao.lancamento.index', compact('user', 'lancamentos'));
    }


    public function list_MG(Request $request)
    {

        if(Gate::denies('list_lancamento')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('lancamento.index');
        }

        $mes_referencia = ($request->has('mes_referencia') ? $request->mes_referencia : null);

        if(!$mes_referencia){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente ao mês de referência informado.');

            return redirect()->route('lancamento.index');
        }

        $data_programada_vetor = explode('-', $mes_referencia);

        setlocale(LC_ALL, 'pt_BR.utf-8', 'ptb', 'pt_BR', 'portuguese-brazil', 'portuguese-brazilian', 'bra', 'brazil', 'br');
        $data_programada = Carbon::createFromDate($data_programada_vetor[1], $data_programada_vetor[0])->formatLocalized('%B/%Y');

        $lancamentos_MG = ($user->cliente->tipo != 'AG') ?
                            Lancamento::where('cliente_id', $user->cliente->id)
                            ->where('segmento', 'MG')
                            ->whereYear('data_programada', $data_programada_vetor[1])
                            ->whereMonth('data_programada', $data_programada_vetor[0])
                            ->orderBy('data_programada', 'asc')
                            ->get() : [];

        return view('painel.movimentacao.lancamento.index_list_MG', compact('user', 'mes_referencia', 'data_programada', 'lancamentos_MG'));
    }


    public function create(Request $request)
    {
        if(Gate::denies('create_lancamento')){
            abort('403', 'Página não disponível');
        }

        $segmento = ($request->has('segmento') ? $request->segmento : null);

        if(!$segmento || ($segmento != 'MF' && $segmento != 'MG')){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente ao segmento de Efetivo Pecuário / Movimentação Fiscal.');

            return redirect()->route('lancamento.index');
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        $produtors = Produtor::where('cliente_id', $user->cliente->id)
                                            ->where('status', 'A')
                                            ->orderBy('nome', 'asc')
                                            ->get();

        $forma_pagamentos = FormaPagamento::where('cliente_id', $user->cliente->id)
                                            ->where('status', 'A')
                                            ->orderBy('produtor_id', 'desc')
                                            ->orderBy('tipo_conta', 'asc')
                                            ->get();

        $empresas = Empresa::where('cliente_id', $user->cliente->id)
                                            ->where('status', 'A')
                                            ->orderBy('nome', 'asc')
                                            ->get();


        if($segmento == 'MG' && $user->cliente->tipo != 'AG'){

            $categorias = Categoria::where('segmento', 'MG')
                                    ->where('status', 'A')
                                    ->orderBy('nome', 'asc')
                                    ->get();

            $fazendas = Fazenda::where('cliente_id', $user->cliente->id)
                                    ->where('status', 'A')
                                    ->orderBy('nome', 'asc')
                                    ->get();

            return view('painel.movimentacao.lancamento.create_MG', compact('user', 'produtors', 'forma_pagamentos', 'empresas', 'categorias', 'fazendas'));
        }
        if($segmento == 'MF'){

            $categorias = Categoria::where('segmento', 'MF')
                                    ->where('status', 'A')
                                    ->orderBy('nome', 'asc')
                                    ->get();

            return view('painel.movimentacao.lancamento.create_MF', compact('user', 'produtors', 'forma_pagamentos', 'empresas', 'categorias'));
        }

        return redirect()->route('lancamento.index');
    }


    public function store_MG(CreateRequest $request)
    {

        if(Gate::denies('create_lancamento')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        $message = '';

        try {

            DB::beginTransaction();

            $lancamento = new Lancamento();

            $lancamento->cliente_id = $user->cliente->id;
            $lancamento->data_programada = $request->data_programada;
            $lancamento->segmento = 'MG';
            $lancamento->tipo = $request->tipo;
            $lancamento->empresa_id = ($request->has('empresa')) ? $request->empresa : null;
            $lancamento->origem_id = ($request->has('origem')) ? $request->origem : null;
            $lancamento->destino_id = ($request->has('destino')) ? $request->destino : null;
            $lancamento->categoria_id = $request->categoria;
            $lancamento->item_macho = ($request->has('item_macho')) ? $request->item_macho : null;
            $lancamento->qtd_macho = ($request->has('qtd_macho')) ? $request->qtd_macho : 0;
            $lancamento->item_femea = ($request->has('item_femea')) ? $request->item_femea : null;
            $lancamento->qtd_femea = ($request->has('qtd_femea')) ? $request->qtd_femea : 0;
            $lancamento->gta = $request->gta;
            $lancamento->observacao = $request->observacao;

            $lancamento->save();

            if ($request->path_gta) {

                $path_gta = 'documentos/'. $user->cliente->id . '/gtas/';

                $nome_arquivo = 'GTA_'.$lancamento->id.'.'.$request->path_gta->getClientOriginalExtension();
                $lancamento->path_gta = $nome_arquivo;
                $lancamento->save();

                Storage::putFileAs($path_gta, $request->file('path_gta'), $nome_arquivo);
            }

            // =================================================================
            // INSERIR MOVIMENTAÇÃO FISCAL PARA LANÇAMENTOS DE COMPRA OU VENDA
            // =================================================================

            if($lancamento->tipo == 'CP' || $lancamento->tipo == 'VD'){

                if($lancamento->tipo == 'CP'){
                    $tipo = 'D';
                } else if($lancamento->tipo == 'VD'){
                    $tipo = 'R';
                }

                $movimentacao = new Movimentacao();

                $movimentacao->lancamento_id = $lancamento->id;
                $movimentacao->cliente_id = $user->cliente->id;
                $movimentacao->produtor_id = $request->produtor;
                $movimentacao->forma_pagamento_id = $request->forma_pagamento;
                $movimentacao->data_programada = $lancamento->data_programada;
                $movimentacao->segmento = 'MG';
                $movimentacao->tipo = $tipo;
                $movimentacao->valor = $request->valor;
                $movimentacao->nota = $request->nota;
                $movimentacao->situacao = $request->path_comprovante ? 'PG' : 'PD';
                $movimentacao->item_texto = $lancamento->texto_lancamento;

                $movimentacao->save();

                if ($request->path_nota) {

                    $path_nota = 'documentos/'. $user->cliente->id . '/notas/';

                    $nome_arquivo = 'NOTA_'.$movimentacao->id.'.'.$request->path_nota->getClientOriginalExtension();
                    $movimentacao->path_nota = $nome_arquivo;
                    $movimentacao->save();

                    Storage::putFileAs($path_nota, $request->file('path_nota'), $nome_arquivo);
                }

                if ($request->path_comprovante) {

                    $path_comprovante = 'documentos/'. $user->cliente->id . '/comprovantes/';

                    $nome_arquivo = 'COMPROVANTE_'.$movimentacao->id.'.'.$request->path_comprovante->getClientOriginalExtension();
                    $movimentacao->path_comprovante = $nome_arquivo;
                    $movimentacao->save();

                    Storage::putFileAs($path_comprovante, $request->file('path_comprovante'), $nome_arquivo);
                }
            }

            // =================================================================
            // Atualizar Estoque da Fazenda
            // =================================================================

            $this->atualizaEstoque($lancamento, false);

            DB::commit();

        } catch (Exception $ex){

            DB::rollBack();

            $message = "Erro desconhecido, por gentileza, entre em contato com o administrador. " . $ex->getMessage();

        }

        if ($message && $message !='') {
            $request->session()->flash('message.level', 'danger');
            $request->session()->flash('message.content', $message);
        } else {
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'O lancamento <code class="highlighter-rouge">'. $lancamento->nome .'</code> foi criado com sucesso');
        }

        return redirect()->route('lancamento.index');
    }


    public function show_MG(lancamento $lancamento, Request $request)
    {

        if(Gate::denies('edit_lancamento')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        if($user->cliente->id != $lancamento->cliente_id){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'O Lançamento não pertence ao cliente informado.');

            return redirect()->route('lancamento.index');
        }

        return view('painel.movimentacao.lancamento.show_MG', compact('user', 'lancamento'));
    }


    public function update_MG(UpdateRequest $request, Lancamento $lancamento)
    {

        if(Gate::denies('edit_lancamento')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        if($user->cliente->id != $lancamento->cliente_id){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'O Lançamento não pertence ao cliente informado.');

            return redirect()->route('lancamento.index');
        }

        if($lancamento->tipo != $request->tipo){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'O tipo de lançamento não confere com o registrado.');

            return redirect()->route('lancamento.index');
        }

        $today = Carbon::today();

        if($request->data_programada > $today && $request->path_comprovante){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'O Comprovante de Pagamento somente é permitido para data igual ou anterior a data atual.');

            return redirect()->route('lancamento.index');
        }

        $message = '';

        $ano_mes = Carbon::createFromFormat('Y-m-d', $request->data_programada);
        $mes_referencia = Str::padLeft($ano_mes->month, 2, '0') . '-' . $ano_mes->year;

        try {

            DB::beginTransaction();

            $lancamento->data_programada = $request->data_programada;
            $lancamento->observacao = $request->observacao;
            $lancamento->gta = $request->gta;

            if ($request->path_gta) {

                $path_gta = 'documentos/'. $user->cliente->id . '/gtas/';

                if($lancamento->path_gta){
                    if(Storage::exists($path_gta)) {
                        Storage::delete($path_gta . $lancamento->path_gta);
                    }
                }

                $nome_arquivo = 'GTA_'.$lancamento->id.'.'.$request->path_gta->getClientOriginalExtension();
                $lancamento->path_gta = $nome_arquivo;

                Storage::putFileAs($path_gta, $request->file('path_gta'), $nome_arquivo);
            }

            $lancamento->save();

            // =================================================================
            // ATUALIZAR MOVIMENTAÇÃO FISCAL PARA LANÇAMENTOS DE COMPRA OU VENDA
            // =================================================================

            if($lancamento->tipo == 'CP' || $lancamento->tipo == 'VD'){

                $lancamento->movimentacao->data_programada = $lancamento->data_programada;
                $lancamento->movimentacao->valor = $request->valor;
                $lancamento->movimentacao->nota = $request->nota;

                if ($request->path_nota) {

                    $path_nota = 'documentos/'. $user->cliente->id . '/notas/';

                    if($lancamento->movimentacao->path_nota){
                        if(Storage::exists($path_nota)) {
                            Storage::delete($path_nota . $lancamento->movimentacao->path_nota);
                        }
                    }

                    $nome_arquivo = 'NOTA_'.$lancamento->movimentacao->id.'.'.$request->path_nota->getClientOriginalExtension();
                    $lancamento->movimentacao->path_nota = $nome_arquivo;

                    Storage::putFileAs($path_nota, $request->file('path_nota'), $nome_arquivo);
                }

                if ($request->path_comprovante) {

                    $path_comprovante = 'documentos/'. $user->cliente->id . '/comprovantes/';

                    if($lancamento->movimentacao->path_comprovante){
                        if(Storage::exists($path_comprovante)) {
                            Storage::delete($path_comprovante . $lancamento->movimentacao->path_comprovante);
                        }
                    }

                    $nome_arquivo = 'COMPROVANTE_'.$lancamento->movimentacao->id.'.'.$request->path_comprovante->getClientOriginalExtension();
                    $lancamento->movimentacao->path_comprovante = $nome_arquivo;
                    $lancamento->movimentacao->situacao = 'PG';

                    Storage::putFileAs($path_comprovante, $request->file('path_comprovante'), $nome_arquivo);
                }

                $lancamento->movimentacao->save();
            }

            DB::commit();

        } catch (Exception $ex){

            DB::rollBack();

            $message = "Erro desconhecido, por gentileza, entre em contato com o administrador. " . $ex->getMessage();

        }

        if ($message && $message !='') {
            $request->session()->flash('message.level', 'danger');
            $request->session()->flash('message.content', $message);
        } else {
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'O Lançamento com ID <span style="color: #af1e1e;">'. $lancamento->id .'</span> foi atualizado com sucesso');
        }

        return redirect()->route('lancamento.list_MG', ['mes_referencia' => $mes_referencia]);
    }

    public function destroy_MG(Lancamento $lancamento, Request $request)
    {
        if(Gate::denies('delete_lancamento')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        if($user->cliente->id != $lancamento->cliente_id){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'O Lançamento não pertence ao cliente informado.');

            return redirect()->route('lancamento.index');
        }


        $message = '';
        $lancamento_id = $lancamento->id;

        $ano_mes = Carbon::createFromFormat('Y-m-d', $lancamento->data_programada_ajustada);
        $mes_referencia = Str::padLeft($ano_mes->month, 2, '0') . '-' . $ano_mes->year;

        try {
            DB::beginTransaction();

            $this->atualizaEstoque($lancamento, true);

            if($lancamento->movimentacao){
                $lancamento->movimentacao->delete();
            }

            $lancamento->delete();

            $this->destroy_files_MG($lancamento);

            DB::commit();

        } catch (Exception $ex){

            DB::rollBack();

            if(strpos($ex->getMessage(), 'Integrity constraint violation') !== false){
                $message = "Não foi possível excluir o registro, pois existem referências ao mesmo em outros processos.";
            } else{
                $message = "Erro desconhecido, por gentileza, entre em contato com o administrador. ".$ex->getMessage();
            }

        }

        if ($message && $message !='') {
            $request->session()->flash('message.level', 'danger');
            $request->session()->flash('message.content', $message);
        } else {
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'O Lançamento com ID <span style="color: #af1e1e;">'. $lancamento_id .'</span> foi excluído com sucesso');
        }

        return redirect()->route('lancamento.list_MG', compact('mes_referencia'));
    }

    public function destroy_list_MG(Request $request)
    {
        if(Gate::denies('delete_list_lancamento')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        $tipo = ($request->has('tipo') ? $request->tipo : null);

        if(!$tipo || ($tipo != 'CP' && $tipo != 'VD' && $tipo != 'EG')){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente ao tipo do lançamento do Efetivo Pecuário.');

            return redirect()->route('lancamento.index');
        }

        $mes_referencia = ($request->has('mes_referencia') ? $request->mes_referencia : null);

        if(!$mes_referencia){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente ao mês de referência informado.');

            return redirect()->route('lancamento.index');
        }

        $texto_tipo = '';
        switch($tipo){
            case 'CP':{
                $texto_tipo = 'COMPRA';
                break;
            }
            case 'VD':{
                $texto_tipo = 'VENDA';
                break;
            }
            case 'EG':{
                $texto_tipo = 'ENGORDA';
                break;
            }
        }

        $message = '';

        try {
            DB::beginTransaction();

            $data_programada_vetor = explode('-', $mes_referencia);

            $lancamentos_MG = ($user->cliente->tipo != 'AG') ?
                                Lancamento::where('cliente_id', $user->cliente->id)
                                ->where('segmento', 'MG')
                                ->whereYear('data_programada', $data_programada_vetor[1])
                                ->whereMonth('data_programada', $data_programada_vetor[0])
                                ->orderBy('data_programada', 'asc')
                                ->get() : [];

            foreach($lancamentos_MG as $lancamento){

                $this->atualizaEstoque($lancamento, true);

                if($lancamento->movimentacao){
                    $lancamento->movimentacao->delete();
                }

                $lancamento->delete();

                $this->destroy_files_MG($lancamento);
            }

            DB::commit();

        } catch (Exception $ex){

            DB::rollBack();

            if(strpos($ex->getMessage(), 'Integrity constraint violation') !== false){
                $message = "Não foi possível excluir os registros, pois existem referências aos mesmos em outros processos.";
            } else{
                $message = "Erro desconhecido, por gentileza, entre em contato com o administrador. ".$ex->getMessage();
            }

        }

        if ($message && $message !='') {
            $request->session()->flash('message.level', 'danger');
            $request->session()->flash('message.content', $message);
        } else {
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'Os Lançamentos do Efetivo Pecuário (<span style="color: #af1e1e;">'.$texto_tipo.'</span>) do mês de referência <span style="color: #af1e1e;">'. $mes_referencia .'</span> foram excluídos com sucesso');
        }

        return redirect()->route('lancamento.list_MG', compact('mes_referencia'));
    }

    protected function atualizaEstoque(Lancamento $lancamento, bool $desfazerLancamento){


        if($desfazerLancamento){ //Desfaz o Lançamento de quantidades de bovinos originalmente realizado.
            switch ($lancamento->tipo){
                case 'CP' : {

                    $destino = Fazenda::where('id', $lancamento->destino_id)->first();
                    $destino->qtd_macho = $destino->qtd_macho - $lancamento->qtd_macho;
                    $destino->qtd_femea = $destino->qtd_femea - $lancamento->qtd_femea;
                    $destino->save();
                    break;
                }

                case 'VD' : {

                    $origem = Fazenda::where('id', $lancamento->origem_id)->first();
                    $origem->qtd_macho = $origem->qtd_macho + $lancamento->qtd_macho;
                    $origem->qtd_femea = $origem->qtd_femea + $lancamento->qtd_femea;
                    $origem->save();
                    break;
                }

                case 'EG' : {

                    $origem = Fazenda::where('id', $lancamento->origem_id)->first();
                    $origem->qtd_macho = $origem->qtd_macho + $lancamento->qtd_macho;
                    $origem->qtd_femea = $origem->qtd_femea + $lancamento->qtd_femea;
                    $origem->save();
                    $destino = Fazenda::where('id', $lancamento->destino_id)->first();
                    $destino->qtd_macho = $destino->qtd_macho - $lancamento->qtd_macho;
                    $destino->qtd_femea = $destino->qtd_femea - $lancamento->qtd_femea;
                    $destino->save();
                    break;
                }
            }

        } else{
            switch ($lancamento->tipo){
                case 'CP' : {

                    $destino = Fazenda::where('id', $lancamento->destino_id)->first();
                    $destino->qtd_macho = $destino->qtd_macho + $lancamento->qtd_macho;
                    $destino->qtd_femea = $destino->qtd_femea + $lancamento->qtd_femea;
                    $destino->save();
                    break;
                }

                case 'VD' : {

                    $origem = Fazenda::where('id', $lancamento->origem_id)->first();
                    $origem->qtd_macho = $origem->qtd_macho - $lancamento->qtd_macho;
                    $origem->qtd_femea = $origem->qtd_femea - $lancamento->qtd_femea;
                    $origem->save();
                    break;
                }

                case 'EG' : {

                    $origem = Fazenda::where('id', $lancamento->origem_id)->first();
                    $origem->qtd_macho = $origem->qtd_macho - $lancamento->qtd_macho;
                    $origem->qtd_femea = $origem->qtd_femea - $lancamento->qtd_femea;
                    $origem->save();
                    $destino = Fazenda::where('id', $lancamento->destino_id)->first();
                    $destino->qtd_macho = $destino->qtd_macho + $lancamento->qtd_macho;
                    $destino->qtd_femea = $destino->qtd_femea + $lancamento->qtd_femea;
                    $destino->save();
                    break;
                }
            }
        }
    }

    protected function destroy_files_MG(Lancamento $lancamento){

        $path_gta = 'documentos/'. $lancamento->cliente->id . '/gtas/';
        if($lancamento->path_gta){
            if(Storage::exists($path_gta)) {
                Storage::delete($path_gta . $lancamento->path_gta);
            }
        }

        $path_nota = 'documentos/'. $lancamento->cliente->id . '/notas/';
        if($lancamento->movimentacao && $lancamento->movimentacao->path_nota){
            if(Storage::exists($path_nota)) {
                Storage::delete($path_nota . $lancamento->movimentacao->path_nota);
            }
        }

        $path_comprovante = 'documentos/'. $lancamento->cliente->id . '/comprovantes/';
        if($lancamento->movimentacao && $lancamento->movimentacao->path_comprovante){
            if(Storage::exists($path_comprovante)) {
                Storage::delete($path_comprovante . $lancamento->movimentacao->path_comprovante);
            }
        }

    }

    public function refreshList(Request $request)
    {

        if(Gate::denies('view_lancamento')){
            return redirect()->route('logout');
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        if(!$request->has('tipo') || $request->tipo == null){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Processo para atualização não identificado.');

            return redirect()->route('painel');
        }

        $mensagem = [];
        $tipo = $request->tipo;

        switch($tipo) {
            case 'EP': {
                $empresas = Empresa::where('cliente_id', $user->cliente->id)
                                    ->where('status', 'A')
                                    ->orderBy('nome', 'asc')
                                    ->get();

                foreach($empresas as $empresa)
                {
                    $list['id'] = $empresa->id;
                    $list['nome'] = $empresa->nome_empresa;
                    array_push($mensagem, $list);
                }

                break;
            }

            case 'FP': {
                $forma_pagamentos = FormaPagamento::where('cliente_id', $user->cliente->id)
                                                    ->where('status', 'A')
                                                    ->orderBy('produtor_id', 'desc')
                                                    ->orderBy('tipo_conta', 'asc')
                                                    ->get();

                foreach($forma_pagamentos as $forma_pagamento)
                {
                    $list['id'] = $forma_pagamento->id;
                    $list['nome'] = $forma_pagamento->forma;
                    array_push($mensagem, $list);
                }

                break;
            }

            case 'PT': {
                $produtors = Produtor::where('cliente_id', $user->cliente->id)
                                        ->where('status', 'A')
                                        ->orderBy('nome', 'asc')
                                        ->get();

                foreach($produtors as $produtor)
                {
                    $list['id'] = $produtor->id;
                    $list['nome'] = $produtor->nome_produtor;
                    array_push($mensagem, $list);
                }

                break;
            }

            case 'OR' || 'DT': {
                $fazendas = Fazenda::where('cliente_id', $user->cliente->id)
                                    ->where('status', 'A')
                                    ->orderBy('nome', 'asc')
                                    ->get();

                foreach($fazendas as $fazenda)
                {
                    $list['id'] = $fazenda->id;
                    $list['nome'] = $fazenda->nome_fazenda;
                    array_push($mensagem, $list);
                }

                break;
            }
        }

        return response()->json(['mensagem' => $mensagem]);
    }

    public function download(Lancamento $lancamento, Request $request){

        if(Gate::denies('edit_lancamento')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        if($user->cliente->id != $lancamento->cliente_id){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'O Lançamento não pertence ao cliente informado.');

            return redirect()->route('lancamento.index');
        }

        $tipo_documento = ($request->has('tipo_documento') ? $request->tipo_documento : null);

        if(!$tipo_documento){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível encontrar o tipo de documento solicitado.');

            return redirect()->route('lancamento.index');
        }

        $path_documento = 'documentos/' . $user->cliente->id . '/';

        switch($tipo_documento){
            case 'CP':{
                $path_documento = $path_documento . 'comprovantes/' . $lancamento->movimentacao->path_comprovante;
                break;
            }
            case 'GT':{
                $path_documento = $path_documento . 'gtas/' . $lancamento->path_gta;
                break;
            }
            case 'NT':{
                $path_documento = $path_documento . 'notas/' . $lancamento->movimentacao->path_nota;
                break;
            }
        }

        return Storage::download($path_documento);
    }

}
