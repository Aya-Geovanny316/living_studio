<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>Respuesta cotizacion {{ $quote->quote_number }}</title>
    </head>
    <body style="margin:0;font-family:Arial, sans-serif;background:#E6E6E6;color:#2B2B2B;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#E6E6E6;padding:24px;">
            <tr>
                <td align="center">
                    <table width="640" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:16px;overflow:hidden;">
                        <tr>
                            <td style="background:#EF1C14;color:#ffffff;padding:24px;">
                                <p style="margin:0;font-size:16px;font-weight:700;letter-spacing:0.4px;">GT Hobby</p>
                                <p style="margin:6px 0 0;font-size:12px;">Modelismo y hobbies que inspiran</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:24px;">
                                <h2 style="margin:0 0 12px;font-size:18px;color:#2B2B2B;">Respuesta a tu cotizacion</h2>
                                <p style="margin:0 0 16px;font-size:14px;color:#5F5B5B;">
                                    Cotizacion: <strong>{{ $quote->quote_number }}</strong>
                                </p>
                                <div style="margin-bottom:16px;padding:12px;background:#F2F2F2;border-radius:12px;">
                                    <p style="margin:0;font-size:12px;color:#7A7676;">Mensaje</p>
                                    <p style="margin:6px 0 0;font-size:13px;color:#2B2B2B;">{{ $quote->response_message }}</p>
                                </div>
                                <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                                    <thead>
                                        <tr style="background:#F2F2F2;">
                                            <th align="left" style="padding:8px;font-size:12px;color:#EF1C14;">Producto</th>
                                            <th align="center" style="padding:8px;font-size:12px;color:#EF1C14;">Qty</th>
                                            <th align="right" style="padding:8px;font-size:12px;color:#EF1C14;">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($quote->items as $item)
                                            <tr style="border-bottom:1px solid #e5e7eb;">
                                                <td style="padding:8px;font-size:13px;color:#2B2B2B;">{{ $item->product_name_snapshot }}</td>
                                                <td align="center" style="padding:8px;font-size:13px;color:#2B2B2B;">{{ $item->qty }}</td>
                                                <td align="right" style="padding:8px;font-size:13px;color:#2B2B2B;">Q {{ number_format($item->line_total_estimate, 2, '.', ',') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <p style="margin:16px 0 0;font-size:14px;color:#2B2B2B;text-align:right;">
                                    Subtotal estimado: <strong>Q {{ number_format($quote->subtotal_estimate, 2, '.', ',') }}</strong>
                                </p>
                                @if($quote->response_total_estimate)
                                    <p style="margin:8px 0 0;font-size:14px;color:#2B2B2B;text-align:right;">
                                        Total propuesto: <strong>Q {{ number_format($quote->response_total_estimate, 2, '.', ',') }}</strong>
                                    </p>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="background:#2B2B2B;color:#ffffff;padding:16px;text-align:center;font-size:12px;">
                                GT Hobby | Modelismo y hobbies que inspiran
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
