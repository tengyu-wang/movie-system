<!DOCTYPE html>
<html>
<head>
    @include('head', ['pageTitle' => 'Login'])
</head>
<body>
    @if (isset(Auth::user()->email))
        <script>window.location = 'movies';</script>
    @endif

    <div class="container box">
        <h1>Movie City System</h1>
        @if ($message = Session::get('error'))
            <div class="alert-box">
                <ul>
                    <li>{{ $message }}</li>
                </ul>
            </div>
        @endif

        @if (count($errors) > 0)
            <div class="alert-box">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="post" action="{{ url('/login/check') }}">
            {{ csrf_field() }}
            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" class="form-control"/>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control"/>
            </div>
            <div class="form-group">
                <input type="submit" name="login" value="Login" class="btn btn-primary"/>
            </div>
        </form>
    </div>
</body>
</html>

