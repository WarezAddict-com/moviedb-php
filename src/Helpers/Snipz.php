<?php

// Namespace
namespace Turbo\Helpers;

/**
 * Snipz
 */
class Snipz
{

    public function sourceCode($url)
    {
        $lines = file($url);

        foreach ($lines as $line_num => $line) {
            echo "<p>" . htmlspecialchars($line) . "</p>";
        }
    }

    public function nonAscii($output)
    {
        $output = preg_replace('/[^(x20-x7F)]*/','', $output);
        return $output;
    }

    public function gravatar($email, $size = 64)
    {
        // Validate Email
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $url = "https://www.gravatar.com/avatar/" . md5($email) . "?d=mp&r=x";
        }

        if (filter_var($size, FILTER_VALIDATE_INT) && $size != 64) {
            $url .= "&s=" . $size;
        } else {
            $url .= "&s=64";
        }

        $code = '<img class="img-fluid" src="' . $url . '" alt="Gravatar">';

        return $code;
    }

}
