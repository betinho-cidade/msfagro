<?php

namespace App\Http\Controllers\Painel\Lancamento\Efetivo;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Empresa;
use App\Models\Produtor;
use App\Models\Categoria;
use App\Models\Efetivo;
use App\Models\Movimentacao;
use App\Models\FormaPagamento;
use App\Models\Fazenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Lancamento\Efetivo\CreateRequest;
use App\Http\Requests\Lancamento\Efetivo\UpdateRequest;
use Carbon\Carbon;
use App\Http\Middleware\GenerateSafeSubmitToken;
use App\Http\Middleware\HandleSafeSubmit;
use App\SafeSubmit\SafeSubmit;

class EfetivoController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->middleware(GenerateSafeSubmitToken::class)->only('create');
        $this->middleware(HandleSafeSubmit::class)->only('store');
    }

    public function index(Request $request)
    {

        if(Gate::denies('list_efetivo')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente_user){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('lancamento.index');
        }

        if($user->cliente_user->cliente->tipo == 'AG'){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Lançamentos permitidos somente para o perfil Pecuarista.');

            return redirect()->route('painel');
        }

        $mes_referencia = ($request->has('mes_referencia') ? $request->mes_referencia : null);

        if(!$mes_referencia){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente ao mês de referência informado.');

            return redirect()->route('lancamento.index', ['aba' => 'EP']);
        }

        $data_programada_vetor = explode('-', $mes_referencia);

        setlocale(LC_ALL, 'pt_BR.utf-8', 'ptb', 'pt_BR', 'portuguese-brazil', 'portuguese-brazilian', 'bra', 'brazil', 'br');
        $data_programada = Carbon::createFromDate($data_programada_vetor[1], $data_programada_vetor[0])->formatLocalized('%B/%Y');

        // $efetivos = Efetivo::where('cliente_id', $user->cliente_user->cliente->id)
        //                         ->where('segmento', 'MG')
        //                         ->whereYear('data_programada', $data_programada_vetor[1])
        //                         ->whereMonth('data_programada', $data_programada_vetor[0])
        //                         ->orderBy('data_programada', 'asc')
        //                         ->get();

        $efetivos = Efetivo::leftJoin('movimentacaos', 'movimentacaos.efetivo_id', '=', 'efetivos.id')
                                ->where('efetivos.cliente_id', $user->cliente_user->cliente->id)
                                ->where('efetivos.segmento', 'MG')
                                ->whereRaw(DB::raw('
                                                CASE WHEN efetivos.tipo in (\'EG\')
                                                    THEN
                                                        YEAR(efetivos.data_programada) = "'.$data_programada_vetor[1].'"
                                                    ELSE
                                                        YEAR(COALESCE(movimentacaos.data_pagamento, efetivos.data_programada)) = "'.$data_programada_vetor[1].'"
                                                    END
                                                '))
                                ->whereRaw(DB::raw('
                                                CASE WHEN efetivos.tipo in (\'EG\')
                                                    THEN
                                                        MONTH(efetivos.data_programada) = "'.$data_programada_vetor[0].'"
                                                    ELSE
                                                        MONTH(COALESCE(movimentacaos.data_pagamento, efetivos.data_programada)) = "'.$data_programada_vetor[0].'"
                                                    END
                                                '))
                                ->orderBy(DB::raw('
                                                    CASE WHEN efetivos.tipo in (\'EG\')
                                                        THEN `efetivos`.`data_programada`
                                                        ELSE COALESCE(movimentacaos.data_pagamento, efetivos.data_programada)
                                                    END
                                                '), 'desc')
                                ->select('efetivos.*')
                                ->get();

        return view('painel.lancamento.efetivo.index', compact('user', 'mes_referencia', 'data_programada', 'efetivos'));
    }

    public function create(Request $request)
    {
        if(Gate::denies('create_efetivo')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente_user){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        if($user->cliente_user->cliente->tipo == 'AG'){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Lançamentos permitidos somente para o perfil Pecuarista.');

            return redirect()->route('painel');
        }


        $produtors = Produtor::where('cliente_id', $user->cliente_user->cliente->id)
                                            ->where('status', 'A')
                                            ->orderBy('nome', 'asc')
                                            ->get();

        $forma_pagamentos = FormaPagamento::where('cliente_id', $user->cliente_user->cliente->id)
                                            ->where('status', 'A')
                                            ->orderBy('produtor_id', 'desc')
                                            ->orderBy('tipo_conta', 'asc')
                                            ->get();

        $empresas = Empresa::where('cliente_id', $user->cliente_user->cliente->id)
                                            ->where('status', 'A')
                                            ->orderBy('nome', 'asc')
                                            ->get();

        $categorias = Categoria::where('segmento', 'MG')
                                ->where('status', 'A')
                                ->orderBy('nome', 'asc')
                                ->get();

        $fazendas = Fazenda::where('cliente_id', $user->cliente_user->cliente->id)
                                ->where('status', 'A')
                                ->orderBy('nome', 'asc')
                                ->get();

        return view('painel.lancamento.efetivo.create', compact('user', 'produtors', 'forma_pagamentos', 'empresas', 'categorias', 'fazendas'));
    }

    public function store(CreateRequest $request, SafeSubmit $safeSubmit)
    {

        if(Gate::denies('create_efetivo')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente_user){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        if($user->cliente_user->cliente->tipo == 'AG'){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Lançamentos permitidos somente para o perfil Pecuarista.');

            return redirect()->route('painel');
        }

        if($request->has('data_pagamento')){
            if($request->tipo == 'EG'){
                $request->session()->flash('message.level', 'warning');
                $request->session()->flash('message.content', 'A Data de Pagamento não é permitida para movimentações de Engorda.');

                return redirect()->route('painel');
            }

            $today = Carbon::today();
            if($request->data_pagamento > $today){
                $request->session()->flash('message.level', 'warning');
                $request->session()->flash('message.content', 'A Data de Pagamento não pode ser maior que a data atual.');

                return redirect()->back()->withInput();
            }
        }

        $message = '';

        $ano_mes = Carbon::createFromFormat('Y-m-d', $request->data_programada);
        $mes_referencia = Str::padLeft($ano_mes->month, 2, '0') . '-' . $ano_mes->year;

        try {

            DB::beginTransaction();

            $efetivo = new Efetivo();

            $efetivo->cliente_id = $user->cliente_user->cliente->id;
            $efetivo->data_programada = $request->data_programada;
            $efetivo->segmento = 'MG';
            $efetivo->tipo = $request->tipo;
            $efetivo->empresa_id = ($request->has('empresa')) ? $request->empresa : null;
            $efetivo->origem_id = ($request->has('origem')) ? $request->origem : null;
            $efetivo->destino_id = ($request->has('destino')) ? $request->destino : null;
            $efetivo->categoria_id = $request->categoria;
            $efetivo->item_macho = ($request->has('item_macho')) ? $request->item_macho : null;
            $efetivo->qtd_macho = ($request->has('qtd_macho')) ? $request->qtd_macho : 0;
            $efetivo->item_femea = ($request->has('item_femea')) ? $request->item_femea : null;
            $efetivo->qtd_femea = ($request->has('qtd_femea')) ? $request->qtd_femea : 0;
            $efetivo->gta = $request->gta;
            $efetivo->observacao = $request->observacao;

            $efetivo->save();

            // =================================================================
            // INSERIR MOVIMENTAÇÃO FISCAL PARA LANÇAMENTOS DE COMPRA OU VENDA
            // =================================================================

            if($efetivo->tipo == 'CP' || $efetivo->tipo == 'VD'){

                if($efetivo->tipo == 'CP'){
                    $tipo = 'D';
                } else if($efetivo->tipo == 'VD'){
                    $tipo = 'R';
                }

                $movimentacao = new Movimentacao();

                $movimentacao->efetivo_id = $efetivo->id;
                $movimentacao->cliente_id = $user->cliente_user->cliente->id;
                $movimentacao->empresa_id = $efetivo->empresa_id;
                $movimentacao->produtor_id = $request->produtor;
                $movimentacao->forma_pagamento_id = $request->forma_pagamento;
                $movimentacao->categoria_id = $efetivo->categoria_id;
                $movimentacao->data_programada = $efetivo->data_programada;
                $movimentacao->data_pagamento = $request->data_pagamento;
                $movimentacao->segmento = 'MG';
                $movimentacao->tipo = $tipo;
                $movimentacao->valor = $request->valor;
                $movimentacao->nota = $request->nota;
                $movimentacao->situacao = $request->path_comprovante ? 'PG' : 'PD';
                $movimentacao->item_texto = $efetivo->texto_efetivo;

                $movimentacao->save();

                if($movimentacao->situacao == 'PD' && $movimentacao->tipo == 'D') $movimentacao->create_notification();

            }

            // =================================================================
            // Atualizar Estoque da Fazenda
            // =================================================================

            $retorno_estoque = $this->atualizaEstoque($efetivo, false);

            if($retorno_estoque && $retorno_estoque != 'SALDO_OK') {
                $request->session()->flash('message.level', 'danger');
                $request->session()->flash('message.content', $retorno_estoque);

                DB::rollBack();

                return redirect()->route('lancamento.index', ['aba' => 'EP']);
            }

            // =================================================================
            // ARMAZENA OS ARQUIVOS - GTA, NOTA E COMPROVANTE PAGAMENTO
            // =================================================================

            if ($request->path_gta) {

                $path_gta = 'documentos/'. $user->cliente_user->cliente->id . '/gtas/';

                $nome_arquivo = 'GTA_'.$efetivo->id.'.'.$request->path_gta->getClientOriginalExtension();
                $efetivo->path_gta = $nome_arquivo;
                $efetivo->save();

                Storage::putFileAs($path_gta, $request->file('path_gta'), $nome_arquivo);
            }

            if($request->tipo != 'EG'){

                if ($request->path_nota) {

                    $path_nota = 'documentos/'. $user->cliente_user->cliente->id . '/notas/';

                    $nome_arquivo = 'NOTA_'.$movimentacao->id.'.'.$request->path_nota->getClientOriginalExtension();
                    $movimentacao->path_nota = $nome_arquivo;
                    $movimentacao->save();

                    Storage::putFileAs($path_nota, $request->file('path_nota'), $nome_arquivo);
                }

                if ($request->path_comprovante) {

                    $path_comprovante = 'documentos/'. $user->cliente_user->cliente->id . '/comprovantes/';

                    $nome_arquivo = 'COMPROVANTE_'.$movimentacao->id.'.'.$request->path_comprovante->getClientOriginalExtension();
                    $movimentacao->path_comprovante = $nome_arquivo;
                    $movimentacao->situacao = 'PG';

                    if($movimentacao->tipo == 'D') {
                        $movimentacao->delete_notification();
                    }

                    $movimentacao->save();

                    Storage::putFileAs($path_comprovante, $request->file('path_comprovante'), $nome_arquivo);
                }

                if ($request->path_anexo) {

                    $path_anexo = 'documentos/'. $user->cliente_user->cliente->id . '/anexos/';

                    $nome_arquivo = 'ANEXO_'.$movimentacao->id.'.'.$request->path_anexo->getClientOriginalExtension();
                    $movimentacao->path_anexo = $nome_arquivo;

                    $movimentacao->save();

                    Storage::putFileAs($path_anexo, $request->file('path_anexo'), $nome_arquivo);
                }
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
            $request->session()->flash('message.content', 'O Lançamento de Efetivo Pecuário ('.$efetivo->tipo_efetivo_texto.') com ID <span style="color: #af1e1e;">'. $efetivo->id .'</span> foi criado com sucesso');
        }

        //return redirect()->route('lancamento.index', ['aba' => 'EP']);
        return $safeSubmit->intended(route('lancamento.index', ['aba' => 'EP']));

    }

    public function show(Efetivo $efetivo, Request $request)
    {

        if(Gate::denies('view_efetivo') && (Gate::denies('view_relatorio_gestao'))){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if((Gate::denies('view_relatorio_gestao'))){
            if(!$user->cliente_user){
                $request->session()->flash('message.level', 'warning');
                $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

                return redirect()->route('painel');
            }
        }

        if((Gate::denies('view_relatorio_gestao'))){
            if($user->cliente_user->cliente->tipo == 'AG'){
                $request->session()->flash('message.level', 'warning');
                $request->session()->flash('message.content', 'Lançamentos permitidos somente para o perfil Pecuarista.');

                return redirect()->route('painel');
            }
        }

        if((Gate::denies('view_relatorio_gestao'))){
            if($user->cliente_user->cliente->id != $efetivo->cliente_id){
                $request->session()->flash('message.level', 'warning');
                $request->session()->flash('message.content', 'O Efetivo Pecuário não pertence ao cliente informado.');

                return redirect()->route('lancamento.index', ['aba' => 'EP']);
            }
        }

        $forma_pagamentos = FormaPagamento::where('cliente_id', $user->cliente_user->cliente->id)
                                            ->where('status', 'A')
                                            ->orderBy('produtor_id', 'desc')
                                            ->orderBy('tipo_conta', 'asc')
                                            ->get();

        return view('painel.lancamento.efetivo.show', compact('user', 'efetivo', 'forma_pagamentos'));
    }

    public function update(UpdateRequest $request, Efetivo $efetivo)
    {

        if(Gate::denies('edit_efetivo')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente_user){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        if($user->cliente_user->cliente->tipo == 'AG'){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Lançamentos permitidos somente para o perfil Pecuarista.');

            return redirect()->route('painel');
        }

        if($user->cliente_user->cliente->id != $efetivo->cliente_id){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'O Efetivo Pecuário não pertence ao cliente informado.');

            return redirect()->route('lancamento.index', ['aba' => 'EP']);
        }

        if($efetivo->tipo != $request->tipo){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'O tipo de Efetivo Pecuário não confere com o registrado.');

            return redirect()->route('lancamento.index', ['aba' => 'EP']);
        }

        $today = Carbon::today();

        if($request->tipo != 'EG'){

            if($request->has('data_pagamento') && $request->data_pagamento){

                if($request->data_pagamento > $today){
                    $request->session()->flash('message.level', 'warning');
                    $request->session()->flash('message.content', 'A Data de Pagamento não pode ser maior que a data atual.');

                    return redirect()->back()->withInput();
                }

                if(!$request->has('path_comprovante') && !$efetivo->movimentacao->path_comprovante){
                    $request->session()->flash('message.level', 'warning');
                    $request->session()->flash('message.content', 'O Comprovante de Pagamento é requerido com a Data de Pagamento.');

                    return redirect()->back()->withInput();
                }
            }

            if(!$request->data_pagamento && ($request->path_comprovante || $efetivo->movimentacao->path_comprovante) ){
                if($request->tipo != 'EG'){
                    $request->session()->flash('message.level', 'warning');
                    $request->session()->flash('message.content', 'A Data de Pagamento é requerida com o Comprovante de Pagamento.');

                    return redirect()->route('efetivo.show', compact('efetivo'));
                }
            }
        }

        $message = '';

        $ano_mes = Carbon::createFromFormat('Y-m-d', $request->data_programada);
        $mes_referencia = Str::padLeft($ano_mes->month, 2, '0') . '-' . $ano_mes->year;

        try {

            DB::beginTransaction();

            if($request->tipo != 'EG'){
                if($efetivo->movimentacao){
                    $data_programada_old = $efetivo->movimentacao->data_programada_ajustada;
                    $item_texto_old = $efetivo->movimentacao->item_texto;
                    $valor_old = $efetivo->movimentacao->valor;
                }
            }

            $efetivo->data_programada = $request->data_programada;
            $efetivo->observacao = $request->observacao;
            $efetivo->gta = $request->gta;

            $efetivo->save();

            // =================================================================
            // ATUALIZAR MOVIMENTAÇÃO FISCAL PARA LANÇAMENTOS DE COMPRA OU VENDA
            // =================================================================

            if($efetivo->tipo == 'CP' || $efetivo->tipo == 'VD'){

                $efetivo->movimentacao->data_programada = $efetivo->data_programada;
                $efetivo->movimentacao->data_pagamento = $request->data_pagamento;
                $efetivo->movimentacao->valor = $request->valor;
                $efetivo->movimentacao->nota = $request->nota;
                $efetivo->movimentacao->forma_pagamento_id = $request->forma_pagamento;

                $efetivo->movimentacao->save();
            }

            // =================================================================
            // ATUALIZA/ARMAZENA OS ARQUIVOS - GTA, NOTA E COMPROVANTE PAGAMENTO
            // =================================================================

            if ($request->path_gta) {

                $path_gta = 'documentos/'. $user->cliente_user->cliente->id . '/gtas/';

                if($efetivo->path_gta){
                    if(Storage::exists($path_gta)) {
                        Storage::delete($path_gta . $efetivo->path_gta);
                    }
                }

                $nome_arquivo = 'GTA_'.$efetivo->id.'.'.$request->path_gta->getClientOriginalExtension();
                $efetivo->path_gta = $nome_arquivo;
                $efetivo->save();

                Storage::putFileAs($path_gta, $request->file('path_gta'), $nome_arquivo);
            }

            if($request->tipo != 'EG'){

                if ($request->path_nota) {

                    $path_nota = 'documentos/'. $user->cliente_user->cliente->id . '/notas/';

                    if($efetivo->movimentacao->path_nota){
                        if(Storage::exists($path_nota)) {
                            Storage::delete($path_nota . $efetivo->movimentacao->path_nota);
                        }
                    }

                    $nome_arquivo = 'NOTA_'.$efetivo->movimentacao->id.'.'.$request->path_nota->getClientOriginalExtension();
                    $efetivo->movimentacao->path_nota = $nome_arquivo;
                    $efetivo->movimentacao->save();

                    Storage::putFileAs($path_nota, $request->file('path_nota'), $nome_arquivo);
                }

                if ($request->path_comprovante) {

                    $path_comprovante = 'documentos/'. $user->cliente_user->cliente->id . '/comprovantes/';

                    if($efetivo->movimentacao->path_comprovante){
                        if(Storage::exists($path_comprovante)) {
                            Storage::delete($path_comprovante . $efetivo->movimentacao->path_comprovante);
                        }
                    }

                    $nome_arquivo = 'COMPROVANTE_'.$efetivo->movimentacao->id.'.'.$request->path_comprovante->getClientOriginalExtension();
                    $efetivo->movimentacao->path_comprovante = $nome_arquivo;
                    $efetivo->movimentacao->situacao = 'PG';
                    $efetivo->movimentacao->save();

                    Storage::putFileAs($path_comprovante, $request->file('path_comprovante'), $nome_arquivo);
                }

                if ($request->path_anexo) {

                    $path_anexo = 'documentos/'. $user->cliente_user->cliente->id . '/anexos/';

                    if($efetivo->movimentacao->path_anexo){
                        if(Storage::exists($path_anexo)) {
                            Storage::delete($path_anexo . $efetivo->movimentacao->path_anexo);
                        }
                    }

                    $nome_arquivo = 'ANEXO_'.$efetivo->movimentacao->id.'.'.$request->path_anexo->getClientOriginalExtension();
                    $efetivo->movimentacao->path_anexo = $nome_arquivo;
                    $efetivo->movimentacao->save();

                    Storage::putFileAs($path_anexo, $request->file('path_anexo'), $nome_arquivo);
                }

                if($efetivo->movimentacao && ($efetivo->movimentacao->data_programada != $data_programada_old ||
                    $efetivo->movimentacao->item_texto != $item_texto_old ||
                    $efetivo->movimentacao->valor != $valor_old) &&
                    $efetivo->movimentacao->situacao == 'PD' &&
                    $efetivo->movimentacao->tipo == 'D') {

                    $efetivo->movimentacao->delete_notification();
                    $efetivo->movimentacao->create_notification();
                }

                if($efetivo->movimentacao->tipo == 'D' && $efetivo->movimentacao->situacao == 'PG') {
                    $efetivo->movimentacao->delete_notification();
                }
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
            $request->session()->flash('message.content', 'O Efetivo Pecuário ('.$efetivo->tipo_efetivo_texto.') com ID <span style="color: #af1e1e;">'. $efetivo->id .'</span> foi atualizado com sucesso');
        }

        return redirect()->route('efetivo.index', ['mes_referencia' => $mes_referencia]);
    }

    // REPLICADA na controller RelatorioController (destroy_efetivo) - App\Http\Controllers\Painel\Relatorio
    public function destroy(Efetivo $efetivo, Request $request)
    {
        if(Gate::denies('delete_efetivo')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente_user){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        if($user->cliente_user->cliente->tipo == 'AG'){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Lançamentos permitidos somente para o perfil Pecuarista.');

            return redirect()->route('painel');
        }

        if($user->cliente_user->cliente->id != $efetivo->cliente_id){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'O Efetivo Pecuário não pertence ao cliente informado.');

            return redirect()->route('lancamento.index', ['aba' => 'EP']);
        }


        $message = '';
        $efetivo_id = $efetivo->id;
        $efetivo_tipo_texto = $efetivo->tipo_efetivo_texto;

        $ano_mes = Carbon::createFromFormat('Y-m-d', $efetivo->data_programada_ajustada);
        $mes_referencia = Str::padLeft($ano_mes->month, 2, '0') . '-' . $ano_mes->year;

        try {
            DB::beginTransaction();

            $retorno_estoque = $this->atualizaEstoque($efetivo, true);

            if($retorno_estoque && $retorno_estoque != 'SALDO_OK') {
                $request->session()->flash('message.level', 'danger');
                $request->session()->flash('message.content', $retorno_estoque);

                DB::rollBack();

                return redirect()->route('efetivo.index', compact('mes_referencia'));
            }

            $efetivo_arquivos = [];
            $efetivo_arquivos[0]['cliente_id'] = $efetivo->cliente_id;
            $efetivo_arquivos[0]['efetivo_id'] = $efetivo->id;
            $efetivo_arquivos[0]['movimentacao_id'] = ($efetivo->movimentacao) ? $efetivo->movimentacao->id : 0;
            $efetivo_arquivos[0]['path_gta'] = $efetivo->path_gta;

            if($efetivo->movimentacao){

                if($efetivo->movimentacao->tipo == 'D') {
                    $efetivo->movimentacao->delete_notification();
                }

                $efetivo_arquivos[0]['path_nota'] = $efetivo->movimentacao->path_nota;
                $efetivo_arquivos[0]['path_comprovante'] = $efetivo->movimentacao->path_comprovante;
                $efetivo_arquivos[0]['path_anexo'] = $efetivo->movimentacao->path_anexo;
                $efetivo->movimentacao->delete();
            }

            $efetivo->delete();

            $this->destroy_files($efetivo_arquivos);

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
            $request->session()->flash('message.content', 'O Efetivo Pecuário ('.$efetivo_tipo_texto.') com ID <span style="color: #af1e1e;">'. $efetivo_id .'</span> foi excluído com sucesso');
        }

        return redirect()->route('efetivo.index', compact('mes_referencia'));
    }

    public function destroy_list(Request $request)
    {
        if(Gate::denies('delete_list_efetivo')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente_user){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        if($user->cliente_user->cliente->tipo == 'AG'){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Lançamentos permitidos somente para o perfil Pecuarista.');

            return redirect()->route('painel');
        }

        $tipo = ($request->has('tipo') ? $request->tipo : null);

        if(!$tipo || ($tipo != 'CP' && $tipo != 'VD' && $tipo != 'EG')){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente ao tipo do Efetivo Pecuário.');

            return redirect()->route('lancamento.index', ['aba' => 'EP']);
        }

        $mes_referencia = ($request->has('mes_referencia') ? $request->mes_referencia : null);

        if(!$mes_referencia){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente ao mês de referência informado.');

            return redirect()->route('lancamento.index', ['aba' => 'EP']);
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

            $efetivos = ($user->cliente_user->cliente->tipo != 'AG') ?
                                Efetivo::where('cliente_id', $user->cliente_user->cliente->id)
                                        ->where('segmento', 'MG')
                                        ->whereYear('data_programada', $data_programada_vetor[1])
                                        ->whereMonth('data_programada', $data_programada_vetor[0])
                                        ->orderBy('data_programada', 'asc')
                                        ->get() : [];

            $efetivo_arquivos = [];
            $contArquivos = 0;

            foreach($efetivos as $efetivo){

                if($user->cliente_user->cliente->id != $efetivo->cliente_id){
                    $request->session()->flash('message.level', 'warning');
                    $request->session()->flash('message.content', 'O Efetivo Pecuário não pertence ao cliente informado.');

                    DB::rollBack();
                    return redirect()->route('lancamento.index', ['aba' => 'EP']);
                }

                $retorno_estoque = $this->atualizaEstoque($efetivo, true);

                if($retorno_estoque && $retorno_estoque != 'SALDO_OK') {
                    $request->session()->flash('message.level', 'danger');
                    $request->session()->flash('message.content', $retorno_estoque);

                    DB::rollBack();

                    return redirect()->route('efetivo.index', compact('mes_referencia'));
                }

                $efetivo_arquivos[$contArquivos]['cliente_id'] = $efetivo->cliente_id;
                $efetivo_arquivos[$contArquivos]['efetivo_id'] = $efetivo->id;
                $efetivo_arquivos[$contArquivos]['movimentacao_id'] = ($efetivo->movimentacao) ? $efetivo->movimentacao->id : 0;
                $efetivo_arquivos[$contArquivos]['path_gta'] = $efetivo->path_gta;

                if($efetivo->movimentacao){
                    $efetivo_arquivos[$contArquivos]['path_nota'] = $efetivo->movimentacao->path_nota;
                    $efetivo_arquivos[$contArquivos]['path_comprovante'] = $efetivo->movimentacao->path_comprovante;
                    $efetivo_arquivos[$contArquivos]['path_anexo'] = $efetivo->movimentacao->path_anexo;

                    if($efetivo->movimentacao->tipo == 'D') {
                        $efetivo->movimentacao->delete_notification();
                    }

                    $efetivo->movimentacao->delete();
                }

                $efetivo->delete();
                $contArquivos++;
            }

            $this->destroy_files($efetivo_arquivos);

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
            $request->session()->flash('message.content', 'Os Efetivos Pecuários (<span style="color: #af1e1e;">'.$texto_tipo.'</span>) do mês de referência <span style="color: #af1e1e;">'. $mes_referencia .'</span> foram excluídos com sucesso');
        }

        return redirect()->route('efetivo.index', compact('mes_referencia'));
    }

    // REPLICADA na controller RelatorioController (destroy_files_efetivo) - App\Http\Controllers\Painel\Relatorio
    protected function destroy_files(Array $efetivo_arquivos){

        foreach($efetivo_arquivos as $efetivo){

            $path_gta = 'documentos/'. $efetivo['cliente_id'] . '/gtas/';
            if($efetivo['path_gta']){
                if(Storage::exists($path_gta)) {
                    Storage::delete($path_gta . $efetivo['path_gta']);
                }
            }

            $path_nota = 'documentos/'. $efetivo['cliente_id'] . '/notas/';
            if($efetivo['movimentacao_id'] != 0 && $efetivo['path_nota']){
                if(Storage::exists($path_nota)) {
                    Storage::delete($path_nota . $efetivo['path_nota']);
                }
            }

            $path_comprovante = 'documentos/'. $efetivo['cliente_id'] . '/comprovantes/';
            if($efetivo['movimentacao_id'] != 0 && $efetivo['path_comprovante']){
                if(Storage::exists($path_comprovante)) {
                    Storage::delete($path_comprovante . $efetivo['path_comprovante']);
                }
            }

            $path_anexo = 'documentos/'. $efetivo['cliente_id'] . '/anexos/';
            if($efetivo['movimentacao_id'] != 0 && $efetivo['path_anexo']){
                if(Storage::exists($path_anexo)) {
                    Storage::delete($path_anexo . $efetivo['path_anexo']);
                }
            }

        }
    }

    public function download(Efetivo $efetivo, Request $request){

        if(Gate::denies('view_efetivo') && (Gate::denies('view_relatorio_gestao'))){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if((Gate::denies('view_relatorio_gestao'))){
            if(!$user->cliente_user){
                $request->session()->flash('message.level', 'warning');
                $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

                return redirect()->route('painel');
            }
        }

        if((Gate::denies('view_relatorio_gestao'))){
            if($user->cliente_user->cliente->tipo == 'AG'){
                $request->session()->flash('message.level', 'warning');
                $request->session()->flash('message.content', 'Lançamentos permitidos somente para o perfil Pecuarista.');

                return redirect()->route('painel');
            }
        }

        if((Gate::denies('view_relatorio_gestao'))){
            if($user->cliente_user->cliente->id != $efetivo->cliente_id){
                $request->session()->flash('message.level', 'warning');
                $request->session()->flash('message.content', 'O Efetivo Pecuário não pertence ao cliente informado.');

                return redirect()->route('lancamento.index', ['aba' => 'EP']);
            }
        }

        $tipo_documento = ($request->has('tipo_documento') ? $request->tipo_documento : null);

        if(!$tipo_documento){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível encontrar o tipo de documento solicitado.');

            if((Gate::denies('view_relatorio_gestao'))){
                return redirect()->route('lancamento.index', ['aba' => 'EP']);
            } else{
                return redirect()->route('painel');
            }
        }


        if((Gate::denies('view_relatorio_gestao'))){
            $path_documento = 'documentos/' . $user->cliente_user->cliente->id . '/';
        } else {
            $path_documento = 'documentos/' . $efetivo->movimentacao->cliente_id . '/';
        }

        switch($tipo_documento){
            case 'CP':{
                $path_documento = $path_documento . 'comprovantes/' . $efetivo->movimentacao->path_comprovante;
                break;
            }
            case 'GT':{
                $path_documento = $path_documento . 'gtas/' . $efetivo->path_gta;
                break;
            }
            case 'NT':{
                $path_documento = $path_documento . 'notas/' . $efetivo->movimentacao->path_nota;
                break;
            }
            case 'AN':{
                $path_documento = $path_documento . 'anexos/' . $efetivo->movimentacao->path_anexo;
                break;
            }
        }

        return Storage::download($path_documento);
    }

    // REPLICADA na controller RelatorioController (atualizaEstoque_efetivo) - App\Http\Controllers\Painel\Relatorio
    protected function atualizaEstoque(Efetivo $efetivo, bool $desfazerefetivo){

        $message = 'SALDO_OK';

        if($desfazerefetivo){ //Desfaz o Lançamento de quantidades de bovinos originalmente realizado.
            switch ($efetivo->tipo){
                case 'CP' : {

                    $destino = Fazenda::where('id', $efetivo->destino_id)->first();
                    $destino->qtd_macho = $destino->qtd_macho - $efetivo->qtd_macho;
                    $destino->qtd_femea = $destino->qtd_femea - $efetivo->qtd_femea;

                    if($destino->qtd_macho < 0){
                        $message = 'A quantidade de MACHOS na Fazenda é inferior a que está sendo movimentada. Fazenda ('.($destino->qtd_macho + $efetivo->qtd_macho).') - movimentação ('.$efetivo->qtd_macho.')';
                        return $message;
                    }

                    if($destino->qtd_femea < 0){
                        $message = 'A quantidade de FÊMEAS na Fazenda é inferior a que está sendo movimentada. Fazenda ('.($destino->qtd_femea + $efetivo->qtd_femea).') - movimentação ('.$efetivo->qtd_femea.')';
                        return $message;
                    }

                    $destino->save();
                    break;
                }

                case 'VD' : {

                    $origem = Fazenda::where('id', $efetivo->origem_id)->first();
                    $origem->qtd_macho = $origem->qtd_macho + $efetivo->qtd_macho;
                    $origem->qtd_femea = $origem->qtd_femea + $efetivo->qtd_femea;
                    $origem->save();
                    break;
                }

                case 'EG' : {

                    $origem = Fazenda::where('id', $efetivo->origem_id)->first();
                    $origem->qtd_macho = $origem->qtd_macho + $efetivo->qtd_macho;
                    $origem->qtd_femea = $origem->qtd_femea + $efetivo->qtd_femea;
                    $origem->save();

                    $destino = Fazenda::where('id', $efetivo->destino_id)->first();
                    $destino->qtd_macho = $destino->qtd_macho - $efetivo->qtd_macho;
                    $destino->qtd_femea = $destino->qtd_femea - $efetivo->qtd_femea;

                    if($destino->qtd_macho < 0){
                        $message = 'A quantidade de MACHOS na Fazenda é inferior a que está sendo movimentada. Fazenda ('.($destino->qtd_macho + $efetivo->qtd_macho).') - movimentação ('.$efetivo->qtd_macho.')';
                        return $message;
                    }

                    if($destino->qtd_femea < 0){
                        $message = 'A quantidade de FÊMEAS na Fazenda é inferior a que está sendo movimentada. Fazenda ('.($destino->qtd_femea + $efetivo->qtd_femea).') - movimentação ('.$efetivo->qtd_femea.')';
                        return $message;
                    }

                    $destino->save();
                    break;
                }
            }

        } else{
            switch ($efetivo->tipo){
                case 'CP' : {

                    $destino = Fazenda::where('id', $efetivo->destino_id)->first();
                    $destino->qtd_macho = $destino->qtd_macho + $efetivo->qtd_macho;
                    $destino->qtd_femea = $destino->qtd_femea + $efetivo->qtd_femea;
                    $destino->save();
                    break;
                }

                case 'VD' : {

                    $origem = Fazenda::where('id', $efetivo->origem_id)->first();
                    $origem->qtd_macho = $origem->qtd_macho - $efetivo->qtd_macho;
                    $origem->qtd_femea = $origem->qtd_femea - $efetivo->qtd_femea;

                    if($origem->qtd_macho < 0){
                        $message = 'A quantidade de MACHOS na Fazenda é inferior a que está sendo movimentada. Fazenda ('.($origem->qtd_macho + $efetivo->qtd_macho).') - movimentação ('.$efetivo->qtd_macho.')';
                        return $message;
                    }

                    if($origem->qtd_femea < 0){
                        $message = 'A quantidade de FÊMEAS na Fazenda é inferior a que está sendo movimentada. Fazenda ('.($origem->qtd_femea + $efetivo->qtd_femea).') - movimentação ('.$efetivo->qtd_femea.')';
                        return $message;
                    }

                    $origem->save();
                    break;
                }

                case 'EG' : {

                    $origem = Fazenda::where('id', $efetivo->origem_id)->first();
                    $origem->qtd_macho = $origem->qtd_macho - $efetivo->qtd_macho;
                    $origem->qtd_femea = $origem->qtd_femea - $efetivo->qtd_femea;

                    if($origem->qtd_macho < 0){
                        $message = 'A quantidade de MACHOS na Fazenda é inferior a que está sendo movimentada. Fazenda ('.($origem->qtd_macho + $efetivo->qtd_macho).') - movimentação ('.$efetivo->qtd_macho.')';
                        return $message;
                    }

                    if($origem->qtd_femea < 0){
                        $message = 'A quantidade de FÊMEAS na Fazenda é inferior a que está sendo movimentada. Fazenda ('.($origem->qtd_femea + $efetivo->qtd_femea).') - movimentação ('.$efetivo->qtd_femea.')';
                        return $message;
                    }

                    $origem->save();

                    $destino = Fazenda::where('id', $efetivo->destino_id)->first();
                    $destino->qtd_macho = $destino->qtd_macho + $efetivo->qtd_macho;
                    $destino->qtd_femea = $destino->qtd_femea + $efetivo->qtd_femea;
                    $destino->save();
                    break;
                }
            }
        }

        return $message;
    }
}
