<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicPageRenderingTest extends TestCase
{
    public function test_static_public_pages_render_without_artificial_skeleton_gate(): void
    {
        foreach (['services', 'portfolio', 'sobre'] as $route) {
            $this->get(route($route))
                ->assertOk()
                ->assertDontSee('x-show="!loaded"', false)
                ->assertDontSee('x-show="loaded"', false)
                ->assertDontSee('setTimeout(() => loaded = true, 400)', false);
        }
    }
}
