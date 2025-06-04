@extends('layouts.app')

@section('title', 'Create Gold Price')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('gold-price.index') }}">Gold Price</a></li>
        <li class="breadcrumb-item active">Add</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <form id="gold-price-form" action="{{ route('gold-price.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    @include('utils.alerts')
                    <div class="form-group">
                        <button class="btn btn-primary">Save Gold Price <i class="bi bi-check"></i></button>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Transaction Price</th>
                                            <th>Trade-In Price</th>
                                            <th>Buyback Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input type="text" name="transaction_price" class="form-control" value="{{ old('transaction_price') }}" required>
                                                @error('transaction_price') <div class="text-danger">{{ $message }}</div> @enderror
                                            </td>
                                            <td>
                                                <input type="text" name="trade_in_price" class="form-control" value="{{ old('trade_in_price') }}" required>
                                                @error('trade_in_price') <div class="text-danger">{{ $message }}</div> @enderror
                                            </td>
                                            <td>
                                                <input type="text" name="buyback_price" class="form-control" value="{{ old('buyback_price') }}" required>
                                                @error('buyback_price') <div class="text-danger">{{ $message }}</div> @enderror
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('page_scripts')

    <script src="{{ asset('js/jquery-mask-money.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('input[name="transaction_price"], input[name="trade_in_price"], input[name="buyback_price"]').maskMoney({
                prefix: '{{ settings()->currency->symbol }}',
                thousands: '{{ settings()->currency->thousand_separator }}',
                decimal: '{{ settings()->currency->decimal_separator }}',
                allowZero: true,
            });

            $('#gold-price-form').submit(function () {
                $('input[name="transaction_price"], input[name="trade_in_price"], input[name="buyback_price"]').each(function () {
                    var unmaskedValue = $(this).maskMoney('unmasked')[0];
                    $(this).val(unmaskedValue);
                });
            });
        });
    </script>
@endpush

