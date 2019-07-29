<?php

namespace Drupal\challenge\Plugin\Block;

use Drupal\Core\Block\BlockBase;

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
    $build = [];
    $build['popular_movies_block']['#markup'] = 'Implement PopularMoviesBlock.';

    return $build;
  }

}
