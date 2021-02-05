<?php

define('PLUGIN_FIXMAILCOLLECTOR_VERSION', '1.0.1');

$folder = basename(dirname(__FILE__));

if ($folder !== "fixmailcollector") {
   $msg = sprintf("Please, rename the plugin folder \"%s\" to \"fixmailcollector\"", $folder);
   Session::addMessageAfterRedirect($msg, true, ERROR);
}

// Init the hooks of the plugins -Needed
function plugin_init_fixmailcollector() {
   global $PLUGIN_HOOKS;
   $PLUGIN_HOOKS['csrf_compliant']['fixmailcollector'] = true;

   // Prioritize the localfile instead of vendor
   spl_autoload_register(function ($class) {
      if ($class === 'Laminas\Mail\Header\ContentDisposition') {
         include __DIR__ . '/Normalizer.php';
         include __DIR__ . '/ContentDisposition.php';
      }
   }, true, true);

   return true;
}

// Get the name and the version of the plugin - Needed
function plugin_version_fixmailcollector() {
   return [
      'name'           => 'Fix Mail Collector',
      'version'        => PLUGIN_FIXMAILCOLLECTOR_VERSION,
      'author'         => 'Edgard Lorraine Messias',
      'homepage'       => 'https://github.com/edgardmessias/glpi-fixmailcollector',
      'license'        => 'GPL v2+',
      'minGlpiVersion' => '9.5',
      'requirements'   => [
         'glpi' => [
            'min' => '9.5',
            'max' => '9.5.4',
         ]
      ]
   ];
}

// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_fixmailcollector_check_prerequisites() {
   if (version_compare(GLPI_VERSION, '9.5', 'lt')) {
      echo "This plugin requires GLPI >= 9.5";
      return false;
   } else {
      return true;
   }
}

function plugin_fixmailcollector_check_config() {
   return true;
}
