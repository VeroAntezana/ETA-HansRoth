<!DOCTYPE html>
<html>

<head>
    <title>Recibo de Ingreso </title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 1.5cm;
            line-height: 1.3;
        }

        .container {
            width: 100%;
            page-break-inside: avoid;
            transform: scale(0.95);
        }

        /* Primera copia */
        .receipt-top {
            border-bottom: 1px dashed #000;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        /* Segunda copia */
        .receipt-bottom {
            padding-top: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #28a745;
            padding-bottom: 15px;
            position: relative;
            color: #28a745;
        }

        .logo {
            position: absolute;
            top: 0;
            right: 0;
            width: 80px;
        }

        .receipt-title {
            font-size: 20px;
            font-weight: bold;
            margin: 15px 0;
        }

        .details {
            margin: 15px 0;
        }

        .row {
            margin: 8px 0;
            display: flex;
            justify-content: space-between;
        }

        .label {
            font-weight: bold;
            width: 150px;
            color: #28a745;
        }

        .value {
            flex: 1;
            margin-left: 20px;
        }

        .amount {
            margin: 20px 0;
            padding: 8px;
            border: 1px solid #28a745;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            background-color: #28a745;
            color: #fff;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            width: 80%;
            margin: 20px auto;
        }

        .signature {
            width: 120px;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-bottom: 10px;
            width: 100%;
        }

        .signature-title {
            font-size: 12px;
            font-weight: bold;
        }

        .center-image {
            display: flex;
            justify-content: center;
            align-items: flex-end;
            margin: 0 10px;
        }

        .stamp-image {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }

        .folio {
            position: absolute;
            top: 20px;
            left: 0;
            font-size: 12px;
            color: #28a745;
        }

        .copy-label {
            position: absolute;
            top: 40px;
            left: 0;
            font-size: 12px;
            font-weight: bold;
            color: #28a745;
        }
    </style>
</head>

<body>
    <!-- Primera copia -->
    <div class="container receipt-top">
        <div class="header">
            <img src="{{ asset('vendor/adminlte/dist/img/eta.jpg') }}" alt="Logo" class="logo">
            <div class="folio">Folio: #{{ $pago->pago_id }}</div>
            <div class="copy-label">ORIGINAL</div>
            <div class="receipt-title">RECIBO DE INGRESO EXTRA</div>
        </div>

        <div class="details">
            <div class="row">
                <span class="label">Fecha:</span>
                <span class="value">{{ \Carbon\Carbon::parse($pago->fecha)->format('d/m/Y H:i') }}</span>
            </div>

            <div class="row">
                <span class="label">Concepto:</span>
                <span class="value">{{ $pago->concepto }}</span>
            </div>
        </div>

        <div class="amount">
            MONTO TOTAL: {{ number_format($pago->monto, 2) }} BS
        </div>

        <div class="footer">
            <div class="signatures">
                <div class="signature">
                    <div class="signature-line"></div>
                    <div class="signature-title">Entregué Conforme</div>
                </div>
                <div class="center-image">
                    <img src="{{ asset('vendor/adminlte/dist/img/Imagen1.jpg') }}" alt="Sello" class="stamp-image">
                </div>
                <div class="signature">
                    <div class="signature-line"></div>
                    <div class="signature-title">Recibí Conforme</div>
                </div>
            </div>
            <p>Este documento es un comprobante de ingreso </p>
            <p style="font-size: 12px;">Fecha de impresión: {{ now('America/La_Paz')->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>

    <!-- Segunda copia -->
    <div class="container receipt-bottom">
        <div class="header">
            <img src="{{ asset('vendor/adminlte/dist/img/eta.jpg') }}" alt="Logo" class="logo">
            <div class="folio">Folio: #{{ $pago->pago_id }}</div>
            <div class="copy-label">COPIA</div>
            <div class="receipt-title">RECIBO DE INGRESO EXTRA</div>
        </div>

        <div class="details">
            <div class="row">
                <span class="label">Fecha:</span>
                <span class="value">{{ \Carbon\Carbon::parse($pago->fecha)->format('d/m/Y H:i') }}</span>
            </div>

            <div class="row">
                <span class="label">Concepto:</span>
                <span class="value">{{ $pago->concepto }}</span>
            </div>
        </div>

        <div class="amount">
            MONTO TOTAL: {{ number_format($pago->monto, 2) }} BS
        </div>

        <div class="footer">
            <div class="signatures">
                <div class="signature">
                    <div class="signature-line"></div>
                    <div class="signature-title">Entregué Conforme</div>
                </div>
                <div class="center-image">
                    <img src="{{ asset('vendor/adminlte/dist/img/Imagen1.jpg') }}" alt="Sello" class="stamp-image">
                </div>
                <div class="signature">
                    <div class="signature-line"></div>
                    <div class="signature-title">Recibí Conforme</div>
                </div>
            </div>
            <p>Este documento es un comprobante de ingreso</p>
            <p style="font-size: 12px;">Fecha de impresión: {{ now('America/La_Paz')->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>

</html>
