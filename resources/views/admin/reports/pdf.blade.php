<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.navigation.reports.report_title') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3B82F6;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #1E40AF;
            font-size: 20pt;
            margin-bottom: 5px;
        }
        .header p {
            color: #6B7280;
            font-size: 9pt;
        }
        .stats {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .stat-box {
            display: table-cell;
            width: 25%;
            padding: 10px;
            text-align: center;
            border: 1px solid #E5E7EB;
            background: #F9FAFB;
        }
        .stat-box h3 {
            font-size: 18pt;
            color: #3B82F6;
            margin-bottom: 5px;
        }
        .stat-box p {
            font-size: 8pt;
            color: #6B7280;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background: #3B82F6;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 9pt;
        }
        td {
            padding: 6px 8px;
            border-bottom: 1px solid #E5E7EB;
            font-size: 8pt;
        }
        tr:nth-child(even) {
            background: #F9FAFB;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7pt;
            font-weight: bold;
        }
        .badge-high { background: #FEE2E2; color: #991B1B; }
        .badge-medium { background: #FEF3C7; color: #92400E; }
        .badge-low { background: #D1FAE5; color: #065F46; }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8pt;
            color: #9CA3AF;
            border-top: 1px solid #E5E7EB;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 {{ __('messages.navigation.reports.report_title') }}</h1>
        <p>{{ __('messages.navigation.reports.generated_at') }} {{ $generatedAt }}</p>
        @if(isset($filters['date_from']) || isset($filters['date_to']))
            <p>
                {{ __('messages.navigation.reports.period') }}
                {{ $filters['date_from'] ?? __('messages.navigation.reports.start') }} até {{ $filters['date_to'] ?? __('messages.navigation.reports.today') }}
            </p>
        @endif
    </div>

    <div class="stats">
        <div class="stat-box">
            <h3>{{ $stats['total'] }}</h3>
            <p>{{ __('messages.navigation.reports.total_tickets') }}</p>
        </div>
        <div class="stat-box">
            <h3>{{ number_format($stats['avg_response_time'], 0) }}min</h3>
            <p>{{ __('messages.navigation.reports.avg_response') }}</p>
        </div>
        <div class="stat-box">
            <h3>{{ number_format($stats['avg_resolution_time'], 0) }}min</h3>
            <p>{{ __('messages.navigation.reports.avg_resolution') }}</p>
        </div>
        <div class="stat-box">
            <h3>{{ number_format($stats['avg_rating'], 1) }}/5</h3>
            <p>{{ __('messages.navigation.reports.avg_rating') }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>{{ __('messages.navigation.reports.client') }}</th>
                <th>Assunto</th>
                <th>Status</th>
                <th>Prioridade</th>
                <th>{{ __('messages.navigation.reports.created_at') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
            <tr>
                <td>#{{ $ticket->id }}</td>
                <td>{{ $ticket->user->name }}</td>
                <td>{{ Str::limit($ticket->subject, 40) }}</td>
                <td>{{ $ticket->status->label() }}</td>
                <td>
                    <span class="badge badge-{{ strtolower($ticket->priority->name) }}">
                        {{ $ticket->priority->label() }}
                    </span>
                </td>
                <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Suporte TI - Sistema de Gestão de Chamados</p>
        <p>{{ __('messages.navigation.reports.automatically_generated') }}</p>
    </div>
</body>
</html>
