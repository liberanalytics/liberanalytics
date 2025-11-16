<?php

    // Make sure at least 1 argument was passed.
    if ($argc < 2) {
        fwrite(STDERR, "Please provide a filename to parse.\n");
        exit(1);
    }

    // Make sure the input file arg is an actual file on the fileystem and is
    // of file extension type php or html.
    $inputFile = $argv[1];
    if ( (!(is_file($inputFile ))) || (!preg_match('/\.(php|html)$/', $inputFile)) ) {
        fwrite(STDERR, "Please provide a valid filename.\n");
        exit(1);
    }

    // Get the file contents.
    if ( $fileContent = file_get_contents($inputFile) ) {
        $regex = '/(<span\s+class="([^"]*code-php-[^"]*)"\s*>)(.*?)(<\/span>)/s';
    }
    else {
        fwrite(STDERR, "Can't read file content for file {$inputFile}.\n");
        exit(1); 
    }

    // Get the
    $html = preg_replace_callback($regex, function ($ln) {
        
        $lnOpenTag = $ln[1];
        $lnClass = $ln[2];
        $lnText = $ln[3];
        $lnCloseTag = $ln[4];

    }, $html);



?>