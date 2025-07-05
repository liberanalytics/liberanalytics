<?php
    namespace enums;

    enum CrudOperation: string
    {
        CASE GETFXPARAMETERS = 'getfxparameters';
        CASE GETPOSTS = 'getposts';
        CASE GETPOSTCATEGORIES = 'getpostcategories';
        CASE GETPOSTTAGSLIKE = 'getposttagslike';
        CASE CREATECONTACT = 'createcontact';
    }
?>