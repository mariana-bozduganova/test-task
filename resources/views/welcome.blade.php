<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link href="/css/app.css" rel="stylesheet">
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ url('/login') }}">Login</a>
                        <a href="{{ url('/register') }}">Register</a>
                    @endif
                </div>
            @endif

            <div class="container">
                <div class="row">

                    @include('partials.notifications')

                    <div class="col-md-8 col-md-offset-2">
                        <div class="panel panel-default">
                            <div class="panel-heading">Subscription From</div>
                            <div class="panel-body">
                                <form class="form-horizontal" id="subscription-form" role="form" method="POST" action="{{ url('subscriptions') }}">

                                    {{ csrf_field() }}

                                    <input type="hidden" name="selected_price" id="selected_price" />

                                    <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                                        <label for="W" class="col-md-4 control-label">Gender</label>

                                        <div class="col-md-6">
                                            <div class="radio-inline">
                                                <label for="W">
                                                    <input id="W" class="radio" type="radio" name="gender" value="W" {!! old('gender') == 'W' || ! old('gender') ? 'checked="checked"' : ''!!} onclick="loadSizes('W')">W
                                                </label>
                                            </div>
                                            <div class="radio-inline">
                                                <label for="M">
                                                    <input id="M" class="radio" type="radio" name="gender" value="M" {!! old('gender') == 'M' ? 'checked="checked"' : '' !!} onclick="loadSizes('M')">M
                                                </label>
                                            </div>

                                            @if ($errors->has('gender'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('gender') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('size') ? ' has-error' : '' }}">
                                        <label for="email" class="col-md-4 control-label">Size</label>

                                        <div class="col-md-6">
                                            <select class="form-control" name="size" id="size" onchange="loadPrice(this.value)"></select>

                                            @if ($errors->has('size'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('size') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="price" class="col-md-4 control-label">Price</label>

                                        <div class="col-md-6">
                                            <label id="price" class="form-control-static"></label>
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label for="email" class="col-md-4 control-label">Email</label>

                                        <div class="col-md-6">
                                            @if (Auth::check())
                                                <input type="hidden" name="email" value="{{ Auth::user()->email }}" />
                                                <label id="price" class="form-control-static">{{ Auth::user()->email }}</label>
                                            @else
                                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Enter Your Email" required>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-4">
                                            <button type="submit" class="btn btn-primary">
                                                Submit
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <script>
            var sizes = {!! json_encode($sizes) !!};
            var prices = {!! json_encode($prices) !!};
            var preSelectedSize = "{{ old('size') }}";
        </script>
        <script src="/js/main.js"></script>

    </body>
</html>
