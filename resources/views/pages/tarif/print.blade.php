<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Tarif Parkir</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 13px;
            color: #000;
            background: #fff;
            padding: 30px;
        }

        .header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .header h2 {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header p {
            font-size: 12px;
            color: #444;
            margin-top: 4px;
        }

        .meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 16px;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        thead tr {
            background-color: #222;
            color: #fff;
        }

        thead th {
            padding: 9px 12px;
            text-align: left;
            font-size: 13px;
        }

        tbody tr:nth-child(even) {
            background-color: #f5f5f5;
        }

        tbody td {
            padding: 8px 12px;
            border-bottom: 1px solid #ddd;
        }

        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: flex-end;
        }

        .ttd {
            text-align: center;
            width: 200px;
        }

        .ttd p {
            font-size: 12px;
        }

        .ttd .space {
            height: 60px;
        }

        .ttd .nama {
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 4px;
        }

        @media print {
            body {
                padding: 20px;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    {{-- Tombol Print (tidak ikut tercetak) --}}
    <div class="no-print" style="margin-bottom:16px;">
        <button onclick="window.print()"
            style="padding:8px 20px; background:#c0392b; color:#fff; border:none; border-radius:4px; cursor:pointer; font-size:13px;">
            🖨️ Cetak
        </button>
    </div>

    {{-- Header --}}
    <div class="header">
        <h2>Sistem Parkir</h2>
        <p>Daftar Tarif Parkir Per Jam</p>
    </div>

    {{-- Meta info --}}
    <div class="meta">
        <span>Total Data: <strong>{{ $tarifs->count() }} tarif</strong></span>
        <span>Dicetak: <strong>{{ now()->translatedFormat('d F Y, H:i') }} WIB</strong></span>
    </div>

    {{-- Tabel --}}
    <table>
        <thead>
            <tr>
                <th style="width:46px">#</th>
                <th>Jenis Kendaraan</th>
                <th>Tarif Per Jam</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tarifs as $index => $tarif)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $tarif->jenis_kendaraan }}</td>
                <td>Rp {{ number_format($tarif->tarif_per_jam, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" style="text-align:center; padding:20px; color:#888;">
                    Tidak ada data tarif
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Tanda Tangan --}}
    <div class="footer">
        <div class="ttd">
            <p>Admin,</p>
            <div class="space"></div>
            <p class="nama">{{ auth()->user()->name }}</p>
            <p style="font-size:11px; color:#555;">Administrator</p>
        </div>
    </div>

</body>

</html>