<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        // Pega apenas as ativas
        $faqs = Faq::where('is_active', true)->get();
        return view('public.faq', compact('faqs'));
    }
}