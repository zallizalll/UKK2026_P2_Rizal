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
            background: #f5f5f5;
            color: #000;
        }

        .struk {
            width: 300px;
            margin: 30px auto;
            padding: 20px;
            background: #fff;
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

        .metode {
            text-align: center;
            margin-top: 8px;
            font-size: 11px;
            color: #555;
        }

        .footer {
            text-align: center;
            margin-top: 16px;
            font-size: 11px;
            color: #777;
        }

        /* ===== TOMBOL ===== */
        .no-print {
            text-align: center;
            margin: 24px auto;
            width: 300px;
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .no-print a,
        .no-print button {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-family: 'Courier New', monospace;
            text-decoration: none;
            font-weight: bold;
            transition: opacity .2s;
        }

        .no-print a:hover,
        .no-print button:hover {
            opacity: .85;
        }

        .btn-print {
            background: #c0392b;
            color: #fff;
        }

        .btn-back {
            background: #444;
            color: #fff;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                background: #fff;
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

    {{-- TOMBOL AKSI --}}
    <div class="no-print">
        <button class="btn-print" onclick="window.print()">
            🖨️ Cetak Struk
        </button>
        <a href="{{ route('petugas.transaksi') }}" class="btn-back">
            ← Kembali
        </a>
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

        @if($transaksi->metode_pembayaran)
        <div class="metode">
            Dibayar via {{ strtoupper($transaksi->metode_pembayaran) }}
        </div>
        @endif

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