<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Struk Parkir</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 13px;
            background: #fff;
            color: #000;
        }

        .struk {
            width: 300px;
            margin: 30px auto;
            padding: 20px;
            border: 1px dashed #999;
        }

        .header {
            text-align: center;
            margin-bottom: 16px;
        }

        .header h2 {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .header p {
            font-size: 11px;
            color: #555;
        }

        .divider {
            border-top: 1px dashed #999;
            margin: 10px 0;
        }

        .row-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
        }

        .row-item .label {
            color: #555;
        }

        .row-item .value {
            font-weight: bold;
            text-align: right;
        }

        .total {
            background: #f0f0f0;
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
        }

        .total .row-item .value {
            font-size: 16px;
            color: #000;
        }

        .footer {
            text-align: center;
            margin-top: 16px;
            font-size: 11px;
            color: #777;
        }

        .no-print {
            text-align: center;
            margin: 20px auto;
            width: 300px;
        }

        .no-print button {
            padding: 8px 20px;
            margin: 0 4px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
        }

        .btn-print {
            background: #c0392b;
            color: #fff;
        }

        .btn-back {
            background: #555;
            color: #fff;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                margin: 0;
            }

            .struk {
                border: none;
                margin: 0 auto;
            }
        }
    </style>
</head>

<body>

    <div class="no-print">
        <button class="btn-print" onclick="window.print()">🖨️ Cetak Struk</button>
        <button class="btn-back" onclick="window.history.back()">← Kembali</button>
    </div>

    <div class="struk">

        {{-- HEADER --}}
        <div class="header">
            <h2>SISTEM PARKIR</h2>
            <p>Struk Pembayaran Parkir</p>
            <p>{{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }}</p>
        </div>

        <div class="divider"></div>

        {{-- INFO KENDARAAN --}}
        <div class="row-item">
            <span class="label">No. Transaksi</span>
            <span class="value">#{{ str_pad($transaksi->id_transaksi, 5, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="row-item">
            <span class="label">Plat Nomor</span>
            <span class="value">{{ $transaksi->kendaraan->plat_nomor ?? '-' }}</span>
        </div>
        <div class="row-item">
            <span class="label">Warna</span>
            <span class="value">{{ $transaksi->kendaraan->warna ?? '-' }}</span>
        </div>
        <div class="row-item">
            <span class="label">Jenis</span>
            <span class="value">{{ $transaksi->kendaraan->tarif->jenis_kendaraan ?? '-' }}</span>
        </div>
        <div class="row-item">
            <span class="label">Area</span>
            <span class="value">{{ $transaksi->area->nama_area ?? '-' }}</span>
        </div>

        <div class="divider"></div>

        {{-- WAKTU --}}
        <div class="row-item">
            <span class="label">Masuk</span>
            <span class="value">{{ \Carbon\Carbon::parse($transaksi->waktu_masuk)->format('H:i, d M Y') }}</span>
        </div>
        <div class="row-item">
            <span class="label">Keluar</span>
            <span class="value">{{ \Carbon\Carbon::parse($transaksi->waktu_keluar)->format('H:i, d M Y') }}</span>
        </div>
        <div class="row-item">
            <span class="label">Durasi</span>
            <span class="value">{{ $transaksi->durasi }}</span>
        </div>

        <div class="divider"></div>

        {{-- BIAYA --}}
        <div class="row-item">
            <span class="label">Tarif/Jam</span>
            <span class="value">Rp {{ number_format($transaksi->tarif->tarif_per_jam ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="row-item">
            <span class="label">Durasi (dibulatkan)</span>
            <span class="value">{{ $transaksi->durasi_jam }} jam</span>
        </div>

        <div class="total">
            <div class="row-item">
                <span class="label">TOTAL BAYAR</span>
                <span class="value">Rp {{ number_format($transaksi->biaya_total ?? 0, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="divider"></div>

        {{-- PETUGAS --}}
        <div class="row-item">
            <span class="label">Petugas</span>
            <span class="value">{{ $transaksi->user->name ?? '-' }}</span>
        </div>

        <div class="footer">
            <p>Terima kasih telah menggunakan</p>
            <p>layanan parkir kami!</p>
        </div>

    </div>

</body>

</html>