<?php

namespace App\Http\Controllers;

use App\Services\MovieService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

/**
 * Class MovieController
 *
 *
 * @package App\Http\Controllers
 * @author Tengyu Wang
 */
class MovieController extends Controller
{
    /**
     * @var MovieService
     */
    private $movieService;

    /**
     * MovieController constructor.
     * @param MovieService $movieService
     */
    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getGenreList()
    {
        $list = $this->movieService->getGenreList();

        if (isset($list['ERROR'])) {
            return view('movies',
                        array_merge($list, ['imageUrlPrefix' => Config::get('constants.tmdb_image_url_prefix')]));
        }

        return view('movies', ['genreList' => $list,
                                     'imageUrlPrefix' => Config::get('constants.tmdb_image_url_prefix')]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGenreMovies(Request $request)
    {
        $movies = $this->movieService->getGenreMovies(intval($request->get('genreId')),
                                                      intval($request->get('pageNumber')));

        return response()->json($movies);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSearchedMovies(Request $request)
    {
        $movies = $this->movieService->getSearchedMovies($request->get('query'),
                                                         intval($request->get('pageNumber')));

        return response()->json($movies);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function getMovie(Request $request)
    {
        $movie = $this->movieService->getMovie(intval($request->get('movieId')));

        if (isset($movie['ERROR'])) {
            $view = view('movie', $movie)->render();
        } else {
            $view = view('movie',
                    ['movie' => $movie,
                     'imageUrlPrefix' => Config::get('constants.tmdb_image_url_prefix')])->render();
        }

        return response()->json(['html' => $view]);
    }
}
