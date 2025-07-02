@extends('layouts.app')

@push('page_css')
    <style>
        @page {
            size: A4;
            margin: 1cm;
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
                margin-top: 25px;
                padding: 10px;
            }
            .footer-tagline {
                display: flex;
                justify-content: space-between;
                width: 100%;
                font-weight: normal; 
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
                    {{-- This card-header will be hidden on print --}}
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
                    
                    {{-- This is the main printable area --}}
                    <div class="card-body printable" style="background: #fff;">
                        <div style="text-align:center; font-size:15px;">
                            <img src="{{ asset('images/print-header.png') }}" alt="Kedai Emas Aidid Gold" style="width:100%;">
                            Dimiliki oleh: ASIA KASIH ENTERPRISE (002930393-A)
                            <div>Alamat: {{ settings()->company_address }}</div>
                        </div>

                        {{-- Customer and Invoice Info Table --}}
                        <table style="width:100%;margin-top:20px;margin-bottom:20px;font-size:15px;">
                            <tr>
                                <td style="width:50%; text-align: left !important;">
                                    <strong>M/S {{ $customer->customer_name }}</strong><br>
                                    Phone: {{ $customer->customer_phone }}
                                </td>
                                <td style="width:50%; text-align: right !important;">
                                    <strong>TARIKH:</strong> {{ \Carbon\Carbon::parse($sale->date)->format('d/m/Y') }}<br>
                                    <strong>NO INVOIS:</strong> {{ $sale->reference }}
                                </td>
                            </tr>
                        </table>

                        {{-- Sale Details Table --}}
                        <table style="width:100%;border-collapse:collapse;font-size:15px;">
                            <thead>
                                <tr>
                                    <th style="border:1px solid #000;">No.</th>
                                    <th style="border:1px solid #000;">Jenis Barang</th>
                                    <th style="border:1px solid #000;">Ketulenan</th>
                                    <th style="border:1px solid #000;">Berat (g)</th>
                                    <th style="border:1px solid #000;">Harga Emas ({{ settings()->currency->symbol }}/g)</th>
                                    <th style="border:1px solid #000;">Upah ({{ settings()->currency->symbol }})</th>
                                    <th style="border:1px solid #000;">Jumlah ({{ settings()->currency->symbol }})</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sale->saleDetails as $i => $item)
                                <tr>
                                    <td style="border:1px solid #000;">{{ $i + 1 }}</td>
                                    <td style="border:1px solid #000;">{{ $item->product_name }}</td>
                                    <td style="border:1px solid #000;">{{ $item->purity }}</td>
                                    <td style="border:1px solid #000;">{{ $item->weight }}</td>
                                    <td style="border:1px solid #000;">{{ format_currency($item->unit_price) }}</td>
                                    <td style="border:1px solid #000;">{{ format_currency($item->wage ?? 0) }}</td>
                                    <td style="border:1px solid #000;">{{ format_currency($item->sub_total) }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3" style="border:1px solid #000;"><strong>JUMLAH BERAT</strong></td>
                                    <td style="border:1px solid #000;"><strong>{{ $sale->saleDetails->sum('weight') }}</strong></td>
                                    <td colspan="3" style="border:1px solid #000;"></td>
                                </tr>
                            </tbody>
                        </table>
                        
                        {{-- Totals Table Wrapper --}}
                        <div style="width: 100%; display: flex; justify-content: flex-end;">
                            <table style="width:50%; margin-top:10px; font-size:15px; border: none;">
                                <tr>
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

                        <!-- bagian ini baru beberapa yg nyambung ke database ga tau apa yg mau di sambungin soal nya :) -->
                        <div class="disclaimer-section" style="margin-top:30px;font-size:13px;text-align:center;">
                            <div class="footer-tagline">
                                <span>Nama Jurujual: {{ $sale->user->name ?? '-' }}</span><br>
                            </div>
                        </div>

                        <div class="disclaimer-section" style="margin-top:30px;font-size:13px;text-align:center;">
                            <div class="footer-tagline">SAYA KEDAI EMAS AIDID GOLD MENJUAL BARANG TERSEBUT DENGAN HARGA YANG DI PERSETUJUI<br></div>
                            <br>
                            <div class="footer-tagline">DISCLAIMER:</div>
                            <div class="footer-tagline">SAYA TELAH MEMERIKSA DAN BERPUAS HATI DENGAN BARANG YANG DI BELI/ DI BAIKI BERADA DALAM KEADAAN BAIK<br></div>
                            <br>
                            <strong>"TERIMA KASIH SILA DATANG LAGI"</strong><br>
                            <br>
                            <div class="footer-tagline">
                                <span>AIDID GOLD SIMBOL KEMEWAHAN ANDA</span>
                                <span>CITARASA ANDA KEPUASAN KAMI</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection