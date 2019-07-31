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

     for ($i=0, $len=count($credits['cast']); $i<4; $i++) {
        $cast[] = $credits['cast'][$i];
      }

      $genres = reset($response['genres'])['name'];
      $production_companies = reset($response['production_companies'])['name'];
      $output = [
        '#theme' => 'page--movie',
        '#title' => $response['original_title'],
        '#base_url' => $calling->baseUrl,
        '#poster_path' => $response['poster_path'],
        '#genres' => $genres,
        '#overview' => $response['overview'],
        '#production_companies' => $production_companies,
        '#release_date' => $response['release_date'],
        '#original_language' => $response['original_language'],
        '#cast' => $cast,
        '#popularity' => $response['popularity'],
      ];
    } catch (\Exception $e) {
      $build['error'] = $e->getMessage();
      $build['code'] = $e->getCode();
    }
    return $output;
  }
}
