<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Movimentacao;
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

        $id = Crypt::decryptString($request->nota);

        $movimentacao = Movimentacao::where('id', $id)->first();

        $path_documento = 'documentos/' . $movimentacao->cliente_id . '/';
        $path_documento = $path_documento . 'notas/' . $movimentacao->path_nota;

        return Storage::download($path_documento);
    }    

}
