<?php

namespace Drupal\adv_audit\Plugin\AdvAuditCheck;

use Drupal\adv_audit\Traits\AuditPluginSubform;
use Drupal\adv_audit\Plugin\AdvAuditCheckBase;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\PluginFormInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Check must-have modules for security reasons.
 *
 * @AdvAuditCheck(
 *   id = "must_have_modules",
 *   label = @Translation("Check must-have modules for security reasons"),
 *   category = "security",
 *   requirements = {},
 *   enabled = true,
 *   severity = "high"
 *  )
 */
class MustHaveModulesCheck extends AdvAuditCheckBase implements ContainerFactoryPluginInterface, PluginFormInterface {

  use AuditPluginSubform;

  /**
   * Drupal\Core\Extension\ModuleHandlerInterface definition.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ModuleHandlerInterface $module_handler) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->moduleHandler = $module_handler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('module_handler')
    );
  }

  /**
   * Process checkpoint review.
   */
  public function perform() {
    $enabled_modules = [];
    $settings = $this->getSettings();
    $required_modules = $this->parseLines($settings['modules']);
    foreach ($required_modules as $module_name) {
      if ($this->moduleHandler->moduleExists($module_name)) {
        $enabled_modules[] = $module_name;
      }
    }

    $diff = array_values(array_diff($required_modules, $enabled_modules));
    if (!empty($diff) && $diff != ['captcha'] && $diff != ['honeypot']) {
      $issues = [];
      foreach ($diff as $item) {
        $issues[$item] = [
          '@issue_title' => 'Module "@module" is not installed.',
          '@module' => $item,
        ];
      }
      return $this->fail($this->t('One or more recommended modules are not installed.'), ['issues' => $issues]);
    }

    return $this->success();
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {

    $settings = $this->getSettings();
    $form['modules'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Required modules'),
      '#default_value' => $settings['modules'],
    ];
    return $form;

  }

}
