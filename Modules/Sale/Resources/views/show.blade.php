@extends('layouts.app')

@push('page_css')
    <style>
        @page {
            size: A4;
            margin: 1cm;
        }

        .printable-page:not(:last-child) {
            padding-bottom: 30px;
            margin-bottom: 30px;
            border-bottom: 2px dashed #cccccc;
        }

        @media print {
            .c-sidebar,
            .c-header,
            .c-footer,
            .breadcrumb-item,
            .card-header,
            .d-print-none {
                display: none !important;
            }

            body {
                background: #fff !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .c-wrapper, .c-body, .c-main, .container-fluid {
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }

            .card {
                margin: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }

            .printable {
                width: 100%;
                text-align: center;
            }
            .printable table {
                margin: 15px auto;
                width: 100%;
            }
            
            .printable .disclaimer-section {
                margin-top: 0px;
                padding-left: 14px;
            }

            .footer-tagline {
                display: flex;
                justify-content: space-between;
                width: 100%;
                font-weight: normal;
            }

            .printable-page:not(:last-child) {
                padding-bottom: 0 !important;
                margin-bottom: 0 !important;
                border-bottom: none !important;
                page-break-after: always;
            }
        }

        .footer-tagline {
            display: flex;
            justify-content: space-between;
            width: 100%;
            font-weight: normal;
        }
    </style>
@endpush

@section('title', 'Sales Details')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('sales.index') }}">Sales</a></li>
        <li class="breadcrumb-item active">Details</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex flex-wrap align-items-center">
                        <div>
                            Reference: <strong>{{ $sale->reference }}</strong>
                        </div>
                        <a class="btn btn-sm btn-secondary mfs-auto mfe-1 d-print-none"
                            href="javascript:void(0);" onclick="window.print();">
                            <i class="bi bi-printer"></i> Print
                        </a>
                        <a target="_blank" class="btn btn-sm btn-info mfe-1 d-print-none" href="{{ route('sales.pdf', $sale->id) }}">
                            <i class="bi bi-save"></i> Save
                        </a>
                    </div>

                    <div class="card-body printable" style="background: #fff;">

                        @php
                            $saleDetailsChunks = $sale->saleDetails->chunk(5);
                            $totalPages = count($saleDetailsChunks);
                        @endphp

                        @foreach ($saleDetailsChunks as $pageNumber => $itemsOnPage)
                            <div class="printable-page">

                                <div style="text-align:center; font-size:14px;">
                                    <img src="{{ asset('images/print-header.png') }}" alt="Kedai Emas Aidid Gold" style="width:100%;">
                                    Dimiliki oleh: ASIA KASIH ENTERPRISE (002930393-A)
                                    <div>Alamat: {{ settings()->company_address }}</div>
                                </div>

                                <table style="width:100%;margin-top:20px;margin-bottom:20px;font-size:15px;">
                                    <tr>
                                        <td style="width:50%; text-align: left !important;">
                                            <strong style="text-transform:uppercase;">M/S {{ $customer->customer_name }}</strong><br>
                                            <strong style="text-transform:uppercase;">Phone:</strong> {{ $customer->customer_phone }}
                                        </td>
                                        <td style="width:50%; text-align: right !important;">
                                            <strong>TARIKH:</strong> {{ \Carbon\Carbon::parse($sale->date)->format('d/m/Y') }}<br>
                                            <strong>NO INVOIS:</strong> {{ $sale->reference }}
                                        </td>
                                    </tr>
                                </table>

                                {{-- Gaya tabel inline tidak diubah sesuai permintaan --}}
                                <table style="width:100%; border-collapse:collapse; font-size:15px; border: 1px solid #000;">
                                    <thead>
                                        <tr>
                                            <th style="line-height:2.2; text-align: center; border-bottom: 1px solid #000; border-right: 1px solid #000;">No.</th>
                                            <th style="line-height:2.2; text-align: center; border-bottom: 1px solid #000; border-right: 1px solid #000;;">Jenis Barang</th>
                                            <th style="line-height:2.2; text-align: center; border-bottom: 1px solid #000; border-right: 1px solid #000;">Ketulenan</th>
                                            <th style="line-height:2.2; text-align: center; border-bottom: 1px solid #000; border-right: 1px solid #000;">Berat (g)</th>
                                            <th style="line-height:2.2; text-align: center; border-bottom: 1px solid #000; border-right: 1px solid #000;">Harga Emas ({{ settings()->currency->symbol }}/g)</th>
                                            <th style="line-height:2.2; text-align: center; border-bottom: 1px solid #000; border-right: 1px solid #000;">Upah ({{ settings()->currency->symbol }})</th>
                                            <th style="line-height:2.2; text-align: center; border-bottom: 1px solid #000; border-right: 1px solid #000;">Jumlah ({{ settings()->currency->symbol }})</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($itemsOnPage as $i => $item)
                                        <tr>
                                            <td style="line-height:2.2; text-align: center; border-right: 1px solid #000;">{{ $i + 1 }}</td>
                                            <td style="line-height:2.2; text-align: left; border-right: 1px solid #000; text-transform:uppercase; padding-left:10px">{{ $item->product_name }}</td>
                                            <td style="line-height:2.2; text-align: center;border-right: 1px solid #000;">{{ $item->purity }}</td>
                                            <td style="line-height:2.2; text-align: center;border-right: 1px solid #000;">{{ $item->weight }}</td>
                                            <td style="line-height:2.2; text-align: center;border-right: 1px solid #000;">{{ format_currency($item->unit_price) }}</td>
                                            <td style="line-height:2.2; text-align: center;border-right: 1px solid #000;">{{ format_currency($item->wage ?? 0) }}</td>
                                            <td style="line-height:2.2; text-align: center;border-right: 1px solid #000;">{{ format_currency($item->sub_total) }}</td>
                                        </tr>
                                        @endforeach

                                        @for ($j = count($itemsOnPage); $j < 5; $j++)
                                        <tr>
                                            <td style="line-height:2.2; border-right: 1px solid #000;">&nbsp;</td>
                                            <td style="line-height:2.2; border-right: 1px solid #000;">&nbsp;</td>
                                            <td style="line-height:2.2; border-right: 1px solid #000;">&nbsp;</td>
                                            <td style="line-height:2.2; border-right: 1px solid #000;">&nbsp;</td>
                                            <td style="line-height:2.2; border-right: 1px solid #000;">&nbsp;</td>
                                            <td style="line-height:2.2; border-right: 1px solid #000;">&nbsp;</td>
                                            <td style="line-height:2.2; border-right: 1px solid #000;">&nbsp;</td>
                                        </tr>
                                        @endfor

                                        @if ($pageNumber + 1 == $totalPages)
                                        <tr>
                                            <td style="padding: 4px; border-top: 1px solid #000;">&nbsp;</td>
                                            <td colspan="2" style="padding: 4px; border-top: 1px solid #000; text-align:right;"><strong>JUMLAH BERAT</strong></td>
                                            <td style="padding: 4px; text-align: center; border: 1px solid #000;"><strong>{{ $sale->saleDetails->sum('weight') }}</strong></td>
                                            <td colspan="3" style="padding: 4px; border-top: 1px solid #000;"></td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>

                                <div style="display: flex; justify-content: flex-end;">
                                    <table style="width:100%; margin-top:0px; font-size:15px; border: none;">
                                        <tr>
                                            <td style="width:50%; text-align:left; vertical-align:top;" rowspan="4">
                                                @if($sale->sales_person_name)
                                                    Nama Jurujual: <strong>{{ $sale->sales_person_name ?? '-' }}</strong>
                                                @endif
                                            </td>
                                            <td style="text-align:right !important;">JUMLAH ({{ settings()->currency->symbol }})</td>
                                            <td style="text-align:right !important;">{{ format_currency($sale->saleDetails->sum('sub_total')) }}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right !important;">TRADE IN LAMA ({{ settings()->currency->symbol }})</td>
                                            <td style="text-align:right !important;">{{ format_currency($sale->trade_in_amount ?? 0) }}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right !important;">ADJ ({{ settings()->currency->symbol }})</td>
                                            <td style="text-align:right !important;">{{ format_currency($sale->adjustment_amount ?? 0) }}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right !important;"><strong>TOTAL BAYARAN ({{ settings()->currency->symbol }})</strong></td>
                                            <td style="text-align:right !important;"><strong>{{ format_currency($sale->total_amount) }}</strong></td>
                                        </tr>
                                    </table>
                                </div>



                                <div class="disclaimer-section" style="margin-top:0px; font-size:12px; text-align:center;">
                                    <div class="footer-tagline">SAYA KEDAI EMAS AIDID GOLD MENJUAL BARANG TERSEBUT DENGAN HARGA YANG DI PERSETUJUI</div>
                                    <br>
                                    <div class="footer-tagline">DISCLAIMER:</div>
                                    <div class="footer-tagline">SAYA TELAH MEMERIKSA DAN BERPUAS HATI DENGAN BARANG YANG DI BELI/ DI BAIKI BERADA DALAM KEADAAN BAIK</div>
                                    <br>
                                    <strong>"TERIMA KASIH SILA DATANG LAGI"</strong><br>
                                    <br>
                                    <div class="footer-tagline">
                                        <span>AIDID GOLD SIMBOL KEMEWAHAN ANDA</span>
                                        <span>CITARASA ANDA KEPUASAN KAMI</span>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
