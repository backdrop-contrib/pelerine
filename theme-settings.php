<?php

/**
 * @file
 * Theme settings.
 */

/**
 * Implements hook_form_system_theme_settings_alter().
 */
function pelerine_form_system_theme_settings_alter(&$form, &$form_state) {
  if (module_exists('color')) {
    // Until #4463 is addressed.
    // @see https://github.com/backdrop/backdrop-issues/issues/4463
    $form_state['build_info']['args'][0] = NULL;
  }

  $theme_name = $form['theme']['#value'];
  $pelerine_path = backdrop_get_path('theme', 'pelerine');
  // Could be a subtheme.
  $current_theme_path = backdrop_get_path('theme', $theme_name);

  backdrop_add_css($pelerine_path . '/css/pelerine-admin.css');
  backdrop_add_js($pelerine_path . '/js/pelerine-admin.js');

  $form['settings'] = array(
    '#type' => 'fieldset',
    '#title' => t('Settings'),
    '#collapsible' => FALSE,
  );
  $image_path = backdrop_get_path('theme', $theme_name) . '/backgrounds';
  $images = file_scan_directory($image_path, '/[a-zA-Z0-9_-]+\.(png|jpe?g)/', array(
    'recurse' => FALSE,
    'key' => 'uri',
  ));
  asort($images);
  $options = array('' => t('<none>'));
  foreach ($images as $uri => $object) {
    $css_class = backdrop_clean_css_identifier($object->name);
    $options[$uri] = $css_class;
  }
  $form['settings']['image_url'] = array(
    '#title' => t('Select background image'),
    '#type' => 'select',
    '#options' => $options,
    '#default_value' => theme_get_setting('image_url', $theme_name),
  );
  // @see _pelerine_css_class()
  $form_state['storage']['options'] = $options;
  $form['settings']['bodyclass'] = array(
    '#type' => 'value',
    '#value' => '',
  );
  $form['settings']['preview'] = array(
    '#type' => 'markup',
    '#markup' => file_get_contents($pelerine_path . '/preview.html'),
  );
  $form['#validate'][] = '_pelerine_css_class';
  // Custom css in a textarea.
  $form['use_custom_css'] = array(
    '#type' => 'checkbox',
    '#title' => t('Add custom css'),
    '#default_value' => theme_get_setting('use_custom_css', $theme_name),
  );
  $form['custom_css'] = array(
    '#type' => 'textarea',
    '#title' => t('Add your custom CSS rules'),
    '#description' => t('Note that you can not preview these rules here.'),
    '#default_value' => theme_get_setting('custom_css', $theme_name),
    '#rows' => 12,
    '#states' => array(
      'invisible' => array(
        ':input[name="use_custom_css"]' => array('checked' => FALSE),
      ),
    ),
  );
}

/**
 * Custom validation function to set a form item value.
 */
function _pelerine_css_class($form, &$form_state) {
  // Create a css body class from image.
  if (!empty($form_state['values']['image_url'])) {
    $options = $form_state['storage']['options'];
    $key = $form_state['values']['image_url'];
    $form_state['values']['bodyclass'] = $options[$key];
  }
  // Additionally save css file to disk.
  $theme_name = $form_state['values']['theme'];
  $filepath = backdrop_realpath("public://{$theme_name}_custom.css");
  if ($form_state['values']['use_custom_css']) {
    $custom_css = strip_tags($form_state['values']['custom_css']);
    file_unmanaged_save_data($custom_css, $filepath, FILE_EXISTS_REPLACE);
    $form_state['values']['custom_css'] = $custom_css;
  }
  else {
    $form_state['values']['custom_css'] = '';
    file_unmanaged_delete($filepath);
  }
}
