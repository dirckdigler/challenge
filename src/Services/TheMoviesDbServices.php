<?php

namespace Drupal\challenge\Services;

use Drupal\Component\Serialization\Json;

class TheMoviesDbServices {

  const SITE_URL = 'https://api.themoviedb.org/3/';
  const API_KEY = '693729b685b7570fed8664b50ff701cc';
  public $baseUrl = 'https://image.tmdb.org/t/p/';
  protected $httpClient;

  /**
   * {@inheritdoc}
   */
  public function __construct() {
    $this->httpClient = \Drupal::service('http_client_factory');
    $this->baseUrl = 'https://image.tmdb.org/t/p/';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuery($endpoint, array $queryString = []) {
    try {
      $client = $this->httpClient->fromOptions([
        'base_uri' => self::SITE_URL,
      ]);
      $query = [
        'api_key' => self::API_KEY,
      ] + $queryString;
      $built = $client->get($endpoint, [
        'query' => $query,
      ]);
      $response = Json::decode($built->getBody());
    }
    catch(\Exception $e) {
      $response['error'] = $e->getMessage();
      $response['code'] = $e->getCode();
    }
    \Drupal::logger('query')->notice(print_r($response, 1));
    return $response;
  }

}
