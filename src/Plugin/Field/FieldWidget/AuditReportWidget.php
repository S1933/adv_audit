<?php

namespace Drupal\adv_audit\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'audit_report_widget' widget.
 *
 * @FieldWidget(
 *   id = "audit_report_widget",
 *   label = @Translation("Audit report widget"),
 *   field_types = {
 *     "audit_result"
 *   }
 * )
 */
class AuditReportWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'size' => 60,
      'placeholder' => '',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = [];

    $elements['size'] = [
      '#type' => 'number',
      '#title' => $this->t('Size of textfield'),
      '#default_value' => $this->getSetting('size'),
      '#required' => TRUE,
      '#min' => 1,
    ];
    $elements['placeholder'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Placeholder'),
      '#default_value' => $this->getSetting('placeholder'),
      '#description' => $this->t('Text that will be shown inside the field until a value is entered. This hint is usually a sample value or a brief description of the expected format.'),
    ];

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];

    $summary[] = $this->t('Textfield size: @size', ['@size' => $this->getSetting('size')]);
    if (!empty($this->getSetting('placeholder'))) {
      $summary[] = $this->t('Placeholder: @placeholder', ['@placeholder' => $this->getSetting('placeholder')]);
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    return $element;
  }

}
