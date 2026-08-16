<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CannedResponse;
use Illuminate\Http\Request;

class CannedResponseController extends Controller
{
    public function index()
    {
        $responses = CannedResponse::orderBy('category')->orderBy('title')->get();
        $categories = CannedResponse::distinct()->whereNotNull('category')->pluck('category');
        
        return view('admin.canned.index', compact('responses', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'content' => 'required|string',
        ]);

        CannedResponse::create($validated);

        return back()->with('success', 'Resposta pronta criada com sucesso!');
    }

    public function update(Request $request, CannedResponse $cannedResponse)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'content' => 'required|string',
        ]);

        $cannedResponse->update($validated);

        return back()->with('success', 'Resposta pronta atualizada!');
    }

    public function destroy(CannedResponse $cannedResponse)
    {
        $cannedResponse->delete();

        return back()->with('success', 'Resposta removida.');
    }
}
