<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;


class ImportController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }

    
    public function index()
    {
        if(Gate::denies('import_filiado')){
            //abort('403', 'Página não disponível');
            return redirect()->back();
        }

        $user = Auth()->User();

        $roles = $user->roles;

        if (!$roles->contains('name', 'Gestor')){
            return redirect()->back();
        }
        
        return view('painel.import.index');
    }

    
    public function import(Request $request)
    {
        
        if(Gate::denies('import_filiado')){
            //abort('403', 'Página não disponível');
            return redirect()->back();
        }

        $user = Auth()->User();

        $roles = $user->roles;

        if (!$roles->contains('name', 'Gestor')){
            return redirect()->back();
        }


        Excel::import(new UsersImport, $request->file('iUsers'));

        return view('painel.import.index');
    }

}

