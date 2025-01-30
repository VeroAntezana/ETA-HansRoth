<!DOCTYPE html>
<html>

<head>
    <title>Recibo de Pago</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 2.5cm;
            line-height: 1.6;
        }

        .container {
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
            position: relative;
        }

        .logo {
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
        }

        .receipt-title {
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
        }

        .details {
            margin: 20px 0;
        }

        .row {
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
        }

        .label {
            font-weight: bold;
            width: 150px;
        }

        .value {
            flex: 1;
            margin-left: 20px;
        }

        .amount {
            margin: 30px 0;
            padding: 10px;
            border: 1px solid #000;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            position: fixed;
            bottom: 2.5cm;
            width: calc(100% - 5cm);
        }

        .signatures {
            display: flex;
            justify-content: space-around;
            margin-top: 100px;
            width: 100%;
        }

        .signature {
            width: 200px;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-bottom: 10px;
            width: 100%;
        }

        .signature-title {
            font-size: 14px;
            font-weight: bold;
        }

        .folio {
            position: absolute;
            top: 20px;
            left: 0;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('vendor/adminlte/dist/img/eta.jpg') }}" alt="Logo" class="logo">
            <div class="folio">Folio: #{{ $pago->pago_id }}</div>
            <div class="receipt-title">RECIBO DE PAGO</div>
        </div>

        <div class="details">
            <div class="row">
                <span class="label">Fecha:</span>
                <span class="value">{{ \Carbon\Carbon::parse($pago->fecha)->format('d/m/Y H:i') }}</span>
            </div>

            <div class="row">
                <span class="label">Estudiante:</span>
                <span class="value">{{ $estudiante->nombre }} {{ $estudiante->apellidos }}</span>
            </div>

            <div class="row">
                <span class="label">Carrera:</span>
                <span class="value">{{ $carrera->nombre }} - {{ $nivel->nombre }}</span>
            </div>

            <div class="row">
                <span class="label">Concepto:</span>
                <span class="value">{{ $pago->concepto }}</span>
            </div>

            <div class="row">
                <span class="label">Módulos Pagados:</span>
                <span class="value">{{ $pago->mes_pago }}</span>
            </div>
        </div>

        <div class="amount">
            MONTO TOTAL: {{ number_format($pago->monto) }} BS
        </div>

        <div class="footer">
            <div class="signatures">
                <div class="signature">
                    <div class="signature-line"></div>
                    <div class="signature-title">Entregué Conforme</div>
                </div>
                <div class="signature">
                    <div class="signature-line"></div>
                    <div class="signature-title">Recibí Conforme</div>
                </div>
            </div>
            <p>Este documento es un comprobante de pago</p>
            <p style="font-size: 12px;">Fecha de impresión: {{ now('America/La_Paz')->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>

</html>