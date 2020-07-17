<?php

/**
 * @file
 * Preprocess functions and theme function overrides.
 */

/**
 * Prepares variables for page templates.
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
