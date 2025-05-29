@extends('layouts.app')

@section('title', 'Add Gold Price')

@section('content')
<div class="container-fluid">
    <form action="{{ route('gold-price.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Date</label>
            <input type="date" name="date" class="form-control" required value="{{ date('Y-m-d') }}">
        </div>
        <div class="form-group">
            <label>Type</label>
            <select name="type" class="form-control" required>
                <option value="sale">Sale</option>
                <option value="buyback">Buyback</option>
            </select>
        </div>
        <div class="form-group">
            <label>Price (per gram)</label>
            <input type="number" name="price" class="form-control" required step="0.01" min="0">
        </div>
        <div class="form-group">
            <label>Note</label>
            <input type="text" name="note" class="form-control">
        </div>
        <button class="btn btn-success">Save</button>
    </form>
</div>
@endsection
