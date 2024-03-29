<?php

namespace Drupal\challenge\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class DetailPersonController.
 */
class DetailPersonController extends ControllerBase {

 /**
   * Output.
   *
   * @return array
   *   Return Output array.
   */
  public function view($id) {
    $calling = \Drupal::service('challenge.apiary');
    try {
      $response = $calling->getQuery('person/' . $id);
      $images = $calling->getQuery('person/' . $id . '/images');
      $gallery = $images['profiles'];

      // Validation if data exist
      $deathday = (NULL !== $response['deathday'])
      ? $response['deathday'] : FALSE;
      $birthday = (NULL !== $response['birthday'])
      ? $response['birthday'] : FALSE;
      $popularity = (NULL !== $response['popularity'])
      ? $response['popularity'] : FALSE;
      $place_of_birth = (NULL !== $response['place_of_birth'])
      ? $response['place_of_birth'] : FALSE;

      $output = [
        '#theme' => 'page--person',
        '#base_url' => $calling->baseUrl,
        '#name' => $response['name'],
        '#profile_path' => $response['profile_path'],
        '#birthday' => $birthday,
        '#deathday' => $deathday,
        '#popularity' => $popularity,
        '#biography' => $response['biography'],
        '#gallery' => $gallery,
        '#place_of_birth' => $place_of_birth,
      ];
    } catch (\Exception $e) {
      $build['error'] = $e->getMessage();
      $build['code'] = $e->getCode();
    }
    \Drupal::logger('detail_actor')->notice(print_r($output, 1));
    return $output;
  }

}
