<?php

/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks {

   protected $name = "fixmailcollector";

   function build() {

      $tmpPath = $this->_tmpDir();

      $exclude = glob(__DIR__ . '/.*');
      $exclude[] = 'plugin.xml';
      $exclude[] = 'vendor';
      $exclude[] = "$this->name.zip";
      $exclude[] = "$this->name.tgz";

      $this->taskCopyDir([__DIR__ => $tmpPath])
         ->exclude($exclude)
         ->run();

      $composer_file = "$tmpPath/composer.json";
      if (file_exists($composer_file)) {
         $hasDep = false;
         try {
            $data = json_decode(file_get_contents($composer_file), true);
            $hasDep = isset($data['require']) && count($data['require']) > 0;
         } catch (\Exception $ex) {
            $hasDep = true;
         }

         if ($hasDep) {
            $this->taskComposerInstall()
               ->workingDir($tmpPath)
               ->noDev()
               ->run();
         }
      }

      $this->_remove("$tmpPath/composer.lock");

      // Pack
      $this->_remove(["$this->name.zip", "$this->name.tgz"]);

      $this->taskPack("$this->name.zip")
         ->addDir('fixmailcollector', $tmpPath)
         ->run();

      $this->taskPack("$this->name.tgz")
         ->addDir('fixmailcollector', $tmpPath)
         ->run();
   }
}
