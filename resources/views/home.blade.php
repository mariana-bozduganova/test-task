@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Gender</th>
                                <th>Size</th>
                                <th>Price</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subscriptions as $subscription)
                                <tr>
                                    <td>{{ $subscription['gender'] }}</td>
                                    <td>{{ $subscription['size'] }}</td>
                                    <td>{{ $subscription['price'] }}</td>
                                    <td>{{ $subscription['date'] }}</td>
                                    <td>{{ $subscription['status'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $subscriptions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
