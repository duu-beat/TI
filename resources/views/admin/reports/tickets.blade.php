<!DOCTYPE html>
<html>
<head>
    <title>Relatório de Chamados</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
        .status { font-weight: bold; text-transform: uppercase; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório Geral de Suporte TI</h1>
        <p>Gerado em: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Data</th>
                <th>Cliente</th>
                <th>Assunto</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
            <tr>
                <td>#{{ $ticket->id }}</td>
                <td>{{ $ticket->created_at->format('d/m/Y') }}</td>
                <td>{{ $ticket->user->name }}</td>
                <td>{{ $ticket->subject }}</td>
                <td>
                    <span class="status">{{ $ticket->status->label() }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>