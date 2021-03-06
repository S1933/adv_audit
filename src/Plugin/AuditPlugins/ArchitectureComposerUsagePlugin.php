<?php

namespace Drupal\adv_audit\Plugin\AuditPlugins;

use Drupal\adv_audit\Plugin\AuditBasePlugin;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Check if the project uses composer.
 *
 * @AuditPlugin(
 *   id = "composer_usage_check",
 *   label = @Translation("Check if composer is used on the project."),
 *   category = "architecture_analysis",
 *   requirements = {},
 * )
 */
class ArchitectureComposerUsagePlugin extends AuditBasePlugin {

  /**
   * {@inheritdoc}
   */
  public function perform() {

    if (file_exists(DRUPAL_ROOT . '/../composer.json') && file_exists(DRUPAL_ROOT . '/../composer.lock')) {

      return $this->success();
    }

    return $this->fail(NULL, [
      'issues' => [
        'composer_usage_check' => [
          '@issue_title' => 'There is no composer files in ROOT directory of DrupalProject.',
        ],
      ],
      '%link' => Link::fromTextAndUrl($this->t('Using Composer with Drupal'),
        Url::fromUri('https://www.drupal.org/docs/develop/using-composer/using-composer-with-drupal'))
        ->toString(),
    ]);
  }

}
