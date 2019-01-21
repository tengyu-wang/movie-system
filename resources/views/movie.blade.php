<div class="movie-details-block">
@if (isset($ERROR))
    <div class="no-movies-message">{{ $ERROR }}</div>
@else
    <div class="poster-medium">
        @if (empty($movie['poster_path']))
        <img src="/images/no_poster_icon.png" />
        @elseif (empty($movie['backdrop_path']))
        <img src="{{ $imageUrlPrefix }}{{ $movie['poster_path'] }}" />
        @else
        <div class="flip-container">
            <div class="flipper">
                <div class="front">
                    <img src="{{ $imageUrlPrefix }}{{ $movie['poster_path'] }}" />
                </div>
                <div class="back">
                    <img src="{{ $imageUrlPrefix }}{{ $movie['backdrop_path'] }}" />
                </div>
            </div>
        </div>
        @endif
    </div>
    <div class="movie-info">
        <div id="movie-title">
            {{ $movie['title'] }} @if ($movie['release_date'])({{ substr($movie['release_date'], 0, 4) }}) @endif
        </div>
        <div id="release-date">
            Release date: @if ($movie['release_date']) <I>{{ date('j', strtotime($movie['release_date'])) }} <sup>{{ date('S', strtotime($movie['release_date'])) }}</sup>{{ date(' F, Y', strtotime($movie['release_date'])) }}</I> @endif
        </div>
        <div id="language">
            Language: @if (!empty($movie['spoken_languages'])) <I>{{ implode(', ', array_column($movie['spoken_languages'], 'name')) }}</I> @endif
        </div>
        <div id="rate">Rate: @if ($movie['vote_average']) <I>{{ $movie['vote_average'] }}</I> @endif </div>
        <div id="overview-title">Overview: </div>
        <div id="overview-content"> @if ($movie['overview']) <I>{{ $movie['overview'] }}</I> @endif </div>
    </div>
@endif
</div>
