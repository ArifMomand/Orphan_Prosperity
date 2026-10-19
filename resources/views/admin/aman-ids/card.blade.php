<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aman ID Card — {{ $item->aman_id }}</title>
    @vite(['resources/css/app.css'])

    <style>
        /* ---------- Card (real size: 85.6mm x 54mm) ---------- */
        .id-card {
            position: relative;
            width: 85.6mm;
            height: 54mm;
            border-radius: 3.5mm;
            overflow: hidden;
            color: #fff;
            font-family: 'Segoe UI', Arial, sans-serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            box-sizing: border-box;
        }
        .id-front {
            background: linear-gradient(135deg, #2563eb 0%, #1e3a8a 100%);
            padding: 4.5mm 5mm;
        }
        .id-back {
            background: #ffffff;
            color: #1f2937;
            border: 0.3mm solid #cbd5e1;
            padding: 4.5mm 5mm;
        }
        .circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, .08);
        }

        .id-head { display: flex; justify-content: space-between; align-items: center; position: relative; }
        .id-org { display: flex; align-items: center; gap: 1.5mm; font-weight: 700; font-size: 8pt; }
        .id-org svg { width: 4.5mm; height: 4.5mm; }
        .id-type { font-size: 8pt; font-weight: 600; letter-spacing: .3mm; }

        .id-body { position: absolute; left: 5mm; right: 5mm; bottom: 4.5mm; display: flex; justify-content: space-between; align-items: flex-end; gap: 3mm; }
        .id-label { font-size: 5pt; text-transform: uppercase; letter-spacing: .25mm; color: #bfdbfe; margin-bottom: .5mm; }
        .id-name { font-size: 10.5pt; font-weight: 700; line-height: 1.15; max-width: 48mm; }
        .id-number { font-size: 9.5pt; font-weight: 600; letter-spacing: .4mm; margin-top: 2mm; font-family: 'Courier New', monospace; }
        .id-meta { font-size: 5.5pt; color: #dbeafe; margin-top: 1mm; }

        .qr-box { width: 20mm; height: 20mm; background: #fff; padding: 1.2mm; border-radius: 1.8mm; flex-shrink: 0; }
        .qr-box svg { width: 100%; height: 100%; display: block; }

        /* back side */
        .back-title { font-weight: 700; font-size: 8pt; color: #1e3a8a; }
        .back-text { font-size: 6pt; line-height: 1.5; margin-top: 2mm; color: #4b5563; }
        .back-strip { position: absolute; left: 0; right: 0; bottom: 0; height: 6mm; background: linear-gradient(90deg, #2563eb, #1e3a8a); color: #fff; font-size: 5.5pt; display: flex; align-items: center; justify-content: center; letter-spacing: .2mm; }

        /* ---------- Screen preview (bigger) ---------- */
        @media screen {
            body { background: #f3f4f6; }
            .preview { zoom: 3.6; }
        }

        /* ---------- Print: exactly one card per page ---------- */
        @page { size: 85.6mm 54mm; margin: 0; }
        @media print {
            body { background: #fff; margin: 0; }
            .no-print { display: none !important; }
            .preview { zoom: 1; }
            .id-card { border-radius: 0; page-break-after: always; break-after: page; }
            .id-card:last-child { page-break-after: auto; break-after: auto; }
            .sheet { display: block !important; padding: 0 !important; gap: 0 !important; }
        }
    </style>
</head>
<body class="antialiased">

    {{-- Toolbar (hidden when printing) --}}
    <div class="no-print max-w-3xl mx-auto px-6 pt-8 flex flex-wrap items-center justify-between gap-3">
        <div>
            <a href="{{ route('admin.aman-ids.show', $item) }}" class="text-sm text-blue-600 hover:underline">&larr; Back to review</a>
            <h1 class="text-xl font-bold text-gray-900 mt-1">Aman ID card — {{ $item->aman_id }}</h1>
            <p class="text-sm text-gray-500">Click Print, then choose a printer or "Save as PDF". Set margins to <b>None</b> and scale to <b>100%</b>.</p>
        </div>
        <button onclick="window.print()"
                class="rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 shadow">
            Print / Save as PDF
        </button>
    </div>

    <div class="sheet flex flex-col items-center gap-10 py-10">

        {{-- ============ FRONT ============ --}}
        <div class="preview">
            <div class="id-card id-front">
                <div class="circle" style="width:38mm;height:38mm;right:-12mm;top:-14mm;"></div>
                <div class="circle" style="width:46mm;height:46mm;left:-18mm;bottom:-24mm;background:rgba(255,255,255,.05);"></div>

                <div class="id-head">
                    <div class="id-org">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2 4 5v6c0 5 3.4 9.4 8 11 4.6-1.6 8-6 8-11V5l-8-3zm-1 14-4-4 1.4-1.4L11 13.2l4.6-4.6L17 10l-6 6z"/></svg>
                        Orphan Prosperity
                    </div>
                    <div class="id-type">Aman ID</div>
                </div>

                <div class="id-body">
                    <div>
                        <div class="id-label">Name</div>
                        <div class="id-name">{{ $item->full_name }}</div>

                        <div class="id-label" style="margin-top:2.5mm;">Aman ID</div>
                        <div class="id-number" style="margin-top:0;">{{ $item->aman_id }}</div>

                        <div class="id-meta">{{ $item->province }}, {{ $item->country }}</div>
                    </div>

                    <div class="qr-box">
                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                            ->size(200)->margin(0)->errorCorrection('M')->generate($verifyUrl) !!}
                    </div>
                </div>
            </div>
        </div>

        {{-- ============ BACK ============ --}}
        <div class="preview">
            <div class="id-card id-back">
                <div class="back-title">Orphan Prosperity</div>
                <div class="back-text">
                    This card identifies a verified beneficiary registered with Orphan Prosperity.
                    Scan the QR code on the front to confirm that this Aman ID is valid.<br><br>
                    This card is not transferable. If found, please contact Orphan Prosperity.
                </div>
                <div class="back-strip">Orphan Prosperity &bull; Kabul, Afghanistan</div>
            </div>
        </div>
    </div>

</body>
</html>