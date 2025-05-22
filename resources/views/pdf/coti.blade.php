<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen Cotización</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-size: 12px; }
        .title { background: #f8f9fa; padding: 10px; }
        .section { margin-top: 20px; }
        .table th, .table td { vertical-align: middle; }
    </style>
</head>
<body>
    <div class="container">
        <div class="title text-center">
            <h4>Resumen de Cotización</h4>
        </div>

        <div class="section">
            <h5>Datos del Cliente</h5>
            <p><strong>Agente:</strong> {{ $cotizacion->agente }}</p>
            <p><strong>Titular:</strong> {{ $cotizacion->titular }}</p>
            <p><strong>Email:</strong> {{ $cotizacion->email }}</p>
            <p><strong>Teléfono:</strong> {{ $cotizacion->telefono }}</p>
            <p><strong>Edades:</strong> {{ $cotizacion->edades }}</p>
        </div>

        <div class="section">
            <h5>Coberturas Solicitadas</h5>
            <ul>
                @if($cotizacion->vida) <li>Seguro de Vida</li> @endif
                @if($cotizacion->funerarios) <li>Servicios Funerarios</li> @endif
                @if($cotizacion->maternidad) <li>Maternidad</li> @endif
                @if($cotizacion->viajes) <li>Seguro de Viajes</li> @endif
            </ul>
        </div>

        <div class="section">
            <h5>Compañías Seleccionadas</h5>
            @foreach($companias as $index => $compania)
                <div class="mb-3">
                    <h6>{{ $compania->nombre }}</h6>
                    <p><strong>Coberturas:</strong></p>
                    <ul>
                        @foreach($compania->coberturas as $cobertura)
                            <li>{{ $cobertura->tipo }}: ${{ number_format($cobertura->monto, 2) }},  Prima: ${{ number_format($cobertura->prima, 2) }} </li>
                        @endforeach
                    </ul>
                    @if(isset($sumas[$index]))
                        <p><strong>Suma Asegurada Solicitada:</strong> ${{ number_format($sumas[$index], 2) }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
