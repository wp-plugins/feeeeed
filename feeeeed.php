<?php
/*
Plugin Name: feeeeed
Plugin URI: http://takeai.silverpigeon.jp/
Description: Feeeeed is a plugin that is Measures against browser that is not supports feed.
Author: AI.Takeuchi
Version: 0.9.0
Author URI: http://takeai.silverpigeon.jp/
*/

// -*- Encoding: utf8n -*-
// If you notice a my mistake(Program, English...), Please tell me.

/*  Copyright 2009 AI Takeuchi (email: takeai@silverpigeon.jp)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

load_plugin_textdomain('feeeeed', 'wp-content/plugins/feeeeed/lang', 'feeeeed/lang');

 
function myplugin_tinymce() {
    wp_admin_css('thickbox');
    wp_print_scripts('jquery-ui-core');
    wp_print_scripts('jquery-ui-tabs');
    wp_print_scripts('post');
    wp_print_scripts('editor');
    add_thickbox();
    wp_print_scripts('media-upload');
    if (function_exists('wp_tiny_mce')) { wp_tiny_mce(); }
}

if (is_admin()) {
    $wpFeeeeed = & new WpFeeeeed();
    // Registration of management screen header output function.
    add_action('admin_head', array(&$wpFeeeeed, 'addAdminHead'));
    // Registration of management screen function.
    add_action('admin_menu', array(&$wpFeeeeed, 'addAdminMenu'));
    // ritch text editor
    //add_filter('admin_head','ShowTinyMCE');
    //add_filter('admin_head','ugc_content');
    add_filter('admin_head','myplugin_tinymce');
} else {
    require_once('module/get_browser_info.php');
    $bw = get_browser_info();
    //echo '<p>' . $bw['name'] . $bw['version'] . '</p>';

    if (($bw['name'] === 'MSIE' && $bw['version'] < 7) ||
        ($bw['name'] === 'Chrome')) {
        //echo 'feeeeed';
        require_once('module/function_feeeeed.php');
        remove_filter('do_feed_rdf', 'do_feed_rdf', 10);
        remove_filter('do_feed_rss', 'do_feed_rss', 10);
        remove_filter('do_feed_rss2', 'do_feed_rss2', 10);
        remove_filter('do_feed_atom', 'do_feed_atom', 10);
        add_action('do_feed_rdf', 'feeeeed', 10, 1);
        add_action('do_feed_rss', 'feeeeed', 10, 1);
        add_action('do_feed_rss2', 'feeeeed', 10, 1);
        add_action('do_feed_atom', 'feeeeed', 10, 1);
    }
}

/* Data model */
class WpFeeeeedModel {
    // member variable
    var $version = '0.1';
    var $f5d_radio = 'html';
    var $text_jump_url = '';
    var $text_message = '';
    var $text_date_format = 'D, d M Y H:i:s';
    var $auto_move = '';
    var $auto_move_sec = 10; 
    var $auto_move_url = '';
    
    // constructor
    function WpFeeeeedModel() {
        // default value
        //$this->backup_folder = $cwd;
        $this->text_message = __("What is your browser can not read the feed.",'feeeeed');
        $this->text_date_format = __('Y.m.d', 'feeeeed');
    }
}

/* main class */
class WpFeeeeed {
    var $view;
    var $model;
    var $request;
    var $plugin_name;
    var $plugin_uri;

    // constructor
    function WpFeeeeed() {
        $this->plugin_name = 'feeeeed';
        
        $this->plugin_uri  = get_settings('siteurl');
        $this->plugin_uri .= '/wp-content/plugins/feeeeed/';

        $this->model = $this->getModelObject();
    }
    
    // create model object
    function getModelObject() {
        $data_clear = 0; // Debug: 1: Be empty to data
        
        // get option from Wordpress
        $option = $this->getWpOption();
        
        //printf("<p>Debug[%s, %s]</p>", strtolower(get_class($option)), strtolower('WpFeeeeedModel'));
        
        // Restore the model object if it is registered
        if (strtolower(get_class($option)) === strtolower('WpFeeeeedModel') && $data_clear == 0) {
            $model = $option;
        } else {
            // create model instance if it is not registered,
            // register it to Wordpress
            $model = & new WpFeeeeedModel();
            $this->addWpOption($model);
        }
        return $model;
    }
    
    function getWpOption() {
        $option = get_option($this->plugin_name);
        
        if(!$option == false) {
            $OptionValue = $option;
        } else {
            $OptionValue = false;
        }
        return $OptionValue;
    }

    /* be add plug-in data to Wordpresss */
    function addWpOption(&$model) {
        $option_description = $this->plugin_name . " Options";
        $OptionValue = $model;
        //print_r($OptionValue);
        add_option(
            $this->plugin_name,
            $OptionValue,
            $option_description);
    }

    /* update plug-in data */
    function updateWpOption(&$OptionValue) {
        $option_description = $this->plugin_name . " Options";
        $OptionValue = $OptionValue;
        //$OptionValue = $this->model;
        
        update_option(
            $this->plugin_name,
            $OptionValue,
            $option_description);
    }
    
    /*
     * management screen header output
     * reading javascript and css
     */
    function addAdminHead() {
        echo '<link type="text/css" rel="stylesheet" href="';
        echo $this->plugin_uri . 'feeeeed.css" />' . "\n";;

        // ritch text editor
        echo '<style type="text/css" media="screen">';
        echo '#editorcontainer textarea { width: 100%; }';
        echo '</style>';

        echo '<script type="text/javascript">';
        require_once('module/js.php');
        //echo 'window.onload = function() { FeeeeedJs.onLoad(); }';
        echo '</script>';
    }

    function addAdminMenu() {
        add_options_page(
            'Feeeeed Options',
            'Feeeeed',
            8,
            'feeeeed.php',
            array(&$this, 'executeAdmin')
            );
    }

    function executeAdmin() {
        require_once('module/execute_admin.php');
        execute_admin($this);
    }
}

/*
function ugc_content(){
    wp_admin_css('thickbox');
    wp_enqueue_script('post');
    wp_enqueue_script('editor');
    wp_enqueue_script('editor-functions');
    add_thickbox();
    wp_enqueue_script('media-upload');
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-tabs');
    wp_enqueue_script('tiny_mce');
}

// ritch text editor
function ShowTinyMCE() {
    // conditions here
    wp_enqueue_script('common');
    wp_enqueue_script('jquery-color');
    //wp_print_scripts('editor');
    //if (function_exists('add_thickbox')) add_thickbox();
    //wp_print_scripts('media-upload');
    //if (function_exists('wp_tiny_mce')) wp_tiny_mce();
    wp_admin_css();
    wp_enqueue_script('utils');
    //do_action("admin_print_styles-post-php");
    //do_action('admin_print_styles');

    fix_ShowTinyMCE();
}
function fix_ShowTinyMCE(){
    wp_admin_css('thickbox');
    wp_enqueue_script('post');
    wp_enqueue_script('editor');
    wp_enqueue_script('editor-functions');
    add_thickbox();
    wp_enqueue_script('media-upload');
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-tabs');
    wp_enqueue_script('tiny_mce');
 }
  */

?>
