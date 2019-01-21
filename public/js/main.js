/**
 *
 * Movie Seeker
 *
 * @author Tengyu Wang
 *
 */

function MovieSeeker(params)
{
    this.genreId = params.searchParameters.genreId;
    this.query = params.searchParameters.query;
    this.url = params.url;
    this.movieUrl = params.movieUrl;
    this.token = params.token;
    this.posterPathPrefix = params.posterPathPrefix;
    var ms = this;

    this.generateMovieList = function(pageNumber) {
        if (typeof pageNumber === 'undefined') {
            pageNumber = 1;
        }

        $('#genre-movie-list-block').html('<div class="ajax-loading"></div>');

        // AJAX call to send request, and get movies data
        $.ajax({
            type: 'POST',
            url: ms.url,
            dataType : 'json',
            data: {
                _token: ms.token,
                genreId: ms.genreId,
                query: ms.query,
                pageNumber: pageNumber
            },

            success: function (data) {
                if (data['ERROR'] !== undefined) {
                    // pop up error message if any
                    $('#genre-movie-list-block').html('<div class="no-movies-message">'+data['ERROR']+'</div>');
                    return;
                }

                drawMovieTable(data); // draw table with data
            }
        });
    };

    var getMovieTable = function(movies) {
        var table = document.createElement('table');
        table.setAttribute('class', 'pure-table'); // use pure-css lib

        var tr = document.createElement('tr');
        var td = document.createElement('td');
        tr.appendChild(td);
        td = document.createElement('td');
        td.innerHTML = 'Title';
        tr.appendChild(td);
        td = document.createElement('td');
        td.innerHTML = 'Year';
        tr.appendChild(td);
        td = document.createElement('td');
        td.innerHTML = 'Overview';
        tr.appendChild(td);
        td = document.createElement('td');
        td.innerHTML = 'Rate';
        tr.appendChild(td);

        // set thead
        var thead = document.createElement('thead');
        thead.appendChild(tr);

        table.appendChild(thead);

        // set tbody
        var tbody = document.createElement('tbody');
        for (var i = 0; i < movies.length; i ++) {
            tr = document.createElement('tr');

            if (i % 2 == 1) {
                tr.setAttribute('class', 'pure-table-odd');
            }

            td = document.createElement('td');
            var imgClass = 'poster_small';
            var imgPath = movies[i].poster_path ? movies[i].poster_path : movies[i].backdrop_path;

            if (imgPath) {
                imgPath = ms.posterPathPrefix + imgPath;
            } else {
                imgPath = '../images/no_poster_small_icon.png'; // if no poster, set it as a default icon
                imgClass = 'poster_small no_poster';
            }

            td.innerHTML = "<img src='" + imgPath + "' class='" + imgClass + "' />";
            tr.appendChild(td);

            td = document.createElement('td');
            td.innerHTML = '<span id="' + movies[i].id + '" class="movie-title link-like">' + movies[i].title + '</span>';
            tr.appendChild(td);

            td = document.createElement('td');
            td.innerHTML = movies[i].release_date.substr(0, 4);
            tr.appendChild(td);

            td = document.createElement('td');
            var overview = movies[i].overview;
            if (overview.length > 213) { // maximum display 240 valid chars
                overview = overview.substr(0, 210) + '...';
            }

            td.innerHTML = overview;
            td.style.cssText = 'text-align: justify';
            tr.appendChild(td);

            td = document.createElement('td');
            td.innerHTML = movies[i].vote_average;
            tr.appendChild(td);

            tbody.appendChild(tr);
        }

        table.appendChild(tbody);

        return table;
    };

    var getPaginationBar = function(currentPageNumber, totalPageNumber, totalResults) {
        var start = (currentPageNumber - 1) * 20 + 1;
        var end = start + 19 > totalResults ? totalResults : start + 19;

        var paginationBarDiv = document.createElement('div');
        paginationBarDiv.setAttribute('id', 'pagination-bar');

        // 'Previous' link
        var previousDiv = document.createElement('div');
        previousDiv.innerHTML = '< Previous';
        previousDiv.setAttribute('id', 'previous-page');
        previousDiv.setAttribute('class', 'link-like');

        if (start == 1) {
            // classList is not working in IE 9, for all browser usage, we can just split className to get all classes
            // to add or remove
            previousDiv.classList.add('disable');
        } else {
            previousDiv.classList.remove('disable');
            previousDiv.addEventListener('click', function() {
                ms.generateMovieList(currentPageNumber - 1);
            });
        }

        // 'Next' link
        var nextDiv = document.createElement('div');
        nextDiv.innerHTML = 'Next >';
        nextDiv.setAttribute('id', 'next-page');
        nextDiv.setAttribute('class', 'link-like');

        if (end == totalResults) {
            nextDiv.classList.add('disable');
        } else {
            nextDiv.classList.remove('disable');
            nextDiv.addEventListener('click', function(){
                ms.generateMovieList(currentPageNumber + 1);
            });
        }

        var totalPageDiv = document.createElement('div');
        totalPageDiv.innerHTML = 'Total pages: <span>' + totalPageNumber + '</span>';
        totalPageDiv.setAttribute('id', 'total-pages');

        var currentPageDiv = document.createElement('div');
        currentPageDiv.innerHTML = 'Current page: <span>' + currentPageNumber + '</span>';
        currentPageDiv.setAttribute('id', 'current-page');

        var goToPageDiv = document.createElement('div');
        goToPageDiv.innerHTML = 'Go to page '
            + '<input type="number" min="1" max="'+totalPageNumber+'" class="page-number-input" value="" />';

        var itemsInfoDiv = document.createElement('div');
        itemsInfoDiv.innerHTML = 'Showing ' + '<span id="current-items-count">' + start + ' to ' + end + '</span>'
            + ' of ' + '<span id="total-items-count">' + totalResults + '</span>';

        paginationBarDiv.appendChild(previousDiv);
        paginationBarDiv.appendChild(nextDiv);
        paginationBarDiv.appendChild(currentPageDiv);
        paginationBarDiv.appendChild(totalPageDiv);
        paginationBarDiv.appendChild(goToPageDiv);
        paginationBarDiv.appendChild(itemsInfoDiv);

        return paginationBarDiv;
    };

    var setGoToPageEvent = function(totalPageNumber) {
        $('.page-number-input').keydown(function(event) {
            var code = (event.keyCode ? event.keyCode : event.which);
            if(code != 13) { // if not Enter key code, stopped before event activated
                return true;
            }

            var pageNumber = $(this).val();

            // if page number is valid and 'Enter' has been pressed, activate event
            if (pageNumber > 0 && pageNumber <= totalPageNumber) {
                ms.generateMovieList(pageNumber);
                return true;
            }

            alert('Invalid page number!');
            return false;
        })
    };

    var setPosterPreviewEvent = function() {
        $('img.poster_small').click(function () {
            // if no poster, then do not set this event
            if ($(this).hasClass('no_poster')) {
                return false;
            }

            var img = document.createElement('img');
            img.setAttribute('src', $(this).attr('src'));

            // set height, style margin to and bottom both 20px, so decreased by 40 here
            var height = $(window).height() - 40;
            img.style.cssText = 'margin-top: 20px; height: ' + height + 'px;';

            var div = document.createElement('div');
            div.setAttribute('class', 'poster_preview_wall');
            div.appendChild(img);

            div.addEventListener('click', function () {
                this.remove(); // if preview wall clicked, then it will be closed
            });

            var body = document.getElementsByTagName("body")[0]
            body.appendChild(div);

            $('div.poster_preview_wall').fadeTo(500, 1);
        });
    }

    var setGetMovieEvent = function() {
        $('span.movie-title').click(function() {
            $('.genre-row').removeClass('select');

            var movieId = $(this).attr('id');

            $('#genre-movie-list-block').html('<div class="ajax-loading"></div>');

            // AJAX call to send request, and get movies data
            $.ajax({
                type: 'POST',
                url: ms.movieUrl,
                dataType : 'json',
                data: {
                    _token: ms.token,
                    movieId: movieId
                },

                success: function (data) {
                    console.log(data);

                    if (data['ERROR'] !== undefined) {
                        // pop up error message if any
                        $('#genre-movie-list-block').html('<div class="no-movies-message">'+data['ERROR']+'</div>');
                        return;
                    }

                    $('#genre-movie-list-block').html(data.html);
                }
            });
        });
    };

    var drawMovieTable = function (data) {
        if (!data['results'].length) {
            $('#genre-movie-list-block').html('<div class="no-movies-message">No movies found!</div>');
            return false;
        }

        $('#genre-movie-list-block').html('');

        // set pagination bar
        $('#genre-movie-list-block').append(getPaginationBar(data['page'], data['total_pages'], data['total_results']));

        // set movie table
        $('#genre-movie-list-block').append(getMovieTable(data['results']));

        // set pagination bar
        $('#genre-movie-list-block').append(getPaginationBar(data['page'], data['total_pages'], data['total_results']));

        // set go to page event
        setGoToPageEvent(data['total_pages']);

        // set poster preview event
        setPosterPreviewEvent();

        // set poster preview event
        setGetMovieEvent();
    };
}

