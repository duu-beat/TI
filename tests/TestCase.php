<?php

namespace Tests;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Feature tests exercitam os fluxos de autenticação sem precisar gerar token HTML.
        // A proteção CSRF permanece ativa em todos os ambientes de execução da aplicação.
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }
}
