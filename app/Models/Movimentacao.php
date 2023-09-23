<?php

namespace App\Models;

use App\Models\Notificacao;
use App\Models\ClienteNotificacao;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;


class Movimentacao extends Model
{

    public function efetivo()
    {
        return $this->belongsTo('App\Models\Efetivo');
    }

    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente');
    }

    public function empresa()
    {
        return $this->belongsTo('App\Models\Empresa');
    }

    public function produtor()
    {
        return $this->belongsTo('App\Models\Produtor');
    }

    public function forma_pagamento()
    { 
        return $this->belongsTo('App\Models\FormaPagamento');
    }

    public function categoria()
    {
        return $this->belongsTo('App\Models\Categoria');
    }

    public function notificacaos()
    {
        return $this->hasMany('App\Models\Notificacaos');
    }    

    public function getSegmentoTextoAttribute()
    {
        $segmento = '';

        switch($this->segmento){
            case 'MF' : {
                $segmento = 'Mov.Fiscal';
                break;
            }
            case 'MG' : {
                $segmento = 'Mov.Bovina';
                break;
            }
            default : {
                $segmento = '---';
                break;
            }
        }

        return $segmento;
    }



    public function getTipoMovimentacaoTextoAttribute()
    {
        $tipo_movimentacao = '';

        switch($this->tipo){
            case 'R' : {
                $tipo_movimentacao = 'Receita';
                break;
            }
            case 'D' : {
                $tipo_movimentacao = 'Despesa';
                break;
            }
            default : {
                $tipo_movimentacao = '---';
                break;
            }
        }

        return $tipo_movimentacao;
    }

    public function getItemTextoReduzidoAttribute()
    {
        $item_texto_reduzido =  Str::limit($this->item_texto, 15, '...');

        return $item_texto_reduzido;
    }

    public function getDataProgramadaOrdenacaoAttribute()
    {
        return ($this->data_programada) ? date('YmdHis', strtotime($this->data_programada)) : '';
    }

    public function getDataProgramadaFormatadaAttribute()
    {
        return date('d/m/Y', strtotime($this->data_programada));
    }

    public function getDataProgramadaAjustadaAttribute()
    {
        return ($this->data_programada) ? date('Y-m-d', strtotime($this->data_programada)) : '';
    }

    public function getDataPagamentoFormatadaAttribute()
    {
        return ($this->data_pagamento) ? date('d/m/Y', strtotime($this->data_pagamento)) : '...';
    }

    public function getDataPagamentoAjustadaAttribute()
    {
        return ($this->data_pagamento) ? date('Y-m-d', strtotime($this->data_pagamento)) : '';
    }

    public function getMesReferenciaListagemAttribute (){

        $mes_referencia =  Carbon::parse($this->data_programada); 

        return Str::padLeft($mes_referencia->month, 2, '0') . '-' . $mes_referencia->year;

    }    

    public function getLinkNotaAttribute()
    {
        return ($this->path_nota) ? route('movimentacao.download', ['movimentacao' => $this->id, 'tipo_documento' => 'NT']) : '';
    }

    public function getLinkNotaGuestAttribute()
    {
        if($this->path_nota){

            $link = route('download', ['nota' => Crypt::encryptString($this->id)]);

            return $link;            

        } else {
            return '';
        }
    }    

    public function getLinkMovimentacaoAttribute()
    {
        return ($this->path_nota) ? route('movimentacao.show', ['movimentacao' => $this->id]) : '';
    }    

    public function create_notification(){

        // Aviso de notificação para vencer
        $notificacao_aviso = new Notificacao();        

        $aviso_ini = Carbon::createFromFormat('Y-m-d H:i', $this->data_programada . ' 00:01');
        $aviso_fim = Carbon::createFromFormat('Y-m-d H:i', $this->data_programada . ' 23:59');
        
        $notificacao_aviso->movimentacao_id = $this->id;
        $notificacao_aviso->data_inicio = $aviso_ini;
        $notificacao_aviso->data_fim = $aviso_fim;
        $notificacao_aviso->nome= 'MF: Á Vencer - R$ '. $this->valor . ' - ' . $notificacao_aviso->data_fim_compacta;
        $notificacao_aviso->resumo= $this->item_texto;
        $notificacao_aviso->url_notificacao = $this->link_movimentacao;
        $notificacao_aviso->destaque = 'N';
        $notificacao_aviso->todos = 'N';
        $notificacao_aviso->status = 'A';

        $notificacao_aviso->save();

        $cliente_aviso = new ClienteNotificacao();
        $cliente_aviso->cliente_id = $this->cliente_id;
        $cliente_aviso->notificacao_id = $notificacao_aviso->id;
        $cliente_aviso->save();

        // Aviso de notificação vencida
        $notificacao_atraso = new Notificacao();

        $atraso_ini = $aviso_ini->copy()->addDays(1);
        $atraso_fim = $aviso_fim->copy()->addDays(1);

        $notificacao_atraso->movimentacao_id = $this->id;
        $notificacao_atraso->data_inicio = $atraso_ini;
        $notificacao_atraso->data_fim = $atraso_fim;
        $notificacao_atraso->nome= 'MF: VENCIDA - R$ '. $this->valor . ' - ' . $notificacao_aviso->data_fim_compacta;
        $notificacao_atraso->resumo= $this->item_texto;
        $notificacao_atraso->url_notificacao = $this->link_movimentacao;
        $notificacao_atraso->destaque = 'N';
        $notificacao_atraso->todos = 'N';
        $notificacao_atraso->status = 'A';

        $notificacao_atraso->save();       

        $cliente_atraso = new ClienteNotificacao();
        $cliente_atraso->cliente_id = $this->cliente_id;
        $cliente_atraso->notificacao_id = $notificacao_atraso->id;
        $cliente_atraso->save();        
    }    

    public function delete_notification(){
        
        $ids_notificacao = Notificacao::where('movimentacao_id', $this->id)->pluck('id');

        if($ids_notificacao){
            ClienteNotificacao::whereIn('notificacao_id', $ids_notificacao)->delete();
            Notificacao::whereIn('id', $ids_notificacao)->delete();
        }
    }

}

