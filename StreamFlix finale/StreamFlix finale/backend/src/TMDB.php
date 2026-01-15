<?php

namespace Streamflix;

use GuzzleHttp\Client;

class TMDB
{
    private $apiKey;
    private $client;
    private $baseUrl = 'https://api.themoviedb.org/3';

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->client = new Client();
    }

    public function getTopMovies($page = 1)
    {
        $response = $this->client->get("{$this->baseUrl}/movie/popular", [
            'query' => [
                'api_key' => $this->apiKey,
                'language' => 'fr-FR',
                'page' => $page
            ]
        ]);
        
        return json_decode($response->getBody(), true);
    }

    public function getMovie($id)
    {
        $response = $this->client->get("{$this->baseUrl}/movie/{$id}", [
            'query' => [
                'api_key' => $this->apiKey,
                'language' => 'fr-FR'
            ]
        ]);
        
        return json_decode($response->getBody(), true);
    }

    public function getMoviesByGenre($genreId, $page = 1)
    {
        $response = $this->client->get("{$this->baseUrl}/discover/movie", [
            'query' => [
                'api_key' => $this->apiKey,
                'language' => 'fr-FR',
                'with_genres' => $genreId,
                'page' => $page
            ]
        ]);
        
        return json_decode($response->getBody(), true);
    }

    public function searchMovies($query, $page = 1)
    {
        $response = $this->client->get("{$this->baseUrl}/search/movie", [
            'query' => [
                'api_key' => $this->apiKey,
                'language' => 'fr-FR',
                'query' => $query,
                'page' => $page
            ]
        ]);
        
        return json_decode($response->getBody(), true);
    }

    public function getMovieCredits($id)
    {
        $response = $this->client->get("{$this->baseUrl}/movie/{$id}/credits", [
            'query' => [
                'api_key' => $this->apiKey,
                'language' => 'fr-FR'
            ]
        ]);
        
        return json_decode($response->getBody(), true);
    }
}

