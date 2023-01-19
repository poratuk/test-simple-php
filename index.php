<?php
// Add composer requirements
require_once(__DIR__ . '/vendor/autoload.php');

// Loading for .env at the root directory
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
/**
 * This code write as one page for this task.
 * I do not use anything else as need some simple example of worked test
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
} 
else {
  // If this is not sore email or not checking 

  include_once('./includes/html_form.php');
  exit; 
}