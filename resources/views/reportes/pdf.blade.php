<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $titulo }}</title>
</head>
<body>
    <h1>{{ $titulo }}</h1>
    <p>Generado el: {{ $fechaGeneracion }}</p>
    <table border="1" cellpadding="5">
        <thead>
            <tr>
                @foreach($headers as $header)
                    <th>{{ ucfirst($header) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($reporte as $fila)
                <tr>
                    @foreach($fila as $valor)
                        <td>{{ $valor }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>