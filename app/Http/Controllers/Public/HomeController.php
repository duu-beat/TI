<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Exibe a landing pública ou encaminha o usuário autenticado ao ambiente
     * correspondente ao seu papel. As dashboards internas permanecem como a
     * única fonte de métricas e fluxos operacionais.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (!$user) {
            return view('public.home');
        }

        if ($user->isMaster()) {
            return redirect()->route('master.dashboard');
        }

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('client.dashboard');
    }
}
