@extends('layouts.app')

@section('title', 'Sales Person Details')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('sales_person.index') }}">Sales Person</a></li>
        <li class="breadcrumb-item active">Details</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Sales Person Name</th>
                                    <td>{{ $sales_person->sales_person_name }}</td>
                                </tr>
                                <tr>
                                    <th>Sales Person Email</th>
                                    <td>{{ $sales_person->sales_person_email }}</td>
                                </tr>
                                <tr>
                                    <th>Sales Person Phone</th>
                                    <td>{{ $sales_person->sales_person_phone }}</td>
                                </tr>
                                <tr>
                                    <th>City</th>
                                    <td>{{ $sales_person->city }}</td>
                                </tr>
                                <tr>
                                    <th>Country</th>
                                    <td>{{ $sales_person->country }}</td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td>{{ $sales_person->address }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

