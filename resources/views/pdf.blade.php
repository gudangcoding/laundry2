@php
    use Carbon\Carbon;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SO No. {{ $salesOrder->id }}</title>
    <style>
        h4 {
            margin: 0;
        }
        .w-full {
            width: 100%;
        }
        .w-half {
            width: 50%;
            padding: 20px;
        }
        .margin-top {
            margin-top: 1.25rem;
        }
        .footer {
            font-size: 0.875rem;
            clear: both;
        }
        table {
            width: 100%;
            border-spacing: 0;
        }
        table.products {
            font-size: 0.875rem;
        }
        table.products tr {
            background-color: white;
        }
        table.products th {
            color: #000000;
            border: solid gray 1px;
            padding: 0.5rem;
        }
        table tr.items {
            background-color: white;
        }
        table tr.items td {
            padding: 0.5rem;
        }
        .total {
            text-align: right;
            margin-top: 1rem;
            font-size: 0.875rem;
            display: flex;
        }
        .summary {
            width: 35%;
            float: right;
            display: flex;
            justify-content: flex-end;
        }
        table.products {
            border-collapse: collapse;
            width: 100%;
        }
        table.products th,
        table.products td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        table.header-table {
            border-collapse: collapse;
            width: 100%;
        }
        table.header-table thead th {
            border-bottom: 2px solid black;
            padding: 8px;
            text-align: left;
        }
        table.header-table tbody td {
            border: none;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="header-top">
        <table class="w-full">
            <tr>
                <td class="w-half">
                    <h2>LAUNDRY NAURA</h2>
                    <h3 style="line-height: 50px;">Jl. Mesjid Al-Ihsan No.89 Jatirahayu</h3>
                </td>
                <td class="w-half">
                    <h3>Kepada:</h3>
                    <table class="header-table">
                        <tr>
                            <td>Tanggal</td>
                            <td>:</td>
                            <td> &nbsp;{{ Carbon::parse($salesOrder->created_at)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td> &nbsp;{{ $salesOrder->customer->name }}</td>
                        </tr>
                        <tr>
                            <td>No HP</td>
                            <td>:</td>
                            <td> &nbsp;{{ $salesOrder->customer->no_hp }}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td> &nbsp;{{ $salesOrder->customer->alamat }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <div class="margin-top">
        <table class="products">
            <tr style="border-bottom: solid gray 1px;">
                <th style="text-align: center">No</th>
                <th style="text-align: left">Nama Produk</th>
                <th style="text-align: center">Qty</th>
                <th style="text-align: center">Satuan</th>
                <th style="text-align: right">Harga</th>
                <th style="text-align: right">SubTotal</th>
            </tr>
            @php
                $no = 1;
            @endphp
            @foreach ($salesOrder->OrderDetail as $detail)
                <tr class="items">
                    <td style="text-align: center">{{ $no++ }}</td>
                    <td style="text-align: left;">{{ $detail->produk->name }}</td>
                    <td style="text-align: center">{{ $detail->qty }}</td>
                    <td style="text-align: center">{{ $detail->satuan }}</td>
                    <td style="text-align: right">{{ number_format($detail->harga) }}</td>
                    <td style="text-align: right">{{ number_format($detail->subtotal) }}</td>
                </tr>
            @endforeach
            <tr style="border-bottom: 1px solid gray">
                <td colspan="5" style="text-align: right">Total Qty:</td>
                <td style="text-align: right">{{ number_format($salesOrder->qty) }}</td>
            </tr>
            <tr style="border-bottom: 1px solid gray">
                <td colspan="5" style="text-align: right">Total Harga:</td>
                <td style="text-align: right">{{ number_format($salesOrder->total) }}</td>
            </tr>
        </table>
    </div>
</body>

</html>
