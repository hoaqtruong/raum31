<?php

function rockox_form_system_theme_settings_alter(&$form, $form_state) {

  $path = drupal_get_path('theme', 'rockox');
  drupal_add_library('system', 'ui');
  drupal_add_library('system', 'farbtastic');

  // drupal_add_js($path . '/js/theme_admin.js');

  $form['settings'] = array(
      '#type' => 'vertical_tabs',
      '#title' => t('Theme settings'),
      '#weight' => 2,
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
  );

  $form['settings']['general'] = array(
      '#type' => 'fieldset',
      '#title' => t('General settings'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
  );
  $form['settings']['general']['use_dark_theme'] = array(
      '#type' => 'checkbox',
      '#title' => t('Use dark theme'),
      '#default_value' => theme_get_setting('use_dark_theme'),
      '#description' => t('Check checkbox if you would use dark layout style.'),
  );
  $form['settings']['general']['custom_theme_css'] = array(
      '#type' => 'textarea',
      '#title' => t('Custom theme css'),
      '#default_value' => theme_get_setting('custom_theme_css'),
      '#description' => t('Custom your own css, eg: <strong>#social-footer {background-color:#F5F5F5;}</strong>'),
  );
  /* $form['settings']['general']['theme_color'] = array(
    '#title' => t('Theme color'),
    '#type' => 'textfield',
    '#default_value' => theme_get_setting('theme_color'),
    '#attributes' => array('class' => array('input color')),
    '#description' => t('Default color hex code is: <a style="color:#FF4800" class="color-default" href="javascript:void(0); ">#FF4800</a>'),
    ); */
}
