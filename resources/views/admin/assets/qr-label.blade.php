<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Etiqueta QR · {{ $asset->tag }}</title>
    <style>
        :root { color-scheme: light; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; background: #e2e8f0; color: #0f172a; font-family: Arial, sans-serif; }
        .label { width: 88mm; min-height: 58mm; padding: 5mm; background: #fff; border: 1px solid #cbd5e1; border-radius: 4mm; box-shadow: 0 12px 36px rgba(15, 23, 42, .16); }
        .header { display: flex; align-items: center; justify-content: space-between; gap: 4mm; border-bottom: 1px solid #e2e8f0; padding-bottom: 3mm; }
        .brand { font-size: 8pt; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: #0e7490; }
        .tag { font-family: monospace; font-size: 9pt; font-weight: 700; color: #334155; }
        .content { display: grid; grid-template-columns: 1fr 32mm; align-items: center; gap: 4mm; padding-top: 4mm; }
        .type { margin: 0; font-size: 7pt; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: #64748b; }
        .name { margin: 2mm 0; font-size: 12pt; font-weight: 800; line-height: 1.15; }
        .meta { margin: 0; font-size: 7.5pt; line-height: 1.5; color: #475569; }
        .qr { width: 32mm; height: 32mm; padding: 1mm; border: 1px solid #e2e8f0; }
        .footer { margin-top: 3mm; font-size: 6.5pt; color: #64748b; text-align: center; }
        .actions { margin-top: 20px; text-align: center; }
        button { cursor: pointer; border: 0; border-radius: 10px; background: #0891b2; color: white; padding: 11px 16px; font: 700 14px Arial, sans-serif; }
        @media print {
            @page { size: 88mm 58mm; margin: 0; }
            body { min-height: auto; background: #fff; }
            .label { width: 88mm; min-height: 58mm; border: 0; border-radius: 0; box-shadow: none; }
            .actions { display: none; }
        }
    </style>
</head>
<body>
    <main>
        <section class="label" aria-label="Etiqueta de inventário {{ $asset->tag }}">
            <div class="header">
                <span class="brand">Suporte TI · Inventário</span>
                <span class="tag">#{{ $asset->tag }}</span>
            </div>
            <div class="content">
                <div>
                    <p class="type">{{ $asset->type }}</p>
                    <h1 class="name">{{ $asset->name }}</h1>
                    <p class="meta">{{ collect([$asset->brand, $asset->model])->filter()->join(' · ') ?: 'Modelo não informado' }}</p>
                    <p class="meta">S/N: {{ $asset->serial_number ?: 'Não informado' }}</p>
                </div>
                <img class="qr" src="{{ route('admin.assets.qr-code', $asset) }}" alt="QR Code do ativo {{ $asset->tag }}">
            </div>
            <p class="footer">Escaneie com uma sessão autorizada para abrir a ficha interna do ativo.</p>
        </section>
        <div class="actions"><button type="button" onclick="window.print()">Imprimir etiqueta</button></div>
    </main>
</body>
</html>
