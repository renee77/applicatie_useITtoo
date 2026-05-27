<?php

function __($key): string
{
    global $text;

  // Hier worden de key opgesplitst via de punten, zodat
  // webshop.add vertaal naar ['webshop']['add']
    $keys = explode('.', $key);
    $value = $text;

    foreach ($keys as $k) {
      // Kijk of de value K terugkomst in onze lijst
        if (!isset($value[$k])) {
          // Zo nee, return de key.
            return $key;
        }
        $value = $value[$k];
    }

    return $value;
}
