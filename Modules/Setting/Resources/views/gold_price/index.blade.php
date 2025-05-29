@extends('layouts.app')

@section('title', 'Gold Price List')

@section('content')
<div class="container-fluid">
    <div class="mb-3">
        <a href="{{ route('gold-price.create') }}" class="btn btn-primary">Add Gold Price</a>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Price</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prices as $price)
                <tr>
                    <td>{{ $price->date }}</td>
                    <td>{{ ucfirst($price->type) }}</td>
                    <td>{{ format_currency($price->price) }}</td>
                    <td>{{ $price->note }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
