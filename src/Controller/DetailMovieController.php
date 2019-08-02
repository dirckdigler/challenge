<?php

namespace Drupal\challenge\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class DetailMovieController.
 */
class DetailMovieController extends ControllerBase {

  /**
   * Output.
   *
   * @return array
   *   Return Output array.
   */
  public function view($id) {
    $calling = \Drupal::service('challenge.apiary');
    try {
      $response = $calling->getQuery('movie/' . $id);
      $credits = $calling->getQuery('movie/' . $id . '/credits');

      if (isset($credits['cast']) && NULL !== ($credits['cast'])) {
        for ($i=0, $len=count($credits['cast']); $i<4; $i++) {
          $cast[] = $credits['cast'][$i];
        }
      }

      // Validation if data exist
      $overview = (NULL !== $response['overview'])
      ? $response['overview'] : FALSE;
      $production_companies = reset($response['production_companies'])['name'];
      $production_companies = (NULL !== $production_companies)
      ? $production_companies : FALSE;
      $release_date = (NULL !== $response['release_date'])
      ? $response['release_date'] : FALSE;
      $original_language = (NULL !== $response['original_language'])
      ? $response['original_language'] : FALSE;
      $popularity = (NULL !== $response['popularity'])
      ? $response['popularity'] : FALSE;
      $genres = reset($response['genres'])['name'];
      $genres = (NULL !== $genres) ? $genres : FALSE;

      $output = [
        '#theme' => 'page--movie',
        '#title' => $response['original_title'],
        '#base_url' => $calling->baseUrl,
        '#poster_path' => $response['poster_path'],
        '#genres' => $genres,
        '#overview' => $overview,
        '#production_companies' => $production_companies,
        '#release_date' => $release_date,
        '#original_language' => $original_language,
        '#cast' => $cast,
        '#popularity' => $popularity,
      ];
    } catch (\Exception $e) {
      $build['error'] = $e->getMessage();
      $build['code'] = $e->getCode();
    }
    \Drupal::logger('detail_movie')->notice(print_r($output, 1));
    return $output;
  }
}
