<?php

namespace Drupal\new_exercise\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class CustomNotifyForm extends ConfigFormBase {

  protected function getEditableConfigNames() {
    return ['custom_email.settings'];
  }

  public function getFormId() {
    return 'custom_email_settings';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('custom_email.settings');

    $form['email_subject'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Email Subject'),
      '#default_value' => $config->get('email_subject'),
      '#required' => TRUE,
    ];

    $form['email_message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Email Message'),
      '#default_value' => $config->get('email_message'),
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('custom_email.settings')
      ->set('email_subject', $form_state->getValue('email_subject'))
      ->set('email_message', $form_state->getValue('email_message'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}
