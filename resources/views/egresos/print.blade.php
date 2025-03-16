<!DOCTYPE html>
<html>

<head>
    <title>Recibo de Egreso</title>
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

        /* Primer recibo */
        .receipt-top {
            border-bottom: 1px dashed #000;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        /* Segundo recibo */
        .receipt-bottom {
            padding-top: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            position: relative;
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
        }

        .value {
            flex: 1;
            margin-left: 20px;
        }

        .amount {
            margin: 20px 0;
            padding: 8px;
            border: 1px solid #000;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 50px;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        .center-image {
            display: flex;
            justify-content: center;
            align-items: flex-end;
            margin: 0 20px;
        }

        .stamp-image {
            width: 100px;
            /* Ajusta este valor según el tamaño que necesites */
            height: auto;
            margin-bottom: 10px;
        }

        .signature {
            width: 150px;
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

        .folio {
            position: absolute;
            top: 20px;
            left: 0;
            font-size: 12px;
        }

        /* Copias */
        .copy-label {
            position: absolute;
            top: 40px;
            left: 0;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <!-- Primera copia -->
    <div class="container receipt-top">
        <div class="header">
            <img src="{{ asset('vendor/adminlte/dist/img/eta.jpg') }}" alt="Logo" class="logo">
            <div class="folio">Folio: #{{ $egreso->egreso_id }}</div>
            <div class="copy-label">ORIGINAL</div>
            <div class="receipt-title">RECIBO DE EGRESO</div>
        </div>

        <!-- Resto del contenido igual -->
        <div class="details">
            <div class="row">
                <span class="label">Fecha:</span>
                <span class="value">{{ \Carbon\Carbon::parse($egreso->fecha)->format('d/m/Y H:i') }}</span>
            </div>
            <div class="row">
                <span class="label">Nombre:</span>
                <span class="value">{{ $egreso->nombre }}</span>
            </div>
            <div class="row">
                <span class="label">Concepto:</span>
                <span class="value">{{ $egreso->concepto }}</span>
            </div>
        </div>

        <div class="amount">
            MONTO TOTAL: {{ number_format($egreso->monto, 2) }} BS
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
            <p>Este documento es un comprobante de egreso ETA HANS ROTH</p>
            <p style="font-size: 12px;">Fecha de impresión: {{ now('America/La_Paz')->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>

    <!-- Segunda copia -->
    <div class="container receipt-bottom">
        <div class="header">
            <img src="{{ asset('vendor/adminlte/dist/img/eta.jpg') }}" alt="Logo" class="logo">
            <div class="folio">Folio: #{{ $egreso->egreso_id }}</div>
            <div class="copy-label">COPIA</div>
            <div class="receipt-title">RECIBO DE EGRESO</div>
        </div>

        <!-- Resto del contenido igual -->
        <div class="details">
            <div class="row">
                <span class="label">Fecha:</span>
                <span class="value">{{ \Carbon\Carbon::parse($egreso->fecha)->format('d/m/Y H:i') }}</span>
            </div>
            <div class="row">
                <span class="label">Nombre:</span>
                <span class="value">{{ $egreso->nombre }}</span>
            </div>
            <div class="row">
                <span class="label">Concepto:</span>
                <span class="value">{{ $egreso->concepto }}</span>
            </div>
        </div>

        <div class="amount">
            MONTO TOTAL: {{ number_format($egreso->monto, 2) }} BS
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
            <p>Este documento es un comprobante de egreso ETA HANS ROTH</p>
            <p style="font-size: 12px;">Fecha de impresión: {{ now('America/La_Paz')->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>

</html>