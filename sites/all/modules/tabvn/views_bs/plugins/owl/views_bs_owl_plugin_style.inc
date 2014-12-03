<?php

class ViewsBsOwlPluginStyle extends views_plugin_style {

  function option_definition() {
    $options = parent::option_definition();

    $options['owl'] = array('default' => 1);


    return $options;
  }

  function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);

    $form['owl']['items'] = array(
        '#type' => 'textfield',
        '#title' => t('Items'),
        '#default_value' => isset($this->options['owl']['items']) ? $this->options['owl']['items'] : 4,
        '#description' => t('This variable allows you to set the maximum amount of items displayed at a time with the widest browser width.'),
    );

    $form['owl']['itemsDesktop'] = array(
        '#title' => t('Items desktop'),
        '#type' => 'textfield',
        '#default_value' => isset($this->options['owl']['itemsDesktop']) ? $this->options['owl']['itemsDesktop'] : '1000,3',
        '#description' => t('This allows you to preset the number of slides visible with a particular browser width. The format is x,y whereby x=browser width and y=number of slides displayed. For example [1000,3] means that if(window<=1000){ show 3 slides per page}.'),
    );
    $form['owl']['itemsDesktopSmall'] = array(
        '#type' => 'textfield',
        '#title' => t('items desktop small'),
        '#default_value' => isset($this->options['owl']['itemsDesktopSmall']) ? $this->options['owl']['itemsDesktopSmall'] : '979,3',
        '#description' => t('Default is: 979,3'),
    );
    $form['owl']['itemsTablet'] = array(
        '#type' => 'textfield',
        '#title' => t('Items tablet'),
        '#default_value' => isset($this->options['owl']['itemsTablet']) ? $this->options['owl']['itemsTablet'] : '768,2',
        '#description' => t('Default is: 768,2'),
    );
    $form['owl']['itemsMobile'] = array(
        '#type' => 'textfield',
        '#title' => t('Items mobile'),
        '#default_value' => isset($this->options['owl']['itemsMobile']) ? $this->options['owl']['itemsMobile'] : '479,1',
        '#description' => t('Default is: 479,1')
    );
    $form['owl']['autoPlay'] = array(
        '#type' => 'checkbox',
        '#title' => t('Auto play'),
        '#default_value' => isset($this->options['owl']['autoPlay']) ? $this->options['owl']['autoPlay'] : FALSE,
    );
    $form['owl']['navigation'] = array(
        '#title' => t('Navigation'),
        '#type' => 'checkbox',
        '#default_value' => isset($this->options['owl']['navigation']) ? $this->options['owl']['navigation'] : FALSE,
        '#description' => t('Display "next" and "prev" buttons.')
    );
    $form['owl']['navigationText'] = array(
        '#title' => t('navigation text'),
        '#type' => 'checkbox',
        '#default_value' => isset($this->options['owl']['navigationText']) ? $this->options['owl']['navigationText'] : FALSE,
    );
    $form['owl']['pagination'] = array(
        '#title' => t('Pagination'),
        '#type' => 'checkbox',
        '#default_value' => isset($this->options['owl']['pagination']) ? $this->options['owl']['pagination'] : TRUE,
        '#description' => t('Show pagination.')
    );
    $form['owl']['mouseDrag'] = array(
        '#title' => t('Mouse drag'),
        '#type' => 'checkbox',
        '#default_value' => isset($this->options['owl']['mouseDrag']) ? $this->options['owl']['mouseDrag'] : TRUE,
        '#description' => t('Turn off/on mouse events.')
    );
    $form['owl']['touchDrag'] = array(
        '#title' => t('Touch drag'),
        '#type' => 'checkbox',
        '#default_value' => isset($this->options['owl']['touchDrag']) ? $this->options['owl']['touchDrag'] : TRUE,
        '#description' => t('Turn off/on touch events.')
    );
  }

}
