<?php

namespace Tests\Feature;

use Tests\TestCase;

class LocalizationTest extends TestCase
{
    public function test_priority_translation_catalogs_resolve_in_portuguese(): void
    {
        $this->assertSame('Chamado criado com sucesso!', __('tickets.created', [], 'pt_BR'));
        $this->assertSame('Equipamento cadastrado com sucesso!', __('assets.created', [], 'pt_BR'));
        $this->assertSame('Artigo criado com sucesso!', __('knowledge.created', [], 'pt_BR'));
        $this->assertSame('Visita técnica agendada com sucesso!', __('visits.scheduled', [], 'pt_BR'));
        $this->assertSame('Registro atualizado com sucesso!', __('messages.success.updated', [], 'pt_BR'));
    }

    public function test_internal_status_values_remain_stable_while_labels_are_translated(): void
    {
        $this->assertSame('open', 'open');
        $this->assertSame('Aberto', __('tickets.status.open', [], 'pt_BR'));
        $this->assertSame('active', 'active');
        $this->assertSame('Ativo', __('assets.statuses.active', [], 'pt_BR'));
    }

    public function test_admin_ticket_interface_translations_are_available(): void
    {
        $this->assertSame('Gerenciar Chamados', __('tickets.ui.manage', [], 'pt_BR'));
        $this->assertSame('Nenhum chamado encontrado', __('tickets.ui.empty_admin_title', [], 'pt_BR'));
        $this->assertSame('Salvar status', __('tickets.ui.save_status', [], 'pt_BR'));
        $this->assertSame('Quadro de Gestão', __('tickets.ui.kanban', [], 'pt_BR'));
    }
}
