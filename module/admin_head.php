<?php
/*
 * -*- Encoding: utf8n -*-
 */

function feeeeed_tiny_mce_before_init($init) {
    $init['plugins'] = str_replace(
        array('wpfullscreen',',,'),
        array('', ','),
        $init['plugins']
        );
    return $init;
}

add_filter('tiny_mce_before_init', 'feeeeed_tiny_mce_before_init', 999);

