<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 30px 36px; }
        body { font-family: DejaVu Sans, sans-serif; color: #172033; font-size: 11px; line-height: 1.55; }
        .header { border-bottom: 3px solid #4f46e5; padding-bottom: 16px; margin-bottom: 24px; }
        .eyebrow { color: #4f46e5; font-size: 9px; font-weight: bold; letter-spacing: 1.4px; text-transform: uppercase; }
        h1 { font-size: 21px; margin: 7px 0 4px; color: #111827; }
        .subtitle { color: #64748b; margin: 0; }
        .meta { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .meta td { border: 1px solid #dbe3f0; padding: 10px 12px; vertical-align: top; }
        .label { display: block; font-size: 8px; color: #64748b; font-weight: bold; letter-spacing: .8px; text-transform: uppercase; margin-bottom: 3px; }
        .value { color: #172033; font-weight: bold; }
        .section { margin-top: 22px; }
        .section-title { border-left: 4px solid #4f46e5; color: #1e293b; font-size: 12px; font-weight: bold; padding-left: 9px; margin: 0 0 10px; }
        .term-text { background: #f8fafc; border: 1px solid #e2e8f0; padding: 15px; white-space: pre-line; color: #334155; }
        .signature-wrap { border: 1px solid #dbe3f0; padding: 14px; margin-top: 10px; min-height: 94px; }
        .signature { max-width: 270px; max-height: 80px; display: block; }
        .audit { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 9px; }
        .audit td { border-bottom: 1px solid #e2e8f0; padding: 6px 0; }
        .audit td:first-child { color: #64748b; width: 32%; }
        .footer { position: fixed; bottom: -12px; left: 0; right: 0; color: #94a3b8; font-size: 8px; text-align: center; }
    </style>
</head>
<body>
    <header class="header">
        <div class="eyebrow">{{ __('assets.ui.inventory_brand') }}</div>
        <h1>{{ __('assets.ui.pdf_title', ['type' => $term->typeLabel()]) }}</h1>
        <p class="subtitle">{{ __('assets.ui.pdf_subtitle', ['asset' => $term->asset_id, 'term' => $term->id]) }}</p>
    </header>

    <table class="meta">
        <tr>
            <td width="50%"><span class="label">{{ __('assets.ui.equipment') }}</span><span class="value">{{ $term->asset->name }}</span></td>
            <td width="50%"><span class="label">{{ __('assets.ui.tag') }}</span><span class="value">{{ $term->asset->tag }}</span></td>
        </tr>
        <tr>
            <td><span class="label">{{ __('assets.ui.brand_model') }}</span><span class="value">{{ trim(($term->asset->brand ?: '') . ' ' . ($term->asset->model ?: '')) ?: __('assets.ui.not_informed') }}</span></td>
            <td><span class="label">{{ __('assets.ui.serial_number') }}</span><span class="value">{{ $term->asset->serial_number ?: __('assets.ui.not_informed') }}</span></td>
        </tr>
        <tr>
            <td><span class="label">{{ __('assets.ui.responsible_user') }}</span><span class="value">{{ $term->recipient->name }} · {{ $term->recipient->email }}</span></td>
            <td><span class="label">{{ __('assets.ui.issued_by') }}</span><span class="value">{{ $term->issuer->name }}</span></td>
        </tr>
    </table>

    <section class="section">
        <h2 class="section-title">{{ __('assets.ui.responsibility_declaration') }}</h2>
        <div class="term-text">{{ $term->terms_text }}</div>
    </section>

    <section class="section">
        <h2 class="section-title">{{ __('assets.ui.electronic_signature') }}</h2>
        <div class="signature-wrap">
            @if($signatureDataUri)
                <img src="{{ $signatureDataUri }}" class="signature" alt="{{ __('assets.ui.signature') }} de {{ $term->recipient->name }}">
            @else
                <span style="color:#b91c1c">{{ __('assets.ui.signature_not_found') }}</span>
            @endif
        </div>
    </section>

    <section class="section">
        <h2 class="section-title">{{ __('assets.ui.audit_log') }}</h2>
        <table class="audit">
            <tr><td>{{ __('assets.ui.signature_date_time') }}</td><td>{{ $term->signed_at?->format('d/m/Y H:i:s') ?? __('assets.ui.not_informed') }}</td></tr>
            <tr><td>{{ __('assets.ui.registered_ip') }}</td><td>{{ $term->signed_ip ?: __('assets.ui.not_informed') }}</td></tr>
            <tr><td>{{ __('assets.ui.signature_identifier') }}</td><td>{{ $term->signature_hash ?: __('assets.ui.not_informed') }}</td></tr>
            <tr><td>{{ __('assets.ui.term_status') }}</td><td>{{ __('assets.ui.signed_and_registered') }}</td></tr>
        </table>
    </section>

    <div class="footer">{{ __('assets.ui.generated_document', ['date' => now()->format('d/m/Y H:i')]) }}</div>
</body>
</html>
