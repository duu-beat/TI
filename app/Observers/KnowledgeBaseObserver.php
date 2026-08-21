<?php

namespace App\Observers;

use App\Models\KnowledgeBase;
use Illuminate\Support\Facades\Cache;

class KnowledgeBaseObserver
{
    /**
     * Mantém a ajuda rápida do Cliente sincronizada com a Wiki publicada.
     */
    public function saved(KnowledgeBase $article): void
    {
        Cache::forget('client_dashboard_quick_articles');
    }

    public function deleted(KnowledgeBase $article): void
    {
        Cache::forget('client_dashboard_quick_articles');
    }

    public function restored(KnowledgeBase $article): void
    {
        Cache::forget('client_dashboard_quick_articles');
    }
}
