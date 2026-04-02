<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $bill->bill_no }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,400&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            font-size: 11px;
            color: #1a1a1a;
            background: #ffffff;
        }

        .page {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 36px 40px;
            position: relative;
        }

        /* ── WATERMARK (centre of content) ── */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 320px;
            height: 320px;
            margin-top: -160px;
            margin-left: -160px;
            text-align: center;
            z-index: 0;
            opacity: 0.05;
        }
        .watermark img  { width: 320px; height: 320px; object-fit: contain; }
        .watermark-text {
            font-family: 'DM Sans', sans-serif;
            font-size: 86px;
            font-weight: 800;
            letter-spacing: 8px;
            text-transform: uppercase;
            color: #111;
            line-height: 1;
        }

        /* ── All content sits above watermark ── */
        .content { position: relative; z-index: 1; }

        /* ── HEADER ── */
        .header {
            display: table;
            width: 100%;
            padding-bottom: 18px;
            margin-bottom: 0;
        }
        .header-left  { display: table-cell; vertical-align: middle; width: 50%; }
        .header-right { display: table-cell; vertical-align: middle; width: 50%; text-align: right; }

        .logo-img { max-height: 58px; max-width: 140px; object-fit: contain; }

        .shop-name-text {
            font-family: 'DM Sans', sans-serif;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: #111;
        }
        .shop-tagline {
            font-family: 'DM Sans', sans-serif;
            font-size: 8px;
            font-weight: 400;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #aaa;
            margin-top: 3px;
        }
        .invoice-label {
            font-family: 'DM Sans', sans-serif;
            font-size: 8px;
            font-weight: 600;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: #bbb;
            margin-bottom: 4px;
        }
        .bill-no {
            font-family: 'DM Sans', sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: #111;
        }
        .bill-date {
            font-family: 'DM Sans', sans-serif;
            font-size: 10px;
            color: #888;
            margin-top: 3px;
            font-weight: 400;
        }

        /* ── GOLD ACCENT LINE ── */
        .accent-line {
            height: 2px;
            background: #c8a96e;
            border-radius: 2px;
            margin-bottom: 18px;
        }

        /* ── INFO STRIP (two columns) ── */
        .info-strip {
            display: table;
            width: 100%;
            border: 1px solid #ebebeb;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 22px;
        }
        .info-col-left {
            display: table-cell;
            width: 55%;
            padding: 14px 18px;
            vertical-align: top;
            background: #fafaf9;
            border-right: 1px solid #ebebeb;
        }
        .info-col-right {
            display: table-cell;
            width: 45%;
            padding: 14px 18px;
            vertical-align: top;
            background: #fff;
        }
        .col-label {
            font-family: 'DM Sans', sans-serif;
            font-size: 7.5px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #c8a96e;
            margin-bottom: 8px;
        }
        .info-line {
            font-family: 'DM Sans', sans-serif;
            font-size: 10px;
            color: #444;
            line-height: 1.8;
            font-weight: 400;
        }
        .info-line a { color: #444; text-decoration: none; }
        .customer-name {
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            font-weight: 700;
            color: #111;
            margin-bottom: 5px;
        }
        .customer-phone {
            font-family: 'DM Sans', sans-serif;
            font-size: 11px;
            color: #666;
            font-weight: 400;
        }

        /* ── ITEMS TABLE ── */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }
        .items-table thead tr {
            border-bottom: 1.5px solid #111;
        }
        .items-table thead th {
            font-family: 'DM Sans', sans-serif;
            padding: 8px 8px;
            text-align: left;
            font-size: 8px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #777;
        }
        .items-table thead th.right  { text-align: right; }
        .items-table thead th.center { text-align: center; }

        .items-table tbody tr { border-bottom: 1px solid #f2f2f2; }
        .items-table tbody tr:last-child { border-bottom: 1.5px solid #e0e0e0; }

        .items-table tbody td {
            font-family: 'DM Sans', sans-serif;
            padding: 10px 8px;
            font-size: 11px;
            color: #222;
            vertical-align: middle;
            font-weight: 400;
        }
        .items-table tbody td.right  { text-align: right; }
        .items-table tbody td.center { text-align: center; }
        .items-table tbody td.sno    { color: #ccc; font-size: 10px; width: 22px; }
        .item-name {
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            color: #111;
        }

        /* ── TOTALS ── */
        .totals-wrap    { display: table; width: 100%; margin-top: 0; }
        .totals-spacer  { display: table-cell; width: 50%; }
        .totals-box     { display: table-cell; width: 50%; vertical-align: top; padding-top: 12px; }

        .total-row {
            display: table;
            width: 100%;
            padding: 4px 0;
        }
        .total-lbl {
            display: table-cell;
            font-family: 'DM Sans', sans-serif;
            font-size: 10.5px;
            color: #999;
            font-weight: 400;
        }
        .total-val {
            display: table-cell;
            text-align: right;
            font-family: 'DM Sans', sans-serif;
            font-size: 10.5px;
            font-weight: 500;
            color: #444;
        }

        .grand-row {
            display: table;
            width: 100%;
            margin-top: 10px;
            padding: 12px 16px;
            border: 2px solid #c8a96e;
            border-radius: 6px;
        }
        .grand-lbl {
            display: table-cell;
            font-family: 'DM Sans', sans-serif;
            font-size: 9px;
            font-weight: 700;
            color: #c8a96e;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .grand-val {
            display: table-cell;
            text-align: right;
            font-family: 'DM Sans', sans-serif;
            font-size: 18px;
            font-weight: 800;
            color: #111;
        }

        /* ── AMOUNT IN WORDS ── */
        .amount-words {
            margin-top: 14px;
            padding: 9px 14px;
            border-left: 3px solid #c8a96e;
            background: #fffcf5;
            border-radius: 0 4px 4px 0;
            font-family: 'DM Sans', sans-serif;
            font-size: 10px;
            color: #777;
            font-style: italic;
            font-weight: 400;
        }
        .words-label {
            font-family: 'DM Sans', sans-serif;
            font-style: normal;
            font-weight: 600;
            color: #c8a96e;
        }

        /* ── RETURN NOTICE ── */
        .return-notice {
            margin-top: 16px;
            padding: 11px 16px;
            background: #fffbf0;
            border: 1.5px solid #c8a96e;
            border-radius: 6px;
            display: table;
            width: 100%;
        }
        .return-icon { display: table-cell; vertical-align: middle; width: 28px; font-size: 18px; color: #c8a96e; }
        .return-text { display: table-cell; vertical-align: middle; }
        .return-policy-label {
            font-family: 'DM Sans', sans-serif;
            font-size: 7.5px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #c8a96e;
            margin-bottom: 2px;
        }
        .return-until {
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            font-weight: 600;
            color: #111;
        }
        .return-until strong {
            font-family: 'DM Sans', sans-serif;
            font-weight: 800;
            color: #111;
        }

        /* ── RETURNED ITEMS TABLE ── */
        .returned-section {
            margin-top: 16px;
            border: 1px solid #ffd0d0;
            border-radius: 6px;
            overflow: hidden;
        }
        .returned-header {
            background: #fff5f5;
            padding: 8px 14px;
            font-family: 'DM Sans', sans-serif;
            font-size: 7.5px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #c00;
        }
        .returned-table { width: 100%; border-collapse: collapse; }
        .returned-table td {
            font-family: 'DM Sans', sans-serif;
            padding: 7px 14px;
            font-size: 10.5px;
            color: #666;
            border-top: 1px solid #fde8e8;
            font-weight: 400;
        }
        .returned-table td.right { text-align: right; }
        .returned-table td.center { text-align: center; }
        .returned-table td.name { font-weight: 500; color: #444; text-decoration: line-through; }
        .returned-footer {
            background: #fff5f5;
            padding: 7px 14px;
            display: table;
            width: 100%;
        }
        .returned-footer-lbl {
            display: table-cell;
            font-family: 'DM Sans', sans-serif;
            font-size: 10px;
            font-weight: 600;
            color: #c00;
        }
        .returned-footer-val {
            display: table-cell;
            text-align: right;
            font-family: 'DM Sans', sans-serif;
            font-size: 11px;
            font-weight: 700;
            color: #c00;
        }

        /* ── FOOTER ── */
        .footer {
            margin-top: 22px;
            padding-top: 16px;
            border-top: 1px solid #ebebeb;
        }
        .footer-inner { display: table; width: 100%; }
        .footer-left  { display: table-cell; vertical-align: bottom; width: 60%; }
        .footer-right { display: table-cell; vertical-align: bottom; width: 40%; text-align: right; }

        .footer-link {
            font-family: 'DM Sans', sans-serif;
            font-size: 9.5px;
            color: #888;
            margin-bottom: 4px;
            font-weight: 400;
        }
        .footer-link a {
            font-family: 'DM Sans', sans-serif;
            color: #555;
            text-decoration: none;
            font-weight: 500;
        }
        .thank-you {
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 800;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #111;
        }
        .tagline {
            font-family: 'DM Sans', sans-serif;
            font-size: 8px;
            color: #c8a96e;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 3px;
            font-weight: 500;
        }
        .meta-line {
            margin-top: 14px;
            text-align: center;
            font-family: 'DM Sans', sans-serif;
            font-size: 8px;
            color: #ccc;
            letter-spacing: 4px;
            text-transform: uppercase;
            font-weight: 400;
        }
    </style>
</head>
<body>
<div class="page">

    {{-- ── WATERMARK (top centre, behind all content) ── --}}
    <div class="watermark">
        @if($shopLogo)
            <img src="{{ public_path('storage/'.$shopLogo) }}" alt="">
        @else
            <div class="watermark-text">{{ strtoupper(substr($shopName, 0, 2)) }}</div>
        @endif
    </div>

    <div class="content">

        {{-- ── HEADER ── --}}
        <div class="header">
            <div class="header-left">
                @if($shopLogo)
                    <img src="{{ public_path('storage/'.$shopLogo) }}" class="logo-img" alt="Logo">
                @else
                    <div class="shop-name-text">{{ $shopName }}</div>
                    <div class="shop-tagline">Men's Fashion</div>
                @endif
            </div>
            <div class="header-right">
                <div class="invoice-label">Invoice</div>
                <div class="bill-no">{{ $bill->bill_no }}</div>
                <div class="bill-date">{{ $bill->date->format('d M Y') }}</div>
            </div>
        </div>
        <div class="accent-line"></div>

        {{-- ── INFO STRIP ── --}}
        <div class="info-strip">
            <div class="info-col-left">
                <div class="col-label">Store Info</div>
                @if($shopAddress)
                    <div class="info-line">
                        @if($shopMapsUrl)
                            <a href="{{ $shopMapsUrl }}">{{ $shopAddress }}</a>
                        @else
                            {{ $shopAddress }}
                        @endif
                    </div>
                @endif
                @if($shopPhone)
                    <div class="info-line" style="margin-top:3px;">{{ $shopPhone }}</div>
                @endif
                @if($shopInsta)
                    @php $handle = ltrim(basename(rtrim($shopInsta, '/')), '@'); @endphp
                    <div class="info-line" style="margin-top:2px;">
                        <a href="{{ $shopInsta }}">&#64;{{ $handle }}</a>
                    </div>
                @endif
            </div>
            <div class="info-col-right">
                <div class="col-label">Bill To</div>
                <div class="customer-name">{{ $bill->customer_name }}</div>
                @if($bill->phone)
                    <div class="customer-phone">{{ $bill->phone }}</div>
                @endif
            </div>
        </div>

        {{-- ── ITEMS ── --}}
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width:22px">#</th>
                    <th>Description</th>
                    <th class="right" style="width:80px">Unit Price</th>
                    <th class="center" style="width:42px">Qty</th>
                    <th class="right" style="width:90px">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bill->items->where('is_returned', false) as $i => $item)
                    <tr>
                        <td class="sno">{{ $i + 1 }}</td>
                        <td><span class="item-name">{{ $item->item_name }}</span></td>
                        <td class="right" style="color:#888;">Rs. {{ number_format($item->original_price, 2) }}</td>
                        <td class="center">{{ $item->quantity }}</td>
                        <td class="right">Rs. {{ number_format($item->final_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- ── TOTALS ── --}}
        @php
            $totalRefunded = $bill->returns->sum('total_refund');
            $netAmount     = $bill->grand_total - $totalRefunded;
        @endphp
        <div class="totals-wrap">
            <div class="totals-spacer"></div>
            <div class="totals-box">
                <div class="total-row">
                    <div class="total-lbl">Items</div>
                    <div class="total-val">{{ $bill->items->where('is_returned', false)->count() }}</div>
                </div>
                @if($totalRefunded > 0)
                    <div class="total-row">
                        <div class="total-lbl">Gross Total</div>
                        <div class="total-val">Rs. {{ number_format($bill->grand_total, 2) }}</div>
                    </div>
                    <div class="total-row" style="color:#c00;">
                        <div class="total-lbl">Returns</div>
                        <div class="total-val">- Rs. {{ number_format($totalRefunded, 2) }}</div>
                    </div>
                @endif
                <div class="grand-row">
                    <div class="grand-lbl">{{ $totalRefunded > 0 ? 'Net Amount' : 'Total' }}</div>
                    <div class="grand-val">Rs. {{ number_format($netAmount, 2) }}</div>
                </div>
            </div>
        </div>

        {{-- ── PAYMENT METHOD ── --}}
        <div style="margin-top:10px; text-align:right;">
            <span style="font-family:'DM Sans',sans-serif; font-size:8px; font-weight:700; letter-spacing:2px; text-transform:uppercase; color:#999; background:#f5f5f5; padding:4px 10px; border-radius:3px;">PAID VIA &nbsp;<span style="color:#111; font-size:10px; font-weight:900; letter-spacing:1px;">{{ strtoupper($bill->payment_method) }}</span></span>
        </div>

        {{-- ── AMOUNT IN WORDS ── --}}
        @php $words = \App\Helpers\NumberToWords::convert((int) $netAmount); @endphp
        <div class="amount-words">
            <span class="words-label">In Words: </span>{{ ucfirst($words) }} only
        </div>

        {{-- ── RETURNED ITEMS (only if returns exist) ── --}}
        @if($bill->returns->isNotEmpty())
            <div class="returned-section">
                <div class="returned-header">&#8617; Returned Items</div>
                <table class="returned-table">
                    @foreach($bill->returns as $ret)
                        @foreach($ret->items as $ri)
                            <tr>
                                <td style="width:22px; color:#ccc; font-size:10px;">{{ $loop->iteration }}</td>
                                <td class="name">{{ $ri->billItem->item_name }}</td>
                                <td class="center" style="width:42px;">{{ $ri->billItem->quantity }}</td>
                                <td class="right" style="width:90px; color:#c00;">- Rs. {{ number_format($ri->refund_amount, 2) }}</td>
                            </tr>
                        @endforeach
                        @if($bill->returns->count() > 1)
                            <tr>
                                <td colspan="3" style="color:#aaa; font-size:9px; padding-top:2px; padding-bottom:2px;">
                                    Returned on {{ $ret->return_date->format('d M Y') }}
                                    @if($ret->reason) — {{ $ret->reason }} @endif
                                </td>
                                <td class="right" style="color:#c00; font-size:9px;">- Rs. {{ number_format($ret->total_refund, 2) }}</td>
                            </tr>
                        @endif
                    @endforeach
                </table>
                <div class="returned-footer">
                    <div class="returned-footer-lbl">
                        Total Refunded
                        @if($bill->returns->first()->reason && $bill->returns->count() === 1)
                            <span style="font-weight:400; color:#aaa; font-size:9px;">
                                — {{ $bill->returns->first()->reason }}
                            </span>
                        @endif
                    </div>
                    <div class="returned-footer-val">- Rs. {{ number_format($totalRefunded, 2) }}</div>
                </div>
            </div>
        @endif

        {{-- ── RETURN NOTICE ── --}}
        <div class="return-notice">
            <div class="return-icon" style="font-size:26px; color:#c8a96e; font-weight:700;">*</div>
            <div class="return-text">
                <div class="return-policy-label">Return Policy</div>
                <div class="return-until">Returns accepted until &nbsp;<strong>{{ $returnUntil }}</strong></div>
            </div>
        </div>

        {{-- ── FOOTER ── --}}
        @php $handle = $handle ?? ($shopInsta ? ltrim(basename(rtrim($shopInsta,'/')), '@') : ''); @endphp
        <div class="footer">
            <div class="footer-inner">
                <div class="footer-left">
                    @if($shopInsta)
                        <div class="footer-link">
                            <img src="{{ public_path('images/icons/instagram.png') }}" style="width:12px;height:12px;vertical-align:middle;margin-right:5px;">
                            <a href="{{ $shopInsta }}">instagram.com/{{ $handle }}</a>
                        </div>
                    @endif
                    @if($shopMapsUrl)
                        <div class="footer-link">
                            <img src="{{ public_path('images/icons/maps.png') }}" style="width:12px;height:12px;vertical-align:middle;margin-right:5px;">
                            <a href="{{ $shopMapsUrl }}">Find us on Google Maps</a>
                        </div>
                    @endif
                    @if($shopPhone)
                        <div class="footer-link">
                            <img src="{{ public_path('images/icons/phone.png') }}" style="width:12px;height:12px;vertical-align:middle;margin-right:5px;">
                            <span>{{ $shopPhone }}</span>
                        </div>
                    @endif
                </div>
                <div class="footer-right">
                    <div class="thank-you">Thank You</div>
                    <div class="tagline">Visit Again</div>
                </div>
            </div>
            <div class="meta-line">{{ $bill->bill_no }} &nbsp;&bull;&nbsp; {{ $bill->date->format('d-m-Y') }}</div>
        </div>

    </div>{{-- end .content --}}
</div>
</body>
</html>
