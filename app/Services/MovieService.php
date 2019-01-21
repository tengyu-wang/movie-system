<?php
namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

/**
 * Class MovieService
 * Provide service for fetching movie genre list, movie list or single movie details
 *
 * @package App\Services
 * @author Tengyu Wang
 */
class MovieService
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * MovieService constructor.
     */
    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client(['verify' => false]);
    }

    /**
     * Get genre list
     *
     * @return array
     */
    public function getGenreList()
    {
        $endpoint = Config::get('constants.TMDB_api.api_url_prefix').'/genre/movie/list';
        $url = $endpoint.'?api_key='.Config::get('constants.TMDB_api.api_key');

        try {
            $res = $this->client->request('GET', $url);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            Log::error($e->getResponse()->getBody()
                                .' | Method: '.__METHOD__);
            return ['ERROR' => 'Cannot get genre list, please try again later!'];
        }

        if (intval($res->getStatusCode()) !== 200) {
            Log::error("Cannot get genre list");
            return ['ERROR' => 'Cannot get genre list, please try again later!'];
        }

        $result = json_decode($res->getBody(), true);

        return $result['genres'];
    }

    /**
     * Get movies for given genre ID
     *
     * @param int $genreId
     * @param int $pageNumber
     * @return array|mixed
     */
    public function getGenreMovies($genreId, $pageNumber)
    {
        $endpoint = Config::get('constants.TMDB_api.api_url_prefix').'/genre/'.$genreId.'/movies';
        $url = $endpoint.'?page='.$pageNumber.'&api_key='.Config::get('constants.TMDB_api.api_key');

        try {
            $res = $this->client->request('GET', $url);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            Log::error($e->getResponse()->getBody()
                                .' | Method: '.__METHOD__.' | Genre ID: '.$genreId);
            return ['ERROR' => 'Cannot get movies, please try again later!'];
        }

        if (intval($res->getStatusCode()) !== 200) {
            Log::error("Cannot get movies for genreId '".$genreId."'");
            return ['ERROR' => 'Cannot get movies, please try again later!'];
        }

        $list = json_decode($res->getBody(), true);

        return $list;
    }

    /**
     * Get searched data
     *
     * @param string $query
     * @param int $pageNumber
     * @return array|mixed
     */
    public function getSearchedMovies($query, $pageNumber)
    {
        $endpoint = Config::get('constants.TMDB_api.api_url_prefix').'/search/movie';

        // query must be url encoded before sent
        $url = $endpoint.'?query='. rawurlencode($query)
                .'&page='.$pageNumber
                .'&api_key='.Config::get('constants.TMDB_api.api_key');

        try {
            $res = $this->client->request('GET', $url);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            Log::error($e->getResponse()->getBody()
                                .' | Method: '.__METHOD__.' | Query: '.$query);
            return ['ERROR' => 'Cannot get movies, please try again later!'];
        }

        if (intval($res->getStatusCode()) !== 200) {
            Log::error("Cannot get movies for query '".$query."'");
            return ['ERROR' => 'Cannot get movies, please try again later!'];
        }

        $list = json_decode($res->getBody(), true);

        return $list;
    }

    /**
     * Get movie details by given movie ID
     *
     * @param int $movieId
     * @return array|mixed
     */
    public function getMovie($movieId)
    {
        $endpoint = Config::get('constants.TMDB_api.api_url_prefix').'/movie/'.$movieId;
        $url = $endpoint.'?api_key='.Config::get('constants.TMDB_api.api_key');

        try {
            $res = $this->client->request('GET', $url);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            Log::error($e->getResponse()->getBody()
                                .' | Method: '.__METHOD__.' | Genre ID: '.$movieId);
            return ['ERROR' => 'Cannot get movie details, please try again later!'];
        }

        if (intval($res->getStatusCode()) !== 200) {
            Log::error("Cannot get movies for query '".$movieId."'");
            return ['ERROR' => 'Cannot get movie details, please try again later!'];
        }

        $movie = json_decode($res->getBody(), true);

        return $movie;
    }
}
