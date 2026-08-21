<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Expõe as páginas institucionais indexáveis em formato XML.
     */
    public function __invoke(): Response
    {
        $pages = [
            ['url' => route('home'), 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['url' => route('services'), 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['url' => route('sobre'), 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['url' => route('portfolio'), 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['url' => route('faq'), 'changefreq' => 'weekly', 'priority' => '0.7'],
            ['url' => route('contact'), 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['url' => route('terms'), 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['url' => route('privacy'), 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['url' => route('sla'), 'changefreq' => 'yearly', 'priority' => '0.4'],
        ];

        return response()
            ->view('public.sitemap', compact('pages'))
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
