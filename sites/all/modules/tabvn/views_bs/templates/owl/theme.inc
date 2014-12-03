<?php

function template_preprocess_views_bs_owl_plugin_style(&$vars) {
  $module_path = drupal_get_path('module', 'views_bs');
  $view = $vars['view'];
  $element_id = $view->name . '-' . $view->style_plugin->display->id;


  static $instances = array();
  if (isset($instances[$element_id])) {
    $instances[$element_id] ++;
    $element_id .= "_" . $instances[$element_id];
  } else {
    $instances[$element_id] = 1;
  }
  $vars['element_id'] = $element_id;


  $slider_settings = $view->style_options['owl'];
  $itemsDesktop = _views_bs_owl_slider_format($slider_settings['itemsDesktop']);
  $itemsDesktopSmall = _views_bs_owl_slider_format($slider_settings['itemsDesktopSmall']);
  $itemsMobile = _views_bs_owl_slider_format($slider_settings['itemsMobile']); //itemsMobile
  $itemsTablet = _views_bs_owl_slider_format($slider_settings['itemsTablet']); //itemsTablet
  if ($itemsDesktop) {
    $slider_settings['itemsDesktop'] = $itemsDesktop;
  }
  if ($itemsDesktopSmall) {
    $slider_settings['itemsDesktopSmall'] = $itemsDesktopSmall;
  }
  if ($itemsMobile) {
    $slider_settings['itemsMobile'] = $itemsMobile;
  }
  if ($itemsTablet) {
    $slider_settings['itemsTablet'] = $itemsTablet;
  }
  $slider_settings['autoPlay'] = $slider_settings['autoPlay'] ? TRUE : FALSE;
  $slider_settings['mouseDrag'] = $slider_settings['mouseDrag'] ? TRUE : FALSE;
  $slider_settings['navigation'] = $slider_settings['navigation'] ? TRUE : FALSE;
  $slider_settings['pagination'] = $slider_settings['pagination'] ? TRUE : FALSE;
  $slider_settings['touchDrag'] = $slider_settings['touchDrag'] ? TRUE : FALSE;
  $slider_settings['navigationText'] = $slider_settings['navigationText'] ? array('prev', 'next') : FALSE;


  drupal_add_js(array(
      'viewsBs' => array(
          'owl_slider' => array(
              $element_id => array(
                  'id' => $element_id,
                  'name' => $vars['view']->name,
                  'view_name' => $vars['view']->name,
                  'display' => $vars['view']->current_display,
                  'owl' => $slider_settings,
              ),
          ),
      ),
          ), 'setting');

  drupal_add_js($module_path . '/lib/owl_carousel/views_bs_owl_slider.js');
}
