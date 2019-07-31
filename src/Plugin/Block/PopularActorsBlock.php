<?php

namespace Drupal\challenge\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'PopularActorsBlock' block.
 *
 * @Block(
 *  id = "popular_actors_block",
 *  admin_label = @Translation("Popular actors"),
 * )
 */
class PopularActorsBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $calling = \Drupal::service('challenge.apiary');
    try {
      $response = $calling->getQuery('person/popular');
      $build = [];
      $item_number = \Drupal::config('challenge.settings')->get('actor_number');
      $item_filter = array_slice($response['results'], 0, $item_number);
      foreach ($item_filter as $item) {
        $build['#theme'] = 'block--popular-actors-block';
        $build['#results'][] = $item;
        $build['#base_url'] = $calling->baseUrl;
      }
    } catch (\Exception $e) {
      $build['error'] = $e->getMessage();
      $build['code'] = $e->getCode();
    }
    \Drupal::logger('popular_actors')->notice(print_r($build, 1));
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $config = \Drupal::config('challenge.settings');
    $range = range(1, 10);
    $form['actor_number'] = array(
    '#type' => 'select',
    '#title' => $this->t('Number of actors'),
    '#default_value' => (NULL !== $config->get('actor_number'))
      ? $config->get('actor_number') : 10,
    '#options' => array_combine($range, $range),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $config = \Drupal::configFactory()->getEditable('challenge.settings');
    $config->set('actor_number', $form_state->getValue('actor_number'))->save();
  }

}
