<?php

//$content_column_size_class

function rockox_process_page(&$variables) {
  if (arg(0) == 'node' && is_numeric(arg(1)) && !empty($variables['title'])) {
    $nid = arg(1);
    $node = node_load($nid);
    if (isset($node->type)) {
      switch ($node->type) {
        case 'blog':
          $variables['title'] = t('The Blog');
          break;
        case 'product':
          $variables['title'] = t('The Shop');
          break;
        case 'portfolio':
          $variables['title'] = t('Portfolio');
          break;
        default:
          break;
      }
    }
  }
}

function rockox_preprocess_page(&$variables) {
  $theme_path = path_to_theme();
  $custom_theme_css = theme_get_setting('custom_theme_css');
  $default_theme_color = theme_get_setting('theme_color');
  $dark_theme = theme_get_setting('use_dark_theme');
  if ($dark_theme) {
    drupal_add_css($theme_path . '/css/dark_theme.css', array('weight' => 999, 'group' => CSS_THEME));
  }
  if (!empty($default_theme_color)) {
    $hex_code = $default_theme_color;
    $custom_theme_css .= _rockox_css_default_settings($hex_code);
  }
  if (!empty($custom_theme_css)) {
    drupal_add_css($custom_theme_css, array('type' => 'inline', 'weight' => 1000, 'group' => CSS_THEME));
  }
  if (!module_exists('jquery_update')) {
    drupal_set_message(t('Jquery update is required, <a target="_blank" href="!url">Download it</a>,  install and switch jquery to version 1.7', array('!url' => 'http://drupal.org/project/jquery_update')), 'warning');
  } else {
    $set_jquery_update = variable_get('set_jquery_update', FALSE);
    if (!$set_jquery_update) {
      variable_set('jquery_update_jquery_version', '1.7');
      variable_set('set_jquery_update', '1.7');
    }
  }

  if (!empty($variables['page']['sidebar_first']) && !empty($variables['page']['sidebar_second'])) {
    $variables['content_column_class'] = ' class="col-md-6"';
  } elseif (!empty($variables['page']['sidebar_first']) || !empty($variables['page']['sidebar_second'])) {
    $variables['content_column_class'] = ' class="col-md-8"';
  } else {
    $variables['content_column_class'] = ' class="col-md-12"';
  }


  if (drupal_is_front_page() && !theme_get_setting('use_frontpage_title')) {
    $variables['title'] = FALSE;
  }
}

function rockox_preprocess_node(&$variables) {
  $variables['view_mode'] = $variables['elements']['#view_mode'];
// Provide a distinct $teaser boolean.
  $variables['teaser'] = $variables['view_mode'] == 'teaser';
  $variables['node'] = $variables['elements']['#node'];
  $node = $variables['node'];

  $variables['date'] = format_date($node->created);
  $variables['name'] = theme('username', array('account' => $node));

  $uri = entity_uri('node', $node);
  $variables['node_url'] = url($uri['path'], $uri['options']);
  $variables['title'] = check_plain($node->title);
  $variables['page'] = $variables['view_mode'] == 'full' && node_is_page($node);

// Flatten the node object's member fields.
  $variables = array_merge((array) $node, $variables);

// Helpful $content variable for templates.
  $variables += array('content' => array());
  foreach (element_children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }

// Make the field variables available with the appropriate language.
  field_attach_preprocess('node', $node, $variables['content'], $variables);


  if (variable_get('node_submitted_' . $node->type, TRUE)) {
    $variables['display_submitted'] = TRUE;
    $submited = '';

    $submited .= '<span class="s s-user"><i class="fa fa-user"></i> ' . $variables['name'] . '</span>';
    $submited .= '<span class="s s-date"><i class="fa fa-calendar"></i> ' . $variables['date'] . '</span>';
    if (!empty($node->comment_count)) {
      $submited .= '<span class="s s-date"><i class="fa fa-comment-o"></i> ' . t('!comment Comments', array('!comment' => $node->comment_count)) . '</span>';
    }
    $variables['submitted'] = $submited; //t('Submitted by !username on !datetime', array('!username' => $variables['name'], '!datetime' => $variables['date']));
    $variables['user_picture'] = theme_get_setting('toggle_node_user_picture') ? theme('user_picture', array('account' => $node)) : '';
  } else {
    $variables['display_submitted'] = FALSE;
    $variables['submitted'] = '';
    $variables['user_picture'] = '';
  }

// Gather node classes.
  $variables['classes_array'][] = drupal_html_class('node-' . $node->type);
  if ($variables['promote']) {
    $variables['classes_array'][] = 'node-promoted';
  }
  if ($variables['sticky']) {
    $variables['classes_array'][] = 'node-sticky';
  }
  if (!$variables['status']) {
    $variables['classes_array'][] = 'node-unpublished';
  }
  if ($variables['teaser']) {
    $variables['classes_array'][] = 'node-teaser';
  }
  if (isset($variables['preview'])) {
    $variables['classes_array'][] = 'node-preview';
  }

// Clean up name so there are no underscores.
  $variables['theme_hook_suggestions'][] = 'node__' . $node->type;
  $variables['theme_hook_suggestions'][] = 'node__' . $node->nid;
}

function rockox_breadcrumb($variables) {

  $breadcrumb = $variables['breadcrumb'];

  if (!drupal_is_front_page() && !module_exists('custom_breadcrumbs')) {
    if (arg(0) == 'node' && is_numeric(arg(1))) {
      $nid = arg(1);
      $node = node_load($nid);
      if (($node->type == 'portfolio')) {
        $breadcrumb[] = t('Portfolio');
      }
      if (($node->type == 'blog')) {
        $breadcrumb[] = t('Blog');
      }
    }
    $breadcrumb[] = drupal_get_title();
  }

  if (!empty($breadcrumb)) {

    $output = '<ol class="breadcrumb text-center">';

// Provide a navigational heading to give context for breadcrumb links to
// screen-reader users. Make the heading invisible with .element-invisible.
    foreach ($breadcrumb as $br) {
      $output .= '<li>' . $br . '</li>';
    }

    $output .= '</ol>';

    return $output;
  }
}

function rockox_button($variables) {

  $element = $variables['element'];
  $label = $element['#value'];
  element_set_attributes($element, array('id', 'name', 'value', 'type'));

// If a button type class isn't present then add in default.
  $button_classes = array(
      'btn btn-default',
      'btn btn-primary',
      'btn btn-success',
      'btn btn-info',
      'btn btn-warning',
      'btn btn-danger',
      'btn btn-link',
  );
  if (empty($element['#attributes']['class'])) {
    $element['#attributes']['class'] = array();
  }
  $class_intersection = array_intersect($button_classes, $element['#attributes']['class']);
  if (empty($class_intersection)) {
    $element['#attributes']['class'][] = 'btn btn-default';
  }

// Add in the button type class.
  $element['#attributes']['class'][] = 'form-' . $element['#button_type'];

// This line break adds inherent margin between multiple buttons.
  return '<button' . drupal_attributes($element['#attributes']) . '>' . $label . "</button>\n";
}

/*
 * Override theme_password()
 */

function rockox_password($variables) {
  $element = $variables['element'];
  $element['#attributes']['type'] = 'password';
  element_set_attributes($element, array('id', 'name', 'size', 'maxlength'));
  _form_set_class($element, array('form-text'));
  if (!empty($element['#type'])) {
    $element['#attributes']['class'][] = 'form-control';
  }
  return '<input' . drupal_attributes($element['#attributes']) . ' />';
}

/**
 * 
 * Override rockox_select()
 */
function rockox_select($variables) {
  $element = $variables['element'];
  element_set_attributes($element, array('id', 'name', 'size'));
  _form_set_class($element, array('form-select'));
  if (!empty($element['#type'])) {
    $element['#attributes']['class'][] = 'form-control';
  }
  return '<select' . drupal_attributes($element['#attributes']) . '>' . form_select_options($element) . '</select>';
}

/**
 * override theme_textarea()
 */
function rockox_textarea($variables) {
  $element = $variables['element'];
  element_set_attributes($element, array('id', 'name', 'cols', 'rows'));
  _form_set_class($element, array('form-textarea'));

  if (!empty($element['#type'])) {
    $element['#attributes']['class'][] = 'form-control';
  }
  $wrapper_attributes = array(
      'class' => array('form-textarea-wrapper'),
  );

// Add resizable behavior.
  if (!empty($element['#resizable'])) {
    drupal_add_library('system', 'drupal.textarea');
    $wrapper_attributes['class'][] = 'resizable';
  }

  $output = '<div' . drupal_attributes($wrapper_attributes) . '>';
  $output .= '<textarea' . drupal_attributes($element['#attributes']) . '>' . check_plain($element['#value']) . '</textarea>';
  $output .= '</div>';
  return $output;
}

function rockox_webform_email($variables) {
  $element = $variables['element'];

// This IF statement is mostly in place to allow our tests to set type="text"
// because SimpleTest does not support type="email".
  if (!isset($element['#attributes']['type'])) {
    $element['#attributes']['type'] = 'email';
  }
  $element['#attributes']['class'][] = 'form-control';

// Convert properties to attributes on the element if set.
  foreach (array('id', 'name', 'value', 'size') as $property) {
    if (isset($element['#' . $property]) && $element['#' . $property] !== '') {
      $element['#attributes'][$property] = $element['#' . $property];
    }
  }
  _form_set_class($element, array('form-text', 'form-email'));

  return '<input' . drupal_attributes($element['#attributes']) . ' />';
}

function rockox_textfield($variables) {
  $element = $variables['element'];
  $element['#attributes']['type'] = 'text';
  element_set_attributes($element, array('id', 'name', 'value', 'size', 'maxlength'));
  _form_set_class($element, array('form-text'));

  $extra = '';
  $types = array(
// Core.
      'password',
      'password_confirm',
      'select',
      'textarea',
      'textfield',
      // Elements module.
      'emailfield',
      'numberfield',
      'rangefield',
      'searchfield',
      'telfield',
      'urlfield',
      // webform
      'webform_email',
  );

  if (!empty($element['#type']) && (in_array($element['#type'], $types) || ($element['#type'] === 'file' && empty($element['#managed_file'])))) {
    $element['#attributes']['class'][] = 'form-control';
  }


  if ($element['#autocomplete_path'] && drupal_valid_path($element['#autocomplete_path'])) {
    drupal_add_library('system', 'drupal.autocomplete');
    $element['#attributes']['class'][] = 'form-autocomplete';

    $attributes = array();
    $attributes['type'] = 'hidden';
    $attributes['id'] = $element['#attributes']['id'] . '-autocomplete';
    $attributes['value'] = url($element['#autocomplete_path'], array('absolute' => TRUE));
    $attributes['disabled'] = 'disabled';
    $attributes['class'][] = 'autocomplete';
    $extra = '<input' . drupal_attributes($attributes) . ' />';
  }

  $output = '<input' . drupal_attributes($element['#attributes']) . ' />';

  return $output . $extra;
}

function rockox_fieldset($variables) {
  $element = $variables['element'];
  element_set_attributes($element, array('id'));
  _form_set_class($element, array('form-wrapper'));

  if (isset($element['#parents'])) {
    $parents = implode('][', $element['#parents']);


// Each fieldset forms a new group. The #type 'vertical_tabs' basically only
// injects a new fieldset.
    $form_state['groups'][$parents]['#group_exists'] = TRUE;
  }
  $element['#groups'] = &$form_state['groups'];

// Process vertical tabs group member fieldsets.
  if (isset($element['#group'])) {
// Add this fieldset to the defined group (by reference).
    $element['#theme_wrappers'] = array('rockox_panel');
    $group = $element['#group'];
    $form_state['groups'][$group][] = &$element;
  }

// Contains form element summary functionalities.
  $element['#attached']['library'][] = array('system', 'drupal.form');

// The .form-wrapper class is required for #states to treat fieldsets like
// containers.
  if (!isset($element['#attributes']['class'])) {
    $element['#attributes']['class'] = array();
  }


  return theme('rockox_panel', $variables);
}

function rockox_preprocess_rockox_panel(&$variables) {
  $element = &$variables['element'];
  $attributes = !empty($element['#attributes']) ? $element['#attributes'] : array();
  $attributes['class'][] = 'panel';
  $attributes['class'][] = 'panel-default';
// states.js requires form-wrapper on fieldset to work properly.
  $attributes['class'][] = 'form-wrapper';
  $variables['collapsible'] = FALSE;
  if (isset($element['#collapsible'])) {
    $variables['collapsible'] = $element['#collapsible'];
  }
  $variables['collapsed'] = FALSE;
  if (isset($element['#collapsed'])) {
    $variables['collapsed'] = $element['#collapsed'];
  }
// Force grouped fieldsets to not be collapsible (for vertical tabs).
  if (!empty($element['#group'])) {
    $variables['collapsible'] = FALSE;
    $variables['collapsed'] = FALSE;
  }
  $variables['id'] = '';
  if (isset($element['#id'])) {
    if ($variables['collapsible']) {
      $variables['id'] = $element['#id'];
    } else {
      $attributes['id'] = $element['#id'];
    }
  }
  $variables['content'] = $element['#children'];

// Iterate over optional variables.
  $keys = array(
      'description',
      'prefix',
      'suffix',
      'title',
  );
  foreach ($keys as $key) {
    $variables[$key] = !empty($element["#$key"]) ? $element["#$key"] : FALSE;
  }
  $variables['attributes'] = $attributes;
}

/**
 * Implements hook_process_rockox_panel().
 */
function rockox_process_rockox_panel(&$variables) {
  $variables['attributes'] = drupal_attributes($variables['attributes']);
}

/*
 * Implements hook_theme()
 */

function rockox_theme($existing, $type, $theme, $path) {
  $hook_theme = array(
      'rockox_links' => array(
          'variables' => array(
              'links' => array(),
              'attributes' => array(),
              'heading' => NULL,
          ),
      ),
      'rockox_btn_dropdown' => array(
          'variables' => array(
              'links' => array(),
              'attributes' => array(),
              'type' => NULL,
          ),
      ),
      'rockox_modal' => array(
          'variables' => array(
              'heading' => '',
              'body' => '',
              'footer' => '',
              'attributes' => array(),
              'html_heading' => FALSE,
          ),
      ),
      'rockox_accordion' => array(
          'variables' => array(
              'id' => '',
              'elements' => array(),
          ),
      ),
      'rockox_search_form_wrapper' => array(
          'render element' => 'element',
      ),
      'rockox_panel' => array(
          'render element' => 'element',
          'template' => 'rockox-panel',
          'path' => $path . '/templates'
      ),
  );

  return $hook_theme;
}

/**
 * Overrides theme_status_messages().
 */
function rockox_status_messages($variables) {
  $display = $variables['display'];
  $output = '';

  $status_heading = array(
      'status' => t('Status message'),
      'error' => t('Error message'),
      'warning' => t('Warning message'),
      'info' => t('Informative message'),
  );

// Map Drupal message types to their corresponding Bootstrap classes.
// @see http://twitter.github.com/rockox/components.html#alerts
  $status_class = array(
      'status' => 'success',
      'error' => 'danger',
      'warning' => 'warning',
      // Not supported, but in theory a module could send any type of message.
// @see drupal_set_message()
// @see theme_status_messages()
      'info' => 'info',
  );

  foreach (drupal_get_messages($display) as $type => $messages) {
    $class = (isset($status_class[$type])) ? ' alert-' . $status_class[$type] : '';
    $output .= "<div class=\"alert alert-block$class\">\n";
    $output .= "  <a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a>\n";

    if (!empty($status_heading[$type])) {
      $output .= '<h4 class="element-invisible">' . $status_heading[$type] . "</h4>\n";
    }

    if (count($messages) > 1) {
      $output .= " <ul>\n";
      foreach ($messages as $message) {
        $output .= '  <li>' . $message . "</li>\n";
      }
      $output .= " </ul>\n";
    } else {
      $output .= $messages[0];
    }

    $output .= "</div>\n";
  }
  return $output;
}

/**
 * Overrides theme_container().
 */
function rockox_container($variables) {
  $element = $variables['element'];

// Special handling for form elements.
  if (isset($element['#array_parents'])) {
// Assign an html ID.
    if (!isset($element['#attributes']['id'])) {
      $element['#attributes']['id'] = $element['#id'];
    }
// Add classes.
    $element['#attributes']['class'][] = 'form-wrapper';
    $element['#attributes']['class'][] = 'form-group';
  }

  return '<div' . drupal_attributes($element['#attributes']) . '>' . $element['#children'] . '</div>';
}

function rockox_rockox_search_form_wrapper($variables) {
  $output = '<div class="searchform-alter input-group">';
  $output .= $variables['element']['#children'];
  $output .= '<span class="input-group-btn">';
  $output .= '<button type="submit" class="btn btn-default">';
// $output .= '<i class="icon fa fa-search" aria-hidden="true"></i>';
  $output .= t('Search');

  $output .= '</button>';
  $output .= '</span>';
  $output .= '</div>';
  return $output;
}

/**
 * Implements hook_form_alter().
 */
function rockox_form_alter(array &$form, array &$form_state = array(), $form_id = NULL) {
  if ($form_id) {
// IDs of forms that should be ignored. Make this configurable?
// @todo is this still needed?
    $form_ids = array(
        'node_form',
        'system_site_information_settings',
        'user_profile_form',
        'node_delete_confirm',
    );
// Only wrap in container for certain form.
    if (!in_array($form_id, $form_ids) && !isset($form['#node_edit_form']) && isset($form['actions']) && isset($form['actions']['#type']) && ($form['actions']['#type'] == 'actions')) {
      $form['actions']['#theme_wrappers'] = array();
    }

    switch ($form_id) {


      case 'search_form':
// Add a clearfix class so the results don't overflow onto the form.
        $form['#attributes']['class'][] = 'clearfix';

// Remove container-inline from the container classes.
        $form['basic']['#attributes']['class'] = array();

// Hide the default button from display.
        $form['basic']['submit']['#attributes']['class'][] = 'element-invisible';

// Implement a theme wrapper to add a submit button containing a search
// icon directly after the input element.
        $form['basic']['keys']['#theme_wrappers'] = array('rockox_search_form_wrapper');
        $form['basic']['keys']['#title'] = '';
        $form['basic']['keys']['#attributes']['placeholder'] = t('Search');
        break;

      case 'search_block_form':
        $form['#attributes']['class'][] = 'search-form form-search';

        $form['search_block_form']['#title'] = '';
        $form['search_block_form']['#attributes']['placeholder'] = t('Search');

// Hide the default button from display and implement a theme wrapper
// to add a submit button containing a search icon directly after the
// input element.
        $form['actions']['submit']['#attributes']['class'][] = 'element-invisible';
        $form['search_block_form']['#theme_wrappers'] = array('rockox_search_form_wrapper');

// Apply a clearfix so the results don't overflow onto the form.
        $form['#attributes']['class'][] = 'content-search';
        break;
    }
  }
}

/**
 * Overrides theme_menu_local_task().
 */
function rockox_menu_local_task($variables) {
  $link = $variables['element']['#link'];
  $link_text = $link['title'];
  $classes = array();

  if (!empty($variables['element']['#active'])) {
// Add text to indicate active tab for non-visual users.
    $active = '<span class="element-invisible">' . t('(active tab)') . '</span>';

// If the link does not contain HTML already, check_plain() it now.
// After we set 'html'=TRUE the link will not be sanitized by l().
    if (empty($link['localized_options']['html'])) {
      $link['title'] = check_plain($link['title']);
    }
    $link['localized_options']['html'] = TRUE;
    $link_text = t('!local-task-title!active', array('!local-task-title' => $link['title'], '!active' => $active));

    $classes[] = 'active';
  }

  return '<li class="' . implode(' ', $classes) . '">' . l($link_text, $link['href'], $link['localized_options']) . "</li>\n";
}

/**
 * Overrides theme_menu_local_tasks().
 */
function rockox_menu_local_tasks(&$variables) {
  $output = '';

  if (!empty($variables['primary'])) {
    $variables['primary']['#prefix'] = '<h2 class="element-invisible">' . t('Primary tabs') . '</h2>';
    $variables['primary']['#prefix'] .= '<ul class="tabs--primary nav nav-tabs">';
    $variables['primary']['#suffix'] = '</ul>';
    $output .= drupal_render($variables['primary']);
  }

  if (!empty($variables['secondary'])) {
    $variables['secondary']['#prefix'] = '<h2 class="element-invisible">' . t('Secondary tabs') . '</h2>';
    $variables['secondary']['#prefix'] .= '<ul class="tabs--secondary nav nav-tabs">';
    $variables['secondary']['#suffix'] = '</ul>';
    $output .= drupal_render($variables['secondary']);
  }

  return $output;
}

/**
 * Overrides theme_file_managed_file().
 */
function rockox_file_managed_file($variables) {
  $element = $variables['element'];

  $attributes = array();
  if (isset($element['#id'])) {
    $attributes['id'] = $element['#id'];
  }
  if (!empty($element['#attributes']['class'])) {
    $attributes['class'] = (array) $element['#attributes']['class'];
  }
  $attributes['class'][] = 'form-managed-file';
  $attributes['class'][] = 'input-group';

  $element['upload_button']['#prefix'] = '<span class="input-group-btn">';
  $element['upload_button']['#suffix'] = '</span>';
  $element['remove_button']['#prefix'] = '<span class="input-group-btn">';
  $element['remove_button']['#suffix'] = '</span>';

  if (!empty($element['filename'])) {
    $element['filename']['#prefix'] = '<div class="form-control">';
    $element['filename']['#suffix'] = '</div>';
  }

  $hidden_elements = array();
  foreach (element_children($element) as $child) {
    if ($element[$child]['#type'] === 'hidden') {
      $hidden_elements[$child] = $element[$child];
      unset($element[$child]);
    }
  }

// This wrapper is required to apply JS behaviors and CSS styling.
  $output = '';
  $output .= '<div' . drupal_attributes($attributes) . '>';
  $output .= drupal_render_children($element);
  $output .= '</div>';
  $output .= render($hidden_elements);
  return $output;
}

/**
 * Overrides theme_file_widget().
 */
function rockox_file_widget($variables) {
  $element = $variables['element'];
  $output = '';

  $hidden_elements = array();
  foreach (element_children($element) as $child) {
    if ($element[$child]['#type'] === 'hidden') {
      $hidden_elements[$child] = $element[$child];
      unset($element[$child]);
    }
  }

  $element['upload_button']['#prefix'] = '<span class="input-group-btn">';
  $element['upload_button']['#suffix'] = '</span>';

// The "form-managed-file" class is required for proper Ajax functionality.
  $output .= '<div class="file-widget form-managed-file clearfix input-group">';
  if (!empty($element['fid']['#value'])) {
// Add the file size after the file name.
    $element['filename']['#markup'] .= ' <span class="file-size">(' . format_size($element['#file']->filesize) . ')</span> ';
  }
  $output .= drupal_render_children($element);
  $output .= '</div>';
  $output .= render($hidden_elements);
  return $output;
}

/**
 * Implements hook_preprocess_views_view_table().
 */
function rockox_preprocess_views_view_table(&$vars) {
  $vars['classes_array'][] = 'table';
}

/**
 * Overrides theme_form_element_label().
 */
function rockox_form_element_label(&$variables) {
  $element = $variables['element'];

// This is also used in the installer, pre-database setup.
  $t = get_t();

// Determine if certain things should skip for checkbox or radio elements.
  $skip = (isset($element['#type']) && ('checkbox' === $element['#type'] || 'radio' === $element['#type']));

// If title and required marker are both empty, output no label.
  if ((!isset($element['#title']) || $element['#title'] === '' && !$skip) && empty($element['#required'])) {
    return '';
  }

// If the element is required, a required marker is appended to the label.
  $required = !empty($element['#required']) ? theme('form_required_marker', array('element' => $element)) : '';

  $title = filter_xss_admin($element['#title']);

  $attributes = array();

// Style the label as class option to display inline with the element.
  if ($element['#title_display'] == 'after' && !$skip) {
    $attributes['class'][] = $element['#type'];
  }
// Show label only to screen readers to avoid disruption in visual flows.
  elseif ($element['#title_display'] == 'invisible') {
    $attributes['class'][] = 'element-invisible';
  }

  if (!empty($element['#id'])) {
    $attributes['for'] = $element['#id'];
  }

// Insert radio and checkboxes inside label elements.
  $output = '';
  if (isset($variables['#children'])) {
    $output .= $variables['#children'];
  }

// Append label.
  $output .= $t('!title !required', array('!title' => $title, '!required' => $required));

// The leading whitespace helps visually separate fields from inline labels.
  return ' <label' . drupal_attributes($attributes) . '>' . $output . "</label>\n";
}

/**
 * Overrides theme_form_element().
 */
function rockox_form_element(&$variables) {
  $element = &$variables['element'];
  $is_checkbox = FALSE;
  $is_radio = FALSE;

// This function is invoked as theme wrapper, but the rendered form element
// may not necessarily have been processed by form_builder().
  $element += array(
      '#title_display' => 'before',
  );

// Add element #id for #type 'item'.
  if (isset($element['#markup']) && !empty($element['#id'])) {
    $attributes['id'] = $element['#id'];
  }

// Check for errors and set correct error class.
  if (isset($element['#parents']) && form_get_error($element)) {
    $attributes['class'][] = 'error';
  }

  if (!empty($element['#type'])) {
    $attributes['class'][] = 'form-type-' . strtr($element['#type'], '_', '-');
  }
  if (!empty($element['#name'])) {
    $attributes['class'][] = 'form-item-' . strtr($element['#name'], array(
                ' ' => '-',
                '_' => '-',
                '[' => '-',
                ']' => '',
    ));
  }
// Add a class for disabled elements to facilitate cross-browser styling.
  if (!empty($element['#attributes']['disabled'])) {
    $attributes['class'][] = 'form-disabled';
  }
  if (!empty($element['#autocomplete_path']) && drupal_valid_path($element['#autocomplete_path'])) {
    $attributes['class'][] = 'form-autocomplete';
  }
  $attributes['class'][] = 'form-item';


  if (isset($element['#type'])) {
    if ($element['#type'] == "radio") {
      $attributes['class'][] = 'radio';
      $is_radio = TRUE;
    } elseif ($element['#type'] == "checkbox") {
      $attributes['class'][] = 'checkbox';
      $is_checkbox = TRUE;
    } else {
      $attributes['class'][] = 'form-group';
    }
  }

  $description = FALSE;
  $tooltip = FALSE;
// Convert some descriptions to tooltips.
// @see rockox_tooltip_descriptions setting in _rockox_settings_form()
  if (!empty($element['#description'])) {
    $description = $element['#description'];
    if (theme_get_setting('rockox_tooltip_enabled') && theme_get_setting('rockox_tooltip_descriptions') && $description === strip_tags($description) && strlen($description) <= 200) {
      $tooltip = TRUE;
      $attributes['data-toggle'] = 'tooltip';
      $attributes['title'] = $description;
    }
  }

  $output = '<div' . drupal_attributes($attributes) . '>' . "\n";

// If #title is not set, we don't display any label or required marker.
  if (!isset($element['#title'])) {
    $element['#title_display'] = 'none';
  }

  $prefix = '';
  $suffix = '';
  if (isset($element['#field_prefix']) || isset($element['#field_suffix'])) {
// Determine if "#input_group" was specified.
    if (!empty($element['#input_group'])) {
      $prefix .= '<div class="input-group">';
      $prefix .= isset($element['#field_prefix']) ? '<span class="input-group-addon">' . $element['#field_prefix'] . '</span>' : '';
      $suffix .= isset($element['#field_suffix']) ? '<span class="input-group-addon">' . $element['#field_suffix'] . '</span>' : '';
      $suffix .= '</div>';
    } else {
      $prefix .= isset($element['#field_prefix']) ? $element['#field_prefix'] : '';
      $suffix .= isset($element['#field_suffix']) ? $element['#field_suffix'] : '';
    }
  }

  switch ($element['#title_display']) {
    case 'before':
    case 'invisible':
      $output .= ' ' . theme('form_element_label', $variables);
      $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
      break;

    case 'after':
      if ($is_radio || $is_checkbox) {
        $output .= ' ' . $prefix . $element['#children'] . $suffix;
      } else {
        $variables['#children'] = ' ' . $prefix . $element['#children'] . $suffix;
      }
      $output .= ' ' . theme('form_element_label', $variables) . "\n";
      break;

    case 'none':
    case 'attribute':
// Output no label and no required marker, only the children.
      $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
      break;
  }

  if ($description && !$tooltip) {
    $output .= '<p class="help-block">' . $element['#description'] . "</p>\n";
  }

  $output .= "</div>\n";

  return $output;
}

/**
 * Overrides theme_pager().
 */
function rockox_pager($variables) {
  $output = "";
  $items = array();
  $tags = $variables['tags'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  $quantity = $variables['quantity'];

  global $pager_page_array, $pager_total;

// Calculate various markers within this pager piece:
// Middle is used to "center" pages around the current page.
  $pager_middle = ceil($quantity / 2);
// Current is the page we are currently paged to.
  $pager_current = $pager_page_array[$element] + 1;
// First is the first page listed by this pager piece (re quantity).
  $pager_first = $pager_current - $pager_middle + 1;
// Last is the last page listed by this pager piece (re quantity).
  $pager_last = $pager_current + $quantity - $pager_middle;
// Max is the maximum page number.
  $pager_max = $pager_total[$element];

// Prepare for generation loop.
  $i = $pager_first;
  if ($pager_last > $pager_max) {
// Adjust "center" if at end of query.
    $i = $i + ($pager_max - $pager_last);
    $pager_last = $pager_max;
  }
  if ($i <= 0) {
// Adjust "center" if at start of query.
    $pager_last = $pager_last + (1 - $i);
    $i = 1;
  }

// End of generation loop preparation.
// @todo add theme setting for this.
// $li_first = theme('pager_first', array(
// 'text' => (isset($tags[0]) ? $tags[0] : t('first')),
// 'element' => $element,
// 'parameters' => $parameters,
// ));
  $li_previous = theme('pager_previous', array(
      'text' => (isset($tags[1]) ? $tags[1] : t('previous')),
      'element' => $element,
      'interval' => 1,
      'parameters' => $parameters,
  ));
  $li_next = theme('pager_next', array(
      'text' => (isset($tags[3]) ? $tags[3] : t('next')),
      'element' => $element,
      'interval' => 1,
      'parameters' => $parameters,
  ));
  $li_last = theme('pager_last', array('text' => (isset($tags[4]) ? $tags[4] : t('last »')), 'element' => $element, 'parameters' => $parameters));
// @todo add theme setting for this.
// $li_last = theme('pager_last', array(
// 'text' => (isset($tags[4]) ? $tags[4] : t('last')),
// 'element' => $element,
// 'parameters' => $parameters,
// ));
  if ($pager_total[$element] > 1) {
// @todo add theme setting for this.
// if ($li_first) {
// $items[] = array(
// 'class' => array('pager-first'),
// 'data' => $li_first,
// );
// }
    if ($li_previous) {
      $items[] = array(
          'class' => array('prev'),
          'data' => $li_previous,
      );
    }
// When there is more than one page, create the pager list.
    if ($i != $pager_max) {
      if ($i > 1) {
        $items[] = array(
            'class' => array('pager-ellipsis', 'disabled'),
            'data' => '<span>…</span>',
        );
      }
// Now generate the actual pager piece.
      for (; $i <= $pager_last && $i <= $pager_max; $i++) {
        if ($i < $pager_current) {
          $items[] = array(
// 'class' => array('pager-item'),
              'data' => theme('pager_previous', array(
                  'text' => $i,
                  'element' => $element,
                  'interval' => ($pager_current - $i),
                  'parameters' => $parameters,
              )),
          );
        }
        if ($i == $pager_current) {
          $items[] = array(
// Add the active class.
              'class' => array('active'),
              'data' => l($i, '#', array('fragment' => '', 'external' => TRUE)),
          );
        }
        if ($i > $pager_current) {
          $items[] = array(
              'data' => theme('pager_next', array(
                  'text' => $i,
                  'element' => $element,
                  'interval' => ($i - $pager_current),
                  'parameters' => $parameters,
              )),
          );
        }
      }
      if ($i < $pager_max) {
        $items[] = array(
            'class' => array('pager-ellipsis', 'disabled'),
            'data' => '<span>…</span>',
        );
      }
    }
// End generation.
    if ($li_next) {
      $items[] = array(
          'class' => array('next', 'pager-next'),
          'data' => $li_next,
      );
    }

    if ($li_last) {
      $items[] = array(
          'class' => array('pager-last'),
          'data' => $li_last,
      );
    }

    return '<div class="text-center">' . theme('item_list', array(
                'items' => $items,
                'attributes' => array('class' => array('pagination', 'pager')),
            )) . '</div>';
  }
  return $output;
}

/**
 * Implements hook_preprocess_table().
 */
function rockox_preprocess_table(&$variables) {
  if (isset($variables['attributes']['class']) && is_string($variables['attributes']['class'])) {
// Convert classes to an array.
    $variables['attributes']['class'] = explode(' ', $variables['attributes']['class']);
  }
  $variables['attributes']['class'][] = 'table';
  if (!in_array('table-no-striping', $variables['attributes']['class'])) {
    $variables['attributes']['class'][] = 'table-striped';
  }
}

/**
 * Overrides theme_date().
 */
function rockox_date($variables) {
  $element = $variables['element'];

  $attributes = array();
  if (isset($element['#id'])) {
    $attributes['id'] = $element['#id'];
  }
  if (!empty($element['#attributes']['class'])) {
    $attributes['class'] = (array) $element['#attributes']['class'];
  }
  $attributes['class'][] = 'form-inline';

  return '<div' . drupal_attributes($attributes) . '>' . drupal_render_children($element) . '</div>';
}

/**
 * Overrides theme_exposed_filters().
 */
function rockox_exposed_filters($variables) {
  $form = $variables['form'];
  $output = '';

  foreach (element_children($form['status']['filters']) as $key) {
    $form['status']['filters'][$key]['#field_prefix'] = '<div class="col-sm-10">';
    $form['status']['filters'][$key]['#field_suffix'] = '</div>';
  }
  $form['status']['actions']['#attributes']['class'][] = 'col-sm-offset-2';
  $form['status']['actions']['#attributes']['class'][] = 'col-sm-10';
  $form['status']['actions']['#prefix'] = '<div class="form-group">';
  $form['status']['actions']['#suffix'] = '</div>';

  if (isset($form['current'])) {
    $items = array();
    foreach (element_children($form['current']) as $key) {
      $items[] = drupal_render($form['current'][$key]);
    }
    $output .= theme('item_list', array(
        'items' => $items,
        'attributes' => array(
            'class' => array(
                'clearfix',
                'current-filters',
            ),
        ),
    ));
  }
  $output .= drupal_render_children($form);
  return '<div class="form-horizontal">' . $output . '</div>';
}

/**
 * Theme function to allow any menu tree to be themed as a Superfish menu.
 */
function rockox_superfish($variables) {
  global $user, $language;

  $id = $variables['id'];
  $menu_name = $variables['menu_name'];
  $mlid = $variables['mlid'];
  $sfsettings = $variables['sfsettings'];

  $menu = menu_tree_all_data($menu_name);

  if (function_exists('i18n_menu_localize_tree')) {
    $menu = i18n_menu_localize_tree($menu);
  }

// For custom $menus and menus built all the way from the top-level we
// don't need to "create" the specific sub-menu and we need to get the title
// from the $menu_name since there is no "parent item" array.
// Create the specific menu if we have a mlid.
  if (!empty($mlid)) {
// Load the parent menu item.
    $item = menu_link_load($mlid);
    $title = check_plain($item['title']);
    $parent_depth = $item['depth'];
// Narrow down the full menu to the specific sub-tree we need.
    for ($p = 1; $p < 10; $p++) {
      if ($sub_mlid = $item["p$p"]) {
        $subitem = menu_link_load($sub_mlid);
        $key = (50000 + $subitem['weight']) . ' ' . $subitem['title'] . ' ' . $subitem['mlid'];
        $menu = (isset($menu[$key]['below'])) ? $menu[$key]['below'] : $menu;
      }
    }
  } else {
    $result = db_query("SELECT title FROM {menu_custom} WHERE menu_name = :a", array(':a' => $menu_name))->fetchField();
    $title = check_plain($result);
  }

  $output['content'] = '';
  $output['subject'] = $title;
  if ($menu) {
// Set the total menu depth counting from this parent if we need it.
    $depth = $sfsettings['depth'];
    $depth = ($sfsettings['depth'] > 0 && isset($parent_depth)) ? $parent_depth + $depth : $depth;

    $var = array(
        'id' => $id,
        'menu' => $menu,
        'depth' => $depth,
        'trail' => superfish_build_page_trail(menu_tree_page_data($menu_name)),
        'sfsettings' => $sfsettings
    );
    if ($menu_tree = theme('superfish_build', $var)) {
      if ($menu_tree['content']) {
// Add custom HTML codes around the main menu.
        if ($sfsettings['wrapmul'] && strpos($sfsettings['wrapmul'], ',') !== FALSE) {
          $wmul = explode(',', $sfsettings['wrapmul']);
// In case you just wanted to add something after the element.
          if (drupal_substr($sfsettings['wrapmul'], 0) == ',') {
            array_unshift($wmul, '');
          }
        } else {
          $wmul = array();
        }
        $output['content'] = isset($wmul[0]) ? $wmul[0] : '';
        $output['content'] .= '<ul id="superfish-' . $id . '"';
        $output['content'] .= ' class="menu sf-menu nav navbar-nav navbar-right sf-' . $menu_name . ' sf-' . $sfsettings['type'] . ' sf-style-' . $sfsettings['style'];
        $output['content'] .= ($sfsettings['itemcounter']) ? ' sf-total-items-' . $menu_tree['total_children'] : '';
        $output['content'] .= ($sfsettings['itemcounter']) ? ' sf-parent-items-' . $menu_tree['parent_children'] : '';
        $output['content'] .= ($sfsettings['itemcounter']) ? ' sf-single-items-' . $menu_tree['single_children'] : '';
        $output['content'] .= ($sfsettings['ulclass']) ? ' ' . $sfsettings['ulclass'] : '';
        $output['content'] .= ($language->direction == 1) ? ' rtl' : '';
        $output['content'] .= '">' . $menu_tree['content'] . '</ul>';
        $output['content'] .= isset($wmul[1]) ? $wmul[1] : '';
      }
    }
  }
  return $output;
}

function rockox_colorbox_imagefield($variables) {
  $class = array('colorbox');

  if ($variables['image']['style_name'] == 'hide') {
    $image = '';
    $class[] = 'js-hide';
  } elseif (!empty($variables['image']['style_name'])) {
    $image = theme('image_style', $variables['image']);
  } else {
    $image = theme('image', $variables['image']);
  }

  $options = drupal_parse_url($variables['path']);
  $options += array(
      'html' => TRUE,
      'attributes' => array(
          'title' => $variables['title'],
          'class' => $class,
          'rel' => $variables['gid'],
      ),
      'language' => array('language' => NULL),
  );

  $image = '<span class="o-hover"><span><i class="fa fa-search fa-2x"></i></span></span>' . $image;

  $output = '<div class="colorbox-thumbnail overlay-item">';
  $output .= l($image, $options['path'], $options);
  $output .= '</div>';
  return $output;
}

function rockox_tagclouds_weighted(array $vars) {
  $terms = $vars['terms'];

  $output = '';
  $display = variable_get('tagclouds_display_type', 'style');

  if (module_exists('i18n_taxonomy')) {
    $language = i18n_language();
  }

  if ($display == 'style') {
    foreach ($terms as $term) {
      if (module_exists('i18n_taxonomy')) {
        $term_name = i18n_taxonomy_term_name($term, $language->language);
        $term_desc = tagclouds_i18n_taxonomy_term_description($term, $language->language);
      } else {
        $term_name = $term->name;
        $term_desc = $term->description;
      }
      $output .= _rockox_tagclouds_display_term_link_weight($term_name, $term->tid, $term->weight, $term_desc);
    }
  } else {
    foreach ($terms as $term) {
      if (module_exists('i18n_taxonomy')) {
        $term_name = i18n_taxonomy_term_name($term, $language->language);
        $term_desc = tagclouds_i18n_taxonomy_term_description($term, $language->language);
      } else {
        $term_name = $term->name;
        $term_desc = $term->description;
      }
      if ($term->count == 1 && variable_get("tagclouds_display_node_link", false)) {
        $output .= tagclouds_display_node_link_count($term_name, $term->tid, $term->nid, $term->count, $term_desc);
      } else {
        $output .= tagclouds_display_term_link_count($term_name, $term->tid, $term->count, $term_desc);
      }
    }
  }
  return $output;
}

function _rockox_tagclouds_display_term_link_weight($name, $tid, $weight, $description) {
  if ($term = taxonomy_term_load($tid)) {
    $uri = entity_uri('taxonomy_term', $term);
    $uri['options']['attributes']['class'][] = 'tagclouds btn btn-default btn-sm';
    $uri['options']['attributes']['class'][] = 'level' . $weight;
    $uri['options']['attributes']['title'] = $description;
    return "<span class='tagclouds-term'>" . l($name, $uri['path'], $uri['options']) . "</span>\n";
  }
}

function _process_format_video($url) {
  $id = '';
  if ((strpos($url, 'youtube.com') !== FALSE) || (strpos($url, 'youtu.be') !== FALSE)) {
    $id = _get_youtube($url);
  } else {
    $id = _get_vimeo($url);
  }

  return $id;
}

function _get_vimeo($url) {
  $html = '';
  $pattern = '/\/\/(www\.)?vimeo.com\/(\d+)($|\/)/';
  preg_match($pattern, $url, $matches);
  if (count($matches)) {
    $id = $matches[2];

    $html .= 'http://player.vimeo.com/video/' . $id;
  }

  return $html;
}

function _get_youtube($url) {
//youtube theme process.
  $html = '';
  preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches);
//dsm($matches);
  if (!empty($matches[1])) {
    $id = $matches[1];
//$html .= 'https://www.youtube.com/embed/' . $id . '?rel=0&wmode=transparent&autoplay=1';
    $html .= 'http://www.youtube.com/watch?v=' . $id;
  }

  return $html;
}

function _rockox_css_default_settings($hex_code) {
  $css = '.text-color,
.btn.outline.color,
a.arrow-link:hover,
a.arrow-link:hover:before,
.service-item:hover > i,
.service-item:hover > a.arrow-link:before,
.c-details a:hover,
.c-details a:hover i,
div#views_infinite_scroll-ajax-loader i {
	color: ' . $hex_code . ';
}

button:hover,
input[type="submit"]:hover,
a .icon:hover,
a.btn.outline:hover,
button.outline:hover,
input[type="submit"].outline:hover,
.btn.color,
.icon-nav a:hover > i,
a.btn.outline:hover, button.outline:hover, input.outline[type="submit"]:hover, .btn-primary:hover, .btn-default:hover, .option-set .btn.btn-primary {
	background: ' . $hex_code . ';
}

/* Border color */
.btn.outline.color {
	border: 2px solid #18c0cf;
}

a.btn.outline:hover,
button.outline:hover,
input[type="submit"].outline:hover,a.btn.outline:hover, button.outline:hover, input.outline[type="submit"]:hover, .btn-primary:hover, .btn-default:hover, .option-set .btn.btn-primary{
	border: 2px solid ' . $hex_code . ';
}

/* RGBA background color */
.back-top {
	background: ' . $hex_code . ';
	opacity: 0.9;
}

.back-top:hover {
	opacity:1;
    background: ' . $hex_code . ';
}';

  return $css;
}
