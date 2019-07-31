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
      // kint($response, '$response');

      $output = [
        '#theme' => 'page--person',
        '#name' => $response['name'],
        '#profile_path' => $response['profile_path'],
        '#birthday' => $response['birthday'],
        '#deathday' => $response['deathday'], //response null if not exist
        '#popularity' => $response['popularity'],
        '#biography' => $response['biography'],
        '#gallery' => $gallery,
      ];
    } catch (\Exception $e) {
      $build['error'] = $e->getMessage();
      $build['code'] = $e->getCode();
    }
    return $output;
  }

}
