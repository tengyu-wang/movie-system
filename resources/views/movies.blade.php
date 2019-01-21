<!DOCTYPE html>
<html>
    <head>
        @include('head', ['pageTitle' => 'Movies'])
    </head>
    <body>
        @if (isset($ERROR)) <script>alert('{{ $ERROR }}');</script> @endif
        <div class="main-column">
        @if (!isset(Auth::user()->email))
            <script>window.location = 'login';</script>
        @else
            @include('header')
        @endif

            <div id="genre-list-block" class="sidebar">
                <div id="search-block">
                    <label for="search-query">Search movies:</label>
                    <div class="search-input-block">
                        <input type="text" id="search-query" value="" />
                        <a id="search-button" tabindex="0" href="#"><img class="search-icon" src="{{ URL::asset('images/search_icon.png') }}" /></a>
                    </div>
                </div>
        @if (isset($genreList))
            @foreach($genreList as $index => $genre)
                <div class="genre-row @if ($index === 0) first-child @endif">
                    <input type="hidden" class="genre-id-holder" value="{{ $genre['id'] }}">
                    {{ $genre['name'] }}
                </div>
            @endforeach
        @endif
            </div>

            <div class="content-block">
                <div id="genre-movie-list-block"></div>
            </div>

            @include('footer')

        </div>

        <script>
            $(function($) {
                $('.genre-row').click(function() {
                    $('.genre-row').removeClass('select');
                    $(this).addClass('select');
                    var genreId = $(this).find('.genre-id-holder').first().val();

                    var moverSeeker = new MovieSeeker({
                        "searchParameters": {"genreId": genreId, "query": undefined},
                        "url": "{{ url("get-genre-movies") }}",
                        "movieUrl": "{{ url("get-movie") }}",
                        "token": "{{ csrf_token() }}",
                        "posterPathPrefix": "{{ $imageUrlPrefix }}"
                    });

                    moverSeeker.generateMovieList();
                });

                $('input#search-query').focus(function() {
                    $(this).parent().animate({
                        width: "300px"
                    }, 300);

                    $(this).animate({
                        width: "266px"
                    }, 300);
                }).blur(function() {
                    $(this).animate({
                        width: "76px"
                    }, 300);

                    $(this).parent().animate({
                        width: "110px"
                    }, 300);
                });

                $('a#search-button').focus(function() {
                    $(this).blur(); // prevent multiple focus event for this

                    $('.genre-row').removeClass('select');

                    if ($.trim($('input#search-query').val()).length < 2) {
                        alert('Please enter at least two chars!');
                        return false;
                    }

                    var moverSeeker = new MovieSeeker({
                        "searchParameters": {"genreId": undefined, "query": $.trim($('input#search-query').val())},
                        "url": "{{ url("get-searched-movies") }}",
                        "movieUrl": "{{ url("get-movie") }}",
                        "token": "{{ csrf_token() }}",
                        "posterPathPrefix": "{{ $imageUrlPrefix }}"
                    });

                    moverSeeker.generateMovieList();
                });

                $('.genre-row.first-child').trigger('click');
            });

        </script>
    </body>
</html>

