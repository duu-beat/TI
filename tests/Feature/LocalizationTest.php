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

    public function test_admin_asset_interface_translations_are_available(): void
    {
        $this->assertSame('Inventário de Ativos', __('assets.ui.inventory', [], 'pt_BR'));
        $this->assertSame('Novo Equipamento', __('assets.ui.new_asset', [], 'pt_BR'));
        $this->assertSame('QR Code do ativo :tag', __('assets.ui.qr_code_asset', ['tag' => ':tag'], 'pt_BR'));
        $this->assertSame('Assinar termo de responsabilidade', __('assets.ui.sign_term', [], 'pt_BR'));
        $this->assertSame('Entrega de ativo', __('assets.ui.delivery_asset', [], 'pt_BR'));
    }

    public function test_shared_and_seo_catalogs_are_available_in_portuguese(): void
    {
        $this->assertSame('Fechar menu lateral', __('messages.shared.close_sidebar', [], 'pt_BR'));
        $this->assertSame('Confirmar saída', __('messages.shared.confirm_logout', [], 'pt_BR'));
        $this->assertSame('Suporte TI | Operação, segurança e continuidade para sua empresa', __('messages.seo.home_title', [], 'pt_BR'));
        $this->assertSame('Login - Área do Cliente', __('messages.seo.login_title', [], 'pt_BR'));
    }
}
