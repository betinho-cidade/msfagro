<?php

namespace App\Http\Controllers\Painel\Movimentacao\Lancamento;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Lancamento;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
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

         $lancamentos = Lancamento::where('cliente_id', $user->cliente->id)
                                    ->groupBy(DB::raw('concat(LPAD(MONTH(lancamentos.data_criacao), 2, 0), \'-\', YEAR(lancamentos.data_criacao))'))
                                    ->groupBy(DB::raw('concat(YEAR(lancamentos.data_criacao), LPAD(MONTH(lancamentos.data_criacao), 2, 0))'))
                                    ->select(DB::raw('concat(LPAD(MONTH(lancamentos.data_criacao), 2, 0), \'-\', YEAR(lancamentos.data_criacao)) AS mes_referencia,
                                                      SUM(CASE WHEN lancamentos.segmento = (\'MG\') THEN 1 ELSE 0 END) as movimentacao_bovina,
                                                      SUM(CASE WHEN lancamentos.segmento = (\'MF\') THEN 1 ELSE 0 END) as movimentacao_fiscal,
                                                      count( lancamentos.id ) AS total'))
                                    ->get();

        return view('painel.movimentacao.lancamento.index', compact('user', 'lancamentos'));
    }


    public function list(Request $request)
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

        $data_criacao_vetor = explode('-', $mes_referencia);

        setlocale(LC_ALL, 'pt_BR.utf-8', 'ptb', 'pt_BR', 'portuguese-brazil', 'portuguese-brazilian', 'bra', 'brazil', 'br');
        $data_criacao = Carbon::createFromDate($data_criacao_vetor[1], $data_criacao_vetor[0])->formatLocalized('%B/%Y');

        $lancamentos_MG = Lancamento::where('cliente_id', $user->cliente->id)
                            ->where('segmento', 'MG')
                            ->whereYear('data_criacao', $data_criacao_vetor[1])
                            ->whereMonth('data_criacao', $data_criacao_vetor[0])
                            ->orderBy('data_criacao', 'asc')
                            ->get();


        $lancamentos_MF = Lancamento::where('cliente_id', $user->cliente->id)
                            ->where('segmento', 'MF')
                            ->whereYear('data_criacao', $data_criacao_vetor[1])
                            ->whereMonth('data_criacao', $data_criacao_vetor[0])
                            ->orderBy('data_criacao', 'asc')
                            ->get();

        return view('painel.movimentacao.lancamento.index_list', compact('user', 'mes_referencia', 'data_criacao', 'lancamentos_MG', 'lancamentos_MF'));
    }



    public function create(Request $request)
    {
        if(Gate::denies('create_lancamento')){
            abort('403', 'Página não disponível');
        }

        $segmento = ($request->has('segmento') ? $request->segmento : null);

        if(!$segmento || ($segmento != 'MF' && $segmento != 'MG')){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente ao segmento de Movimentação de Bovinos / Movimentação Fiscais.');

            return redirect()->route('lancamento.index');
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        if($segmento == 'MG'){
            return view('painel.movimentacao.lancamento.create_MG', compact('user'));
        }
        if($segmento == 'MF'){
            return view('painel.movimentacao.lancamento.create_MF', compact('user'));
        }
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

            $lancamento = new lancamento();

            $lancamento->cliente_id = $user->cliente->id;
            $lancamento->nome = $request->nome;
            $lancamento->email = $request->email;
            $lancamento->tipo_pessoa = $request->tipo_pessoa;
            $lancamento->cpf_cnpj = $request->cpf_cnpj;
            $lancamento->telefone = $request->telefone;
            $lancamento->inscricao_estadual = $request->inscricao_estadual;
            $lancamento->end_cep = $request->end_cep;
            $lancamento->end_cidade = $request->end_cidade;
            $lancamento->end_uf = $request->end_uf;
            $lancamento->end_logradouro = $request->end_logradouro;
            $lancamento->end_numero = $request->end_numero;
            $lancamento->end_bairro = $request->end_bairro;
            $lancamento->end_complemento = $request->end_complemento;
            $lancamento->status = $request->situacao;

            $lancamento->save();

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


    public function show(lancamento $lancamento, Request $request)
    {

        if(Gate::denies('edit_lancamento')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente || ($user->cliente->id != $lancamento->cliente_id) ){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'O lancamento não pertence ao cliente informado.');

            return redirect()->route('lancamento.index');
        }

        return view('painel.movimentacao.lancamento.show', compact('user', 'lancamento'));
    }


    public function update(UpdateRequest $request, lancamento $lancamento)
    {
        if(Gate::denies('edit_lancamento')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente || ($user->cliente->id != $lancamento->cliente_id) ){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'O lancamento não pertence ao cliente informado.');

            return redirect()->route('lancamento.index');
        }

        $message = '';

        try {

            DB::beginTransaction();

            $lancamento->nome = $request->nome;
            $lancamento->email = $request->email;
            $lancamento->tipo_pessoa = $request->tipo_pessoa;
            $lancamento->cpf_cnpj = $request->cpf_cnpj;
            $lancamento->telefone = $request->telefone;
            $lancamento->inscricao_estadual = $request->inscricao_estadual;
            $lancamento->end_cep = $request->end_cep;
            $lancamento->end_cidade = $request->end_cidade;
            $lancamento->end_uf = $request->end_uf;
            $lancamento->end_logradouro = $request->end_logradouro;
            $lancamento->end_numero = $request->end_numero;
            $lancamento->end_bairro = $request->end_bairro;
            $lancamento->end_complemento = $request->end_complemento;
            $lancamento->status = $request->situacao;

            $lancamento->save();

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
            $request->session()->flash('message.content', 'O lancamento <code class="highlighter-rouge">'. $lancamento->nome .'</code> foi alterado com sucesso');
        }

        return redirect()->route('lancamento.index');
    }


    public function destroy(Lancamento $lancamento, Request $request)
    {
        if(Gate::denies('delete_lancamento')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente ||($user->cliente->id != $lancamento->cliente_id) ){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'O lancamento não pertence ao cliente informado.');

            return redirect()->route('lancamento.index');
        }

        $mes_referencia = ($request->has('mes_referencia') ? $request->mes_referencia : null);

        if(!$mes_referencia){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente ao mês de referência informado.');

            return redirect()->route('lancamento.index');
        }


        $message = '';
        $lancamento_nome = $lancamento->id;

        try {
            DB::beginTransaction();

            $lancamento->delete();

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
            $request->session()->flash('message.content', 'O lancamento com identificador/ID (<code class="highlighter-rouge"> '. $lancamento_nome .' </code>) foi excluído com sucesso');
        }

        return redirect()->route('lancamento.list', compact('mes_referencia'));
    }

    public function list_destroy(Request $request)
    {
        if(Gate::denies('delete_list_lancamento')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('lancamento.index');
        }

        $segmento = ($request->has('segmento') ? $request->segmento : null);

        if(!$segmento){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente ao segmento do mês informado.');

            return redirect()->route('lancamento.index');
        }

        $mes_referencia = ($request->has('mes_referencia') ? $request->mes_referencia : null);

        if(!$mes_referencia){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente ao mês de referência informado.');

            return redirect()->route('lancamento.index');
        }

        $message = '';

        $data_criacao_vetor = explode('-', $mes_referencia);

        setlocale(LC_ALL, 'pt_BR.utf-8', 'ptb', 'pt_BR', 'portuguese-brazil', 'portuguese-brazilian', 'bra', 'brazil', 'br');
        $data_criacao = Carbon::createFromDate($data_criacao_vetor[1], $data_criacao_vetor[0])->formatLocalized('%B/%Y');

        try {
            DB::beginTransaction();

            Lancamento::where('cliente_id', $user->cliente->id)
                        ->where('segmento', $segmento)
                        ->whereYear('data_criacao', $data_criacao_vetor[1])
                        ->whereMonth('data_criacao', $data_criacao_vetor[0])
                        ->delete();

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
            $request->session()->flash('message.content', 'Os Lançamentos do mês de referência <code class="highlighter-rouge">'. $data_criacao .'</code> foram excluídos com sucesso');
        }

        return redirect()->route('lancamento.list', compact('mes_referencia'));
    }

}
