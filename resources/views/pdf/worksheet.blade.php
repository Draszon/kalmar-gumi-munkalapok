<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Munkalap - {{ $worksheet->registration_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #111;
            line-height: 1.4;
        }
        .container {
            padding: 18mm 12mm 10mm 12mm;
            max-width: 190mm;
            margin: 0 auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #222;
            padding-bottom: 8px;
            margin-bottom: 18px;
        }
        .company-block {
            min-width: 120px;
        }
        .company-block .company-name {
            font-size: 13px;
            font-weight: bold;
            color: #111;
            margin-bottom: 2px;
        }
        .company-block .company-contact {
            font-size: 9px;
            color: #222;
            margin-bottom: 1px;
        }
        .client-block {
            min-width: 120px;
            text-align: right;
        }
        .client-block .client-label {
            color: #444;
            font-size: 9px;
        }
        .client-block .client-value {
            font-size: 10px;
            font-weight: 500;
            color: #111;
            margin-bottom: 1px;
        }
        .regnum {
            font-size: 15px;
            font-weight: bold;
            color: #111;
            background: #fff;
            border: 1px solid #222;
            display: inline-block;
            border-radius: 5px;
            padding: 3px 12px;
            margin: 0 0 0 0;
            letter-spacing: 1px;
        }
        .section {
            margin-bottom: 13px;
        }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #111;
            border-bottom: 1px solid #222;
            padding-bottom: 2px;
            margin-bottom: 5px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2px;
        }
        .info-table td {
            padding: 2px 6px 2px 0;
            vertical-align: top;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2px;
            font-size: 10px;
        }
        .invoice-table th, .invoice-table td {
            border: 1px solid #222;
            padding: 3px 6px;
            text-align: left;
        }
        .invoice-table th {
            background: #f5f5f5;
            font-weight: bold;
        }
        .comment-box {
            background: #fff;
            border: 1px solid #222;
            border-radius: 4px;
            padding: 7px;
            margin-top: 2px;
            min-height: 30px;
            font-size: 9px;
        }
        .footer {
            margin-top: 18px;
            padding-top: 8px;
            border-top: 1px solid #222;
            text-align: center;
            color: #444;
            font-size: 9px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Fejléc két oszlopban -->
        <div class="header">
            <div class="company-block">
                <div class="company-name">Kalmár Gumiszerviz Kft.</div>
                <div class="company-contact">Eger, Árpád u. 39.</div>
                <div class="company-contact">Telefon: 06 36/560-231</div>
                <div class="company-contact">info@autogumiexpo.hu</div>
                <div class="company-contact">www.gumikalmar.hu</div>
            </div>
            <div class="client-block">
                <div class="client-label">Ügyfél neve</div>
                <div class="client-value">{{ $worksheet->name ?: '-' }}</div>
                <div class="client-label">Rendszám</div>
                <div class="client-value regnum" style="text-transform: uppercase;">{{ $worksheet->registration_number ?: '-' }}</div>
                <div class="client-label">Gépjármű típusa</div>
                <div class="client-value">{{ $worksheet->car_type ?: '-' }}</div>
            </div>
        </div>

        <!-- Tételes adatok számlaszerűen -->
        <div class="section">
            <div class="section-title">Tételes munkalap</div>
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th style="width: 40%;">Megnevezés</th>
                        <th style="width: 10%;">Mennyiség</th>
                        <th style="width: 25%;">Típus / Részletek</th>
                        <th style="width: 25%;">Megjegyzés</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Szolgáltatások -->
                    @if($worksheet->services && count($worksheet->services) > 0)
                        @foreach($worksheet->services as $service)
                            <tr>
                                <td>Szolgáltatás: @if(is_array($service) && isset($service['name'])){{ $service['name'] }}@else{{ $service }}@endif</td>
                                <td>@if(is_array($service) && isset($service['qty'])){{ $service['qty'] }}@else 1 @endif</td>
                                <td>Szolgáltatás</td>
                                <td></td>
                            </tr>
                        @endforeach
                    @endif
                    <!-- Felhasznált anyagok -->
                    @if($worksheet->used_materials && count($worksheet->used_materials) > 0)
                        @foreach($worksheet->used_materials as $material)
                            <tr>
                                <td>Anyag: @if(is_array($material) && isset($material['name'])){{ $material['name'] }}@else{{ $material }}@endif</td>
                                <td>@if(is_array($material) && isset($material['qty'])){{ $material['qty'] }}@else 1 @endif</td>
                                <td>Felhasznált anyag</td>
                                <td></td>
                            </tr>
                        @endforeach
                    @endif
                    <!-- Tárolás -->
                    <tr>
                        <td>Tárolás</td>
                        <td>{{ $worksheet->store ? ($worksheet->store_qty ?: 1) : 0 }}</td>
                        <td>
                            @if($worksheet->store)
                                @if($worksheet->store_tire)Gumiabroncs @endif
                                @if($worksheet->store_wheel)Szerelt kerék @endif
                            @else
                                -
                            @endif
                        </td>
                        <td></td>
                    </tr>
                    <!-- Gumiabroncs adatok -->
                    <tr>
                        <td>Gumiabroncs márka</td>
                        <td>-</td>
                        <td>{{ $worksheet->tire_brand ?: '-' }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Gumiabroncs méret</td>
                        <td>-</td>
                        <td>{{ $worksheet->tire_size ?: '-' }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Megjegyzés</div>
            <div class="comment-box">
                {{ $worksheet->comment ?: 'Nincs megjegyzés' }}
            </div>
        </div>

        <!-- Lábléc/dátumok -->
        <div class="footer">
            Létrehozva: {{ $worksheet->created_at->format('Y.m.d H:i') }} |
            Lezárva: {{ $worksheet->closed_at ? $worksheet->closed_at->format('Y.m.d H:i') : '-' }}<br>
            Generálva: {{ now()->format('Y.m.d H:i') }} | KalmárGumi Munkalapkezelő Rendszer
        </div>
    </div>
</body>
</html>
