<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Data Kendaraan</title>
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
            font-size: 11px;
            font-weight: bold;
        }

        .badge-masuk {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .badge-keluar {
            background: #e2e3e5;
            color: #383d41;
            border: 1px solid #d6d8db;
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
        <p>Data Kendaraan Terdaftar</p>
    </div>

    <div class="meta">
        <span>
            Total: <strong>{{ $kendaraans->count() }} kendaraan</strong> &nbsp;|&nbsp;
            Masuk: <strong>{{ $kendaraans->where('status','masuk')->count() }}</strong> &nbsp;|&nbsp;
            Keluar: <strong>{{ $kendaraans->where('status','keluar')->count() }}</strong>
        </span>
        <span>Dicetak: <strong>{{ now()->translatedFormat('d F Y, H:i') }} WIB</strong></span>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:40px">#</th>
                <th>Plat Nomor</th>
                <th>Warna</th>
                <th>Jenis Kendaraan</th>
                <th>Tarif/Jam</th>
                <th>Petugas</th>
                <th>Tgl Masuk</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kendaraans as $index => $k)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $k->plat_nomor }}</strong></td>
                <td>{{ $k->warna }}</td>
                <td>{{ $k->tarif->jenis_kendaraan ?? '-' }}</td>
                <td>Rp {{ $k->tarif ? number_format($k->tarif->tarif_per_jam, 0, ',', '.') : '-' }}</td>
                <td>{{ $k->user->name ?? '-' }}</td>
                <td>{{ $k->Created_at ? \Carbon\Carbon::parse($k->Created_at)->format('d M Y') : '-' }}</td>
                <td>
                    <span class="badge badge-{{ $k->status }}">{{ ucfirst($k->status) }}</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center;padding:20px;color:#888;">Tidak ada data kendaraan</td>
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