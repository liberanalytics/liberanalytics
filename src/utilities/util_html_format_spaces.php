<?php

    // fix_code_spans.php
    //
    // Usage (example):
    //   php fix_code_spans.php phpcloudwatchlogs.php > phpcloudwatchlogs_fixed.php
    //
    // Then inspect phpcloudwatchlogs_fixed.php and, if happy, replace the original.

    $inputFile = $argv[1];
    $html = file_get_contents($inputFile);

    if ($html === false) {
        fwrite(STDERR, "Could not read file: {$inputFile}\n");
        exit(1);
    }

    // Regex will target ALL spans whose class contains "code-php-"
    $pattern = '/(<span\s+class="([^"]*code-php-[^"]*)"\s*>)(.*?)(<\/span>)/s';

    $html = preg_replace_callback($pattern, function ($m) {
        $fullOpen   = $m[1]; // <span class="...">
        $classAttr  = $m[2]; // class value
        $inner      = $m[3]; // inner HTML/text
        $close      = $m[4]; // </span>

        // If this is a comment or string span, leave spaces alone
        if (strpos($classAttr, 'code-php-comment-fc') !== false ||
            strpos($classAttr, 'code-php-string-fc')  !== false) {
            return $fullOpen . $inner . $close;
        }

        // For all other code-php-* spans, replace literal spaces with &nbsp;
        // (Only space characters, not newlines)
        $inner = str_replace(' ', '&nbsp;', $inner);

        return $fullOpen . $inner . $close;
    }, $html);

    echo $html;

?>