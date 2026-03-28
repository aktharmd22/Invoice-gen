<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $bill->bill_no }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DM Sans', DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1a1a1a;
            background: #ffffff;
        }

        .page { padding: 40px 44px; max-width: 600px; margin: 0 auto; }

        /* ── HEADER ── */
        .header {
            display: table;
            width: 100%;
            padding-bottom: 20px;
            margin-bottom: 20px;
            border-bottom: 1.5px solid #e8e8e8;
        }
        .header-left  { display: table-cell; vertical-align: middle; width: 55%; }
        .header-right { display: table-cell; vertical-align: middle; width: 45%; text-align: right; }

        .logo-img { max-height: 52px; max-width: 130px; object-fit: contain; }

        .shop-name {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #111;
        }
        .shop-tagline {
            font-size: 9px;
            font-weight: 400;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #aaa;
            margin-top: 3px;
        }

        .invoice-label {
            font-size: 9px;
            font-weight: 600;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #aaa;
            margin-bottom: 6px;
        }
        .bill-no {
            font-size: 15px;
            font-weight: 700;
            color: #111;
            letter-spacing: 0.5px;
        }
        .bill-date {
            font-size: 10.5px;
            color: #777;
            margin-top: 4px;
        }

        /* ── SHOP INFO ── */
        .shop-info {
            display: table;
            width: 100%;
            margin-bottom: 22px;
            padding: 12px 16px;
            border: 1px solid #ebebeb;
            border-radius: 6px;
            background: #fafafa;
        }
        .info-col { display: table-cell; vertical-align: top; }
        .info-col + .info-col { padding-left: 16px; border-left: 1px solid #e8e8e8; }
        .info-label {
            font-size: 8.5px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #bbb;
            margin-bottom: 3px;
        }
        .info-value {
            font-size: 10.5px;
            font-weight: 500;
            color: #333;
            line-height: 1.5;
        }
        .info-value a { color: #333; text-decoration: none; }

        /* ── DIVIDER ── */
        .divider { height: 1px; background: #ebebeb; margin-bottom: 22px; }

        /* ── CUSTOMER ── */
        .bill-to-wrap { display: table; width: 100%; margin-bottom: 22px; }
        .bill-to-cell { display: table-cell; vertical-align: top; width: 50%; }
        .bill-to-cell + .bill-to-cell { padding-left: 20px; }
        .field-label {
            font-size: 8.5px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #bbb;
            margin-bottom: 4px;
        }
        .field-value { font-size: 13px; font-weight: 600; color: #111; }
        .field-sub   { font-size: 11px; color: #666; margin-top: 1px; }

        /* ── ITEMS TABLE ── */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table thead tr {
            border-bottom: 2px solid #111;
        }
        .items-table thead th {
            padding: 8px 10px;
            text-align: left;
            font-size: 8.5px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #555;
        }
        .items-table thead th.right  { text-align: right; }
        .items-table thead th.center { text-align: center; }

        .items-table tbody tr { border-bottom: 1px solid #f0f0f0; }
        .items-table tbody tr:last-child { border-bottom: 2px solid #e0e0e0; }

        .items-table tbody td {
            padding: 9px 10px;
            font-size: 11px;
            color: #222;
            vertical-align: middle;
        }
        .items-table tbody td.right  { text-align: right; }
        .items-table tbody td.center { text-align: center; }
        .items-table tbody td.sno    { color: #bbb; font-size: 10px; width: 26px; }
        .item-name { font-weight: 500; }

        /* ── TOTALS ── */
        .totals-wrap { display: table; width: 100%; margin-bottom: 16px; }
        .totals-spacer { display: table-cell; width: 55%; }
        .totals-box { display: table-cell; width: 45%; vertical-align: top; }

        .total-row {
            display: table;
            width: 100%;
            padding: 5px 0;
        }
        .total-lbl { display: table-cell; font-size: 11px; color: #888; }
        .total-val { display: table-cell; text-align: right; font-size: 11px; font-weight: 500; color: #333; }

        .grand-row {
            display: table;
            width: 100%;
            margin-top: 10px;
            padding: 10px 14px;
            border: 2px solid #111;
            border-radius: 6px;
        }
        .grand-lbl { display: table-cell; font-size: 11px; font-weight: 700; color: #111; letter-spacing: 1px; text-transform: uppercase; }
        .grand-val { display: table-cell; text-align: right; font-size: 16px; font-weight: 700; color: #111; }

        /* ── AMOUNT IN WORDS ── */
        .amount-words {
            margin-top: 12px;
            padding: 8px 12px;
            border: 1px dashed #ddd;
            border-radius: 4px;
            font-size: 10px;
            color: #777;
            font-style: italic;
            line-height: 1.5;
        }

        /* ── FOOTER ── */
        .footer {
            margin-top: 28px;
            padding-top: 16px;
            border-top: 1px solid #e8e8e8;
        }
        .footer-inner { display: table; width: 100%; }
        .footer-left  { display: table-cell; vertical-align: bottom; width: 60%; }
        .footer-right { display: table-cell; vertical-align: bottom; width: 40%; text-align: right; }

        .footer-link { font-size: 10px; color: #666; margin-bottom: 4px; }
        .footer-link a { color: #444; text-decoration: none; }

        .thank-you {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #111;
        }
        .tagline {
            font-size: 9px;
            color: #aaa;
            letter-spacing: 1px;
            margin-top: 3px;
        }

        .meta-line {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #ccc;
            letter-spacing: 3px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
<div class="page">

    {{-- ── HEADER ── --}}
    <div class="header">
        <div class="header-left">
            @if($shopLogo)
                <img src="{{ public_path('storage/'.$shopLogo) }}" class="logo-img" alt="Logo">
            @else
                <div class="shop-name">{{ $shopName }}</div>
                <div class="shop-tagline">Fashion Store</div>
            @endif
        </div>
        <div class="header-right">
            <div class="invoice-label">Invoice</div>
            <div class="bill-no">{{ $bill->bill_no }}</div>
            <div class="bill-date">{{ $bill->date->format('d M Y') }}</div>
        </div>
    </div>

    {{-- ── SHOP INFO ── --}}
    @if($shopAddress || $shopPhone || $shopInsta)
    <div class="shop-info">
        @if($shopAddress)
        <div class="info-col" style="width:45%">
            <div class="info-label">Address</div>
            <div class="info-value">
                @if($shopMapsUrl)
                    <a href="{{ $shopMapsUrl }}">{{ $shopAddress }}</a>
                @else
                    {{ $shopAddress }}
                @endif
            </div>
        </div>
        @endif
        @if($shopPhone)
        <div class="info-col" style="width:28%; padding-left:14px; border-left:1px solid #e8e8e8;">
            <div class="info-label">Phone</div>
            <div class="info-value">{{ $shopPhone }}</div>
        </div>
        @endif
        @if($shopInsta)
        <div class="info-col" style="width:27%; padding-left:14px; border-left:1px solid #e8e8e8;">
            <div class="info-label">Instagram</div>
            <div class="info-value">
                @php $instaHandle = ltrim(basename(rtrim($shopInsta, '/')), '@'); @endphp
                <a href="{{ $shopInsta }}">@{{ $instaHandle }}</a>
            </div>
        </div>
        @endif
    </div>
    @endif

    {{-- ── CUSTOMER ── --}}
    <div class="bill-to-wrap">
        <div class="bill-to-cell">
            <div class="field-label">Bill To</div>
            <div class="field-value">{{ $bill->customer_name }}</div>
        </div>
        @if($bill->phone)
        <div class="bill-to-cell">
            <div class="field-label">Phone</div>
            <div class="field-sub" style="font-size:12px; color:#333; margin-top:0;">{{ $bill->phone }}</div>
        </div>
        @endif
    </div>

    {{-- ── ITEMS ── --}}
    <table class="items-table">
        <thead>
            <tr>
                <th style="width:26px">#</th>
                <th>Description</th>
                <th class="center" style="width:42px">Qty</th>
                <th class="right" style="width:90px">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bill->items->where('is_returned', false) as $i => $item)
                <tr>
                    <td class="sno">{{ $i + 1 }}</td>
                    <td><span class="item-name">{{ $item->item_name }}</span></td>
                    <td class="center">{{ $item->quantity }}</td>
                    <td class="right">Rs. {{ number_format($item->final_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ── TOTALS ── --}}
    <div class="totals-wrap">
        <div class="totals-spacer"></div>
        <div class="totals-box">
            <div class="total-row">
                <div class="total-lbl">Items</div>
                <div class="total-val">{{ $bill->items->where('is_returned', false)->count() }}</div>
            </div>
            <div class="grand-row">
                <div class="grand-lbl">Total</div>
                <div class="grand-val">Rs. {{ number_format($bill->grand_total, 2) }}</div>
            </div>
        </div>
    </div>

    {{-- ── AMOUNT IN WORDS ── --}}
    @php $words = \App\Helpers\NumberToWords::convert((int) $bill->grand_total); @endphp
    <div class="amount-words">
        <strong style="color:#555; font-style:normal;">In Words:</strong> {{ ucfirst($words) }} only
    </div>

    {{-- ── FOOTER ── --}}
    <div class="footer">
        <div class="footer-inner">
            <div class="footer-left">
                @if($shopInsta)
                    <div class="footer-link">
                        @php $instaHandle = $instaHandle ?? ltrim(basename(rtrim($shopInsta, '/')), '@'); @endphp
                        <a href="{{ $shopInsta }}">&#9670; instagram.com/{{ $instaHandle }}</a>
                    </div>
                @endif
                @if($shopMapsUrl)
                    <div class="footer-link">
                        <a href="{{ $shopMapsUrl }}">&#9670; Find us on Google Maps</a>
                    </div>
                @endif
                @if($shopPhone)
                    <div class="footer-link">&#9670; {{ $shopPhone }}</div>
                @endif
            </div>
            <div class="footer-right">
                <div class="thank-you">Thank You</div>
                <div class="tagline">Visit Again</div>
            </div>
        </div>
        <div class="meta-line">{{ $bill->bill_no }} &nbsp;&bull;&nbsp; {{ $bill->date->format('d-m-Y') }}</div>
    </div>

</div>
</body>
</html>
