<?php

/**
 * @see https://github.com/glensc/php-filename-normalizer
 */

namespace glen\FilenameNormalizer;

use Normalizer as IntlNormalizer;

class Normalizer {
   /**
    * Make filename safe:
    * - Unicode normalize NFC
    * - Replace unsafe characters to be portable
    *
    * @see http://windows.microsoft.com/en-us/windows/file-names-extensions-faq File names and file name extensions: frequently asked questions (Windows)
    * @see https://support.apple.com/en-us/HT202808 OS X: Cross-platform filename best practices and conventions (OSX)
    * @param string $name
    * @param string $replacement
    * @return string
    */
   public static function normalize($name, $replacement = '-') {
      // Unicode NFC
      // https://en.wikipedia.org/wiki/Unicode_equivalence#Normal_forms
      $name = IntlNormalizer::normalize($name, IntlNormalizer::FORM_C);

      // OS Safe characters
      $name = str_replace(['\\', '/', '?', ':', '*', '"', '>', '<', '|'], $replacement, $name);

      // strip control chars, backspace and delete (including \r)
      $name = preg_replace('/[\x00-\x08\x0b-\x1f\x7f]/', '', $name);

      return $name;
   }
}
