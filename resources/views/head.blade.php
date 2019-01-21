<title>{{ $pageTitle }}</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css?v={{ time() }}" />
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js?v={{ time() }}"></script>
<link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-nn4HPE8lTHyVtfCBi5yW9d20FjT8BJwUXyWZT9InLYax14RDjBj46LmSztkmNP9w" crossorigin="anonymous">
<link href="{{ URL::asset('css/app.css') }}?v={{ time() }}" rel="stylesheet">
<link href="{{ URL::asset('css/main.css') }}?v={{ time() }}" rel="stylesheet">
<script type="text/javascript" src="{{ URL::asset('js/main.js') }}?v={{ time() }}"></script>

<script type="text/javascript">
    @if (\Request::is('login'))

    @else
    $(function($) {
        var token = "{{ csrf_token() }}";
        var url = '{{ url('check-session') }}';
        setInterval(function() {
            $.ajax({
                type: 'POST',
                url: url,
                dataType : 'json',
                data: {
                    _token: token
                },

                success: function (data) {
                    if (!data['login']) {
                        window.location = 'login';
                    }
                }
            });
        }, 3000);
    });
    @endif
</script>

