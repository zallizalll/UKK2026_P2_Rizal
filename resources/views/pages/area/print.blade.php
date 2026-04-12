<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Area Parkir</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
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
        }

        tbody tr:nth-child(even) {
            background-color: #f5f5f5;
        }

        tbody td {
            padding: 8px 12px;
            border-bottom: 1px solid #ddd;
        }

        .badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-ok {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .badge-warn {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }

        .badge-full {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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

        .ttd .space {
            height: 60px;
        }

        .ttd .nama {
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 4px;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="no-print" style="margin-bottom:16px;">
        <button onclick="window.print()"
            style="padding:8px 20px;background:#c0392b;color:#fff;border:none;border-radius:4px;cursor:pointer;font-size:13px;">
            🖨️ Cetak
        </button>
    </div>

    <div class="header">
        <h2>Sistem Parkir</h2>
        <p>Daftar Area Parkir &amp; Ketersediaan Slot</p>
    </div>

    <div class="meta">
        <span>Total: <strong>{{ $areas->count() }} area</strong> &nbsp;|&nbsp;
            Total Slot: <strong>{{ $areas->sum('kapasitas') }}</strong> &nbsp;|&nbsp;
            Terisi: <strong>{{ $areas->sum('terisi') }}</strong>
        </span>
        <span>Dicetak: <strong>{{ now()->translatedFormat('d F Y, H:i') }} WIB</strong></span>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:40px">#</th>
                <th>Nama Area</th>
                <th>Kapasitas</th>
                <th>Terisi</th>
                <th>Sisa</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($areas as $index => $area)
            @php
            $penuh = $area->terisi >= $area->kapasitas;
            $persen = $area->kapasitas > 0 ? ($area->terisi / $area->kapasitas) * 100 : 0;
            $badgeClass = $penuh ? 'badge-full' : ($persen >= 70 ? 'badge-warn' : 'badge-ok');
            $statusText = $penuh ? 'Penuh' : ($persen >= 70 ? 'Hampir Penuh' : 'Tersedia');
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $area->nama_area }}</td>
                <td>{{ $area->kapasitas }} slot</td>
                <td>{{ $area->terisi }} slot</td>
                <td>{{ $area->kapasitas - $area->terisi }} slot</td>
                <td><span class="badge {{ $badgeClass }}">{{ $statusText }}</span></td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:20px;color:#888;">Tidak ada data area parkir</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="ttd">
            <p>Admin,</p>
            <div class="space"></div>
            <p class="nama">{{ auth()->user()->name }}</p>
            <p style="font-size:11px;color:#555;">Administrator</p>
        </div>
    </div>

</body>

</html>