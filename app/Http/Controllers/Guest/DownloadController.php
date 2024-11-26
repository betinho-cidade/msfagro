<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Movimentacao;
use App\Models\Efetivo;
use App\Models\Lucro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;


class DownloadController extends Controller
{

    public function __construct(Request $request)
    {
    }

    public function download(Request $request){

        $path_documento = '';

        if($request->has('comprovante')){
            $id = Crypt::decryptString($request->comprovante);
            $movimentacao = Movimentacao::where('id', $id)->first();

            $path_documento = 'documentos/' . $movimentacao->cliente_id . '/';
            $path_documento = $path_documento . 'comprovantes/' . $movimentacao->path_comprovante;

        }else if($request->has('nota')){
            $id = Crypt::decryptString($request->nota);
            $movimentacao = Movimentacao::where('id', $id)->first();

            $path_documento = 'documentos/' . $movimentacao->cliente_id . '/';
            $path_documento = $path_documento . 'notas/' . $movimentacao->path_nota;

        }else if($request->has('anexo')){
            $id = Crypt::decryptString($request->anexo);
            $movimentacao = Movimentacao::where('id', $id)->first();

            $path_documento = 'documentos/' . $movimentacao->cliente_id . '/';
            $path_documento = $path_documento . 'anexos/' . $movimentacao->path_anexo;

        }else if($request->has('gta')){
            $id = Crypt::decryptString($request->gta);
            $efetivo = Efetivo::where('id', $id)->first();

            $path_documento = 'documentos/' . $efetivo->cliente_id . '/';
            $path_documento = $path_documento . 'gtas/' . $efetivo->path_gta;
        }

        return Storage::download($path_documento);
    }

    public function download_lucro(Request $request){

        $path_comprovante = '';

        if($request->has('comprovante')){
            $id = Crypt::decryptString($request->comprovante);
            $lucro = Lucro::where('id', $id)->first();

            $path_comprovante = 'documentos/' . $lucro->cliente->id . '/lucros/' . $lucro->path_comprovante;

        }

        return Storage::download($path_comprovante);
    }

}
