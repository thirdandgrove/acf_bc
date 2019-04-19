<?php

namespace Drupal\acf_bc\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class BigCommerceSettingsForm
 *
 * Configure BigCommerce settings for the site.
 */
class BigCommerceSettingsForm extends ConfigFormBase {

  /**
   * ACF BigCommerce config settings path.
   * @const CONFIG_KEY
   */
  const CONFIG_KEY = 'acf_bc.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'bc_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(self::CONFIG_KEY);
    $form['store_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Store ID'),
      '#default_value' => $config->get('store_id'),
      '#required' => TRUE,
    ];
    $form['token'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Access Token'),
      '#default_value' => $config->get('token'),
      '#required' => TRUE,
    ];
    $form['client_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Client ID'),
      '#default_value' => $config->get('client_id'),
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->configFactory->getEditable(self::CONFIG_KEY)
      ->set('store_id', $form_state->getValue('store_id'))
      ->set('token', $form_state->getValue('token'))
      ->set('client_id', $form_state->getValue('client_id'))
      ->save();

    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [self::CONFIG_KEY];
  }
}
