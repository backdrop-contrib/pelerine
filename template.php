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
  // Add open sans webfont.
  backdrop_add_library('system', 'opensans', TRUE);
  // Add image url and css class.
  $image_url = theme_get_setting('image_url');
  if (!empty($image_url)) {
    global $base_path;
    $img = $base_path . $image_url;
    $css = "body {background-image: url($img);}";
    backdrop_add_css($css, 'inline');
    $variables['classes'][] = theme_get_setting('bodyclass');
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
