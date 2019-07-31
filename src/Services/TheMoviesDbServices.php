<?php
namespace Drupal\challenge\Services;

use Drupal\Core\Render\Markup;
use Symfony\Component\HttpFoundation\RedirectResponse;
use GuzzleHttp\Client;
use Drupal\Component\Serialization\Json;

class TheMoviesDbServices {

  protected $siteUrl;
  protected $apiKey;
  public $baseUrl;

  /**
   * {@inheritdoc}
   */
  public function __construct() {
    $this->siteUrl = 'https://api.themoviedb.org/3/';
    $this->apiKey = '693729b685b7570fed8664b50ff701cc';
    $this->baseUrl = 'https://image.tmdb.org/t/p/';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuery($endpoint) {
    try {
      $client = \Drupal::service('http_client_factory')->fromOptions([
        'base_uri' => $this->siteUrl,
      ]);
      $built = $client->get($endpoint, [
        'query' => [
          'api_key' => $this->apiKey,
        ],
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
