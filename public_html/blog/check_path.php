<?php
  // get path BOF
  $script_filename = $_SERVER['PATH_TRANSLATED'];
  if (empty($script_filename)) {
    $script_filename = $_SERVER['SCRIPT_FILENAME'];
  }
  
  $script_filename = str_replace('\\', '/', $script_filename);
  $script_filename = str_replace('//', '/', $script_filename);
  
  $dir_fs_wp_root = dirname($script_filename);
  // get path EOF
  
  echo $dir_fs_wp_root . '/';
?>