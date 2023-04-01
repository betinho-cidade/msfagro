<?php

namespace App\Http\Controllers\Painel\Gestao\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;



class DashboardController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }


    public function index()
    {

        if(Gate::denies('view_dashboard')){
            abort('403', 'Página não disponível');
         }

        $user = Auth()->User();

        return view('painel.gestao.dashboard.index', compact('user'));
    }


    public function formatDate($month) {

        //$month = now()->addMonth()->format('F');

        switch($month)
         {
                case "January": $month = "Janeiro"; break;
                case "February": $month = "Fevereiro"; break;
                case "March": $month = "Março"; break;
                case "April": $month = "Abril"; break;
                case "May": $month = "Maio"; break;
                case "June": $month = "Junho"; break;
                case "July": $month = "Julho"; break;
                case "August": $month = "Agosto"; break;
                case "September": $month = "Setembro"; break;
                case "October": $month = "Outubro"; break;
                case "November": $month = "Novembro"; break;
                case "December": $month = "Dezembro"; break;
                default: $month = $month; break;
        }

        return $month . '/' . now()->year   ;
    }


}

