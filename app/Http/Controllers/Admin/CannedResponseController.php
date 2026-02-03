<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CannedResponse;
use Illuminate\Http\Request;

class CannedResponseController extends Controller
{
    public function index()
    {
        $responses = CannedResponse::latest()->get();
        return view('admin.canned.index', compact('responses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        CannedResponse::create($validated);

        return back()->with('success', 'Resposta pronta criada com sucesso!');
    }

    public function destroy(CannedResponse $cannedResponse)
    {
        $cannedResponse->delete();
        return back()->with('success', 'Resposta removida.');
    }
}