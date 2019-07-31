<?php

namespace Drupal\challenge\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'PopularMoviesBlock' block.
 *
 * @Block(
 *  id = "popular_movies_block",
 *  admin_label = @Translation("Popular movies"),
 * )
 */
class PopularMoviesBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
   $calling = \Drupal::service('challenge.apiary');
    try {
      $response = $calling->getQuery('movie/popular');
      $build = [];
      $item_number = \Drupal::config('challenge.settings')
        ->get('popular_movies_number');
      $item_filter = array_slice($response['results'], 0, $item_number);
      $genre = $calling->getQuery('genre/movie/list');
      foreach ($item_filter as $item) {
        $build['#theme'] = 'block--upcommingblock';
        $build['#results'][] = $item;
        $build['#base_url'] = $calling->baseUrl;
      }
    } catch (\Exception $e) {
      $build['error'] = $e->getMessage();
      $build['code'] = $e->getCode();
    }
    \Drupal::logger('popular_movies')->notice(print_r($build, 1));
    return $build;
  }

   /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $config = \Drupal::config('challenge.settings');
    $range = range(1, 10);
    $form['popular_movies_number'] = array(
    '#type' => 'select',
    '#title' => $this->t('Number of movies'),
    '#default_value' => (NULL !== $config->get('popular_movies_number'))
      ? $config->get('popular_movies_number') : 10,
    '#options' => array_combine($range, $range),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $config = \Drupal::configFactory()->getEditable('challenge.settings');
    $config->set('popular_movies_number', $form_state
      ->getValue('popular_movies_number'))->save();
  }

}
