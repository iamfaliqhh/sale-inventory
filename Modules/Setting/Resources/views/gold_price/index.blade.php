@extends('layouts.app')

@section('title', 'Gold Price List')

@section('third_party_stylesheets')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
@endsection

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">Gold Price</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('gold-price.create') }}" class="btn btn-primary">
                            Update Gold Price <i class="bi bi-plus"></i>
                        </a>

                        <hr>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Transaction Price</th>
                                        <th>Trade-In Price</th>
                                        <th>Buyback Price</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($prices as $price)
                                        <tr>
                                            <td>{{ format_currency($price->transaction_price) }}</td>
                                            <td>{{ format_currency($price->trade_in_price) }}</td>
                                            <td>{{ format_currency($price->buyback_price) }}</td>
                                            <td>{{ $price->created_at->format('d M Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
