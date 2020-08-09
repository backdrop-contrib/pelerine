<?php

/**
 * @file
 * Preprocess functions and theme function overrides.
 */

/**
 * Implements template_preprocess_page().
 *
 * @see page.tpl.php
 */
function pelerine_preprocess_page(&$variables) {
  global $base_path;
  // Add open sans webfont.
  backdrop_add_library('system', 'opensans', TRUE);
  // Add image url and css class.
  $image_url = theme_get_setting('image_url');
  if (!empty($image_url)) {
    $img = $base_path . $image_url;
    $css = "body {background-image: url($img);}";
    backdrop_add_css($css, 'inline');
    $variables['classes'][] = theme_get_setting('bodyclass');
  }
  // Attach custom css file if it exists.
  if (theme_get_setting('use_custom_css')) {
    global $theme;
    $rel_path = config_get('system.core', 'file_public_path') . '/' . $theme . '_custom.css';
    if (file_exists($rel_path)) {
      backdrop_add_css($base_path . $rel_path, array(
        'every_page' => TRUE,
        'preprocess' => FALSE,
        'group' => CSS_THEME,
      ));
    }
  }
}

/**
 * Implements hook_ckeditor_settings_alter().
 *
 * Set CKEditor config.defaultLanguage to current language.
 * Affects the toolbar tooltips and also the webbrowser language guessing
 * inside the iframe.
 *
 * @see https://github.com/backdrop/backdrop-issues/issues/4492
 */
function pelerine_ckeditor_settings_alter(&$settings, $format) {
  global $language;
  $settings['language'] = $language->langcode;
}
