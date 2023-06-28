<?php

namespace App\Http\Controllers\Painel\Lancamento\Movimentacao;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Empresa;
use App\Models\Produtor;
use App\Models\Categoria;
use App\Models\Movimentacao;
use App\Models\FormaPagamento;
use App\Models\Fazenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Lancamento\Movimentacao\CreateRequest;
use App\Http\Requests\Lancamento\Movimentacao\UpdateRequest;
use Carbon\Carbon;



class MovimentacaoController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

        if(Gate::denies('list_movimentacao')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('lancamento.index', ['aba' => 'MF']);
        }

        $mes_referencia = ($request->has('mes_referencia') ? $request->mes_referencia : null);

        if(!$mes_referencia){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente ao mês de referência informado.');

            return redirect()->route('lancamento.index', ['aba' => 'MF']);
        }

        $data_programada_vetor = explode('-', $mes_referencia);

        setlocale(LC_ALL, 'pt_BR.utf-8', 'ptb', 'pt_BR', 'portuguese-brazil', 'portuguese-brazilian', 'bra', 'brazil', 'br');
        $data_programada = Carbon::createFromDate($data_programada_vetor[1], $data_programada_vetor[0])->formatLocalized('%B/%Y');

        $movimentacaos = Movimentacao::where('cliente_id', $user->cliente->id)
                                    ->where('segmento', 'MF')
                                    ->whereYear('data_programada', $data_programada_vetor[1])
                                    ->whereMonth('data_programada', $data_programada_vetor[0])
                                    ->orderBy('data_programada', 'asc')
                                    ->get();

        return view('painel.lancamento.movimentacao.index', compact('user', 'mes_referencia', 'data_programada', 'movimentacaos'));
    }

    public function create(Request $request)
    {
        if(Gate::denies('create_movimentacao')){
            abort('403', 'Página não disponível');
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

        $categorias = Categoria::where('segmento', 'MF')
                                ->where('status', 'A')
                                ->orderBy('nome', 'asc')
                                ->get();


        return view('painel.lancamento.movimentacao.create', compact('user', 'produtors', 'forma_pagamentos', 'empresas', 'categorias'));
    }

    public function store(CreateRequest $request)
    {
        if(Gate::denies('create_movimentacao')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        if($request->has('data_pagamento')){

            $today = Carbon::today();

            if($request->data_pagamento > $today){
                $request->session()->flash('message.level', 'warning');
                $request->session()->flash('message.content', 'A Data de Pagamento não pode ser maior que a data atual.');
    
                return redirect()->back()->withInput();
            }    
        }           

        $message = '';

        try {

            DB::beginTransaction();

            $movimentacao = new Movimentacao();

            $movimentacao->cliente_id = $user->cliente->id;
            $movimentacao->empresa_id = $request->empresa;
            $movimentacao->produtor_id = $request->produtor;
            $movimentacao->forma_pagamento_id = $request->forma_pagamento;
            $movimentacao->categoria_id = $request->categoria;
            $movimentacao->data_programada = $request->data_programada;
            $movimentacao->data_pagamento = $request->data_pagamento;
            $movimentacao->segmento = 'MF';
            $movimentacao->tipo = $request->tipo;
            $movimentacao->valor = $request->valor;
            $movimentacao->nota = $request->nota;
            $movimentacao->situacao = $request->path_comprovante ? 'PG' : 'PD';
            $movimentacao->item_texto = $request->item_texto;
            $movimentacao->observacao = $request->observacao;

            $movimentacao->save();

            // =================================================================
            // ARMAZENA OS ARQUIVOS - GTA, NOTA E COMPROVANTE PAGAMENTO
            // =================================================================

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
            $request->session()->flash('message.content', 'O Lançamento de Movimentação Fiscal ('.$movimentacao->tipo_movimentacao_texto.') com ID <span style="color: #af1e1e;">'. $movimentacao->id .'</span> foi criado com sucesso');
        }

        return redirect()->route('lancamento.index', ['aba' => 'MF']);
    }

    public function show(Movimentacao $movimentacao, Request $request)
    {

        if(Gate::denies('edit_movimentacao')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        if($user->cliente->id != $movimentacao->cliente_id){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'O movimentacao Pecuário não pertence ao cliente informado.');

            return redirect()->route('lancamento.index', ['aba' => 'MF']);
        }

        $empresas = Empresa::where('cliente_id', $user->cliente->id)
                                            ->where('status', 'A')
                                            ->orderBy('nome', 'asc')
                                            ->get();

        return view('painel.lancamento.movimentacao.show', compact('user', 'movimentacao', 'empresas'));
    }

    public function update(UpdateRequest $request, Movimentacao $movimentacao)
    {

        if(Gate::denies('edit_movimentacao')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        if($user->cliente->id != $movimentacao->cliente_id){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'A Movimentação Fiscal não pertence ao cliente informado.');

            return redirect()->route('lancamento.index', ['aba' => 'MF']);
        }

        if($movimentacao->tipo != $request->tipo){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'O tipo de Movimentação não confere com o registrado.');

            return redirect()->route('lancamento.index', ['aba' => 'MF']);
        }

        $today = Carbon::today();


        if($request->has('data_pagamento') && $request->data_pagamento){

            if($request->data_pagamento > $today){
                $request->session()->flash('message.level', 'warning');
                $request->session()->flash('message.content', 'A Data de Pagamento não pode ser maior que a data atual.');
    
                return redirect()->back()->withInput();
            }    

            if(!$request->has('path_comprovante') && !$movimentacao->path_comprovante){
                $request->session()->flash('message.level', 'warning');
                $request->session()->flash('message.content', 'O Comprovante de Pagamento é requerido com a Data de Pagamento.');
    
                return redirect()->back()->withInput();
            }                
        }     

        if(!$request->data_pagamento && ($request->path_comprovante || $movimentacao->path_comprovante) ){
            if($request->tipo != 'EG'){
                $request->session()->flash('message.level', 'warning');
                $request->session()->flash('message.content', 'A Data de Pagamento é requerida com o Comprovante de Pagamento.');

                return redirect()->route('movimentacao.show', compact('movimentacao'));
            }
        }                

        if(!$movimentacao->path_nota && !$request->path_nota){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'A Nota Fiscal é requerida');

            return redirect()->route('movimentacao.show', compact('movimentacao'));
        }


        $message = '';

        $ano_mes = Carbon::createFromFormat('Y-m-d', $request->data_programada);
        $mes_referencia = Str::padLeft($ano_mes->month, 2, '0') . '-' . $ano_mes->year;

        try {

            DB::beginTransaction();

            $movimentacao->data_programada = $request->data_programada;
            $movimentacao->data_pagamento = $request->data_pagamento;
            $movimentacao->empresa_id = $request->empresa;
            $movimentacao->item_texto = $request->item_texto;
            $movimentacao->observacao = $request->observacao;
            $movimentacao->valor = $request->valor;
            $movimentacao->nota = $request->nota;

            $movimentacao->save();

            // =================================================================
            // ATUALIZA/ARMAZENA OS ARQUIVOS - NOTA E COMPROVANTE PAGAMENTO
            // =================================================================

            if ($request->path_nota) {

                $path_nota = 'documentos/'. $user->cliente->id . '/notas/';

                if($movimentacao->path_nota){
                    if(Storage::exists($path_nota)) {
                        Storage::delete($path_nota . $movimentacao->path_nota);
                    }
                }

                $nome_arquivo = 'NOTA_'.$movimentacao->id.'.'.$request->path_nota->getClientOriginalExtension();
                $movimentacao->path_nota = $nome_arquivo;
                $movimentacao->save();

                Storage::putFileAs($path_nota, $request->file('path_nota'), $nome_arquivo);
            }

            if ($request->path_comprovante) {

                $path_comprovante = 'documentos/'. $user->cliente->id . '/comprovantes/';

                if($movimentacao->path_comprovante){
                    if(Storage::exists($path_comprovante)) {
                        Storage::delete($path_comprovante . $movimentacao->path_comprovante);
                    }
                }

                $nome_arquivo = 'COMPROVANTE_'.$movimentacao->id.'.'.$request->path_comprovante->getClientOriginalExtension();
                $movimentacao->path_comprovante = $nome_arquivo;
                $movimentacao->situacao = 'PG';
                $movimentacao->save();

                Storage::putFileAs($path_comprovante, $request->file('path_comprovante'), $nome_arquivo);
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
            $request->session()->flash('message.content', 'A Movimentação Fiscal ('.$movimentacao->tipo_movimentacao_texto.') com ID <span style="color: #af1e1e;">'. $movimentacao->id .'</span> foi atualizada com sucesso');
        }

        return redirect()->route('movimentacao.index', ['mes_referencia' => $mes_referencia]);
    }

    public function destroy(Movimentacao $movimentacao, Request $request)
    {
        if(Gate::denies('delete_movimentacao')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        if($user->cliente->id != $movimentacao->cliente_id){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'A Movimentação Fiscal não pertence ao cliente informado.');

            return redirect()->route('lancamento.index', ['aba' => 'MF']);
        }

        $message = '';
        $movimentacao_id = $movimentacao->id;
        $movimentacao_tipo_texto = $movimentacao->tipo_movimentacao_texto;

        $ano_mes = Carbon::createFromFormat('Y-m-d', $movimentacao->data_programada_ajustada);
        $mes_referencia = Str::padLeft($ano_mes->month, 2, '0') . '-' . $ano_mes->year;

        try {
            DB::beginTransaction();

            $movimentacao_arquivos = [];
            $movimentacao_arquivos[0]['cliente_id'] = $movimentacao->cliente_id;
            $movimentacao_arquivos[0]['movimentacao_id'] = $movimentacao->id;
            $movimentacao_arquivos[0]['path_nota'] = $movimentacao->path_nota;
            $movimentacao_arquivos[0]['path_comprovante'] = $movimentacao->path_comprovante;

            $movimentacao->delete();

            $this->destroy_files($movimentacao_arquivos);

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
            $request->session()->flash('message.content', 'A Movimentação Fiscal ('.$movimentacao_tipo_texto.') com ID <span style="color: #af1e1e;">'. $movimentacao_id .'</span> foi excluída com sucesso');
        }

        return redirect()->route('movimentacao.index', compact('mes_referencia'));
    }

    public function destroy_list(Request $request)
    {
        if(Gate::denies('delete_list_movimentacao')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        $tipo = ($request->has('tipo') ? $request->tipo : null);

        if(!$tipo || ($tipo != 'R' && $tipo != 'D')){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente ao tipo de Movimentação Fiscal.');

            return redirect()->route('lancamento.index', ['aba' => 'MF']);
        }

        $mes_referencia = ($request->has('mes_referencia') ? $request->mes_referencia : null);

        if(!$mes_referencia){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente ao mês de referência informado.');

            return redirect()->route('lancamento.index', ['aba' => 'MF']);
        }

        $texto_tipo = '';
        switch($tipo){
            case 'R':{
                $texto_tipo = 'RECEITA';
                break;
            }
            case 'D':{
                $texto_tipo = 'DESPESA';
                break;
            }
        }

        $message = '';

        try {
            DB::beginTransaction();

            $data_programada_vetor = explode('-', $mes_referencia);

            $movimentacaos = Movimentacao::where('cliente_id', $user->cliente->id)
                                        ->where('segmento', 'MF')
                                        ->whereYear('data_programada', $data_programada_vetor[1])
                                        ->whereMonth('data_programada', $data_programada_vetor[0])
                                        ->orderBy('data_programada', 'asc')
                                        ->get();

            $movimentacao_arquivos = [];
            $contArquivos = 0;

            foreach($movimentacaos as $movimentacao){

                if($user->cliente->id != $movimentacao->cliente_id){
                    $request->session()->flash('message.level', 'warning');
                    $request->session()->flash('message.content', 'A Movimentação FIscal não pertence ao cliente informado.');

                    DB::rollBack();
                    return redirect()->route('lancamento.index', ['aba' => 'MF']);
                }

                $movimentacao_arquivos[$contArquivos]['cliente_id'] = $movimentacao->cliente_id;
                $movimentacao_arquivos[$contArquivos]['movimentacao_id'] = $movimentacao->id;
                $movimentacao_arquivos[$contArquivos]['path_nota'] = $movimentacao->path_nota;
                $movimentacao_arquivos[$contArquivos]['path_comprovante'] = $movimentacao->path_comprovante;

                $movimentacao->delete();
                $contArquivos++;
            }

            $this->destroy_files($movimentacao_arquivos);

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
            $request->session()->flash('message.content', 'As Movimentações Fiscais (<span style="color: #af1e1e;">'.$texto_tipo.'</span>) do mês de referência <span style="color: #af1e1e;">'. $mes_referencia .'</span> foram excluídas com sucesso');
        }

        return redirect()->route('movimentacao.index', compact('mes_referencia'));
    }

    protected function destroy_files(Array $movimentacao_arquivos){

        foreach($movimentacao_arquivos as $movimentacao){

            $path_nota = 'documentos/'. $movimentacao['cliente_id'] . '/notas/';
            if($movimentacao['movimentacao_id'] != 0 && $movimentacao['path_nota']){
                if(Storage::exists($path_nota)) {
                    Storage::delete($path_nota . $movimentacao['path_nota']);
                }
            }

            $path_comprovante = 'documentos/'. $movimentacao['cliente_id'] . '/comprovantes/';
            if($movimentacao['movimentacao_id'] != 0 && $movimentacao['path_comprovante']){
                if(Storage::exists($path_comprovante)) {
                    Storage::delete($path_comprovante . $movimentacao['path_comprovante']);
                }
            }
        }
    }

    public function download(Movimentacao $movimentacao, Request $request){

        if(Gate::denies('view_movimentacao')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        if($user->cliente->id != $movimentacao->cliente_id){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'A Movimentação Fiscal não pertence ao cliente informado.');

            return redirect()->route('lancamento.index', ['aba' => 'MF']);
        }

        $tipo_documento = ($request->has('tipo_documento') ? $request->tipo_documento : null);

        if(!$tipo_documento){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível encontrar o tipo de documento solicitado.');

            return redirect()->route('lancamento.index', ['aba' => 'MF']);
        }

        $path_documento = 'documentos/' . $user->cliente->id . '/';

        switch($tipo_documento){
            case 'CP':{
                $path_documento = $path_documento . 'comprovantes/' . $movimentacao->path_comprovante;
                break;
            }
            case 'NT':{
                $path_documento = $path_documento . 'notas/' . $movimentacao->path_nota;
                break;
            }
        }

        return Storage::download($path_documento);
    }
}
