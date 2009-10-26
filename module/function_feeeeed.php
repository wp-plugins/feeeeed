<?php
/*
 * function_feeeeed.php
 * -*- Encoding: utf8n -*-
 */
function feeeeed() {
    // get data object
    $wpFeeeeed = & new WpFeeeeed();
    $model = $wpFeeeeed->model;
    $radio = $model->f5d_radio;

    if ($radio === 'html') {
        require_once('feed2html.php');
        feed2html($model);
    } else if ($radio === 'jump') {
        header('Location: ' . $model->text_jump_url);
        exit;
    } else if ($radio === 'msg') {
        header("Content-type: text/html; charset=utf-8");
        //$url = get_bloginfo('siteurl');
        //$a = sprintf("<a href=\"%s\">%s</a>", $url, $url);
        $msg = stripslashes($model->text_message);
        //$msg = str_replace('_homepage_', $a, $msg);
        $msg = str_replace("\n", '<br />', $msg);
        echo $msg;
    }
}
?>
