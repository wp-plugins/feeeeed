<?php
/*
 * Setting Screen
 * call: execute_admin($this);
 * -*- Encoding: utf8n -*-
 */
function execute_admin(&$obj) {
    //require_once('common.php');
    
    //print_r($_REQUEST);
    //print_r($_POST);
    //print_r($obj->model);
    //echo phpinfo();

    if (is_array($_REQUEST)) {
        // Array extract to variable
        extract($_REQUEST);
    }

    ?>

    <div class="wrap cfshoppingcart_admin">
    <div id="icon-plugins" class="icon32"><br/></div>
    <h2><?php _e('Feeeeed', 'feeeeed'); ?></h2>
      
    <form name="formF5d" method="post">
    <div id="poststuff" class="meta-box-sortables" style="position: relative; margin-top:10px;">
      <input type="hidden" name="action" value="" />
      <input type="hidden" name="args" value="" />
        
    <?php
    if ($action === 'save') {
        save($obj);
        echoMenuList($obj, __('Saved','feeeeed'));
    } else {
        echoMenuList($obj);
    }
    
    echo '</div>';
    echo '</form>';
    echo '</div>';
}

function checkInput($str, $type) {
    if ($type === 'class') {
        if (preg_match('/^[0-9\-]|[_\-]$|[^a-zA-Z0-9_\-]/', $str)) return false;
    } else if ($type === 'remove_tag') {
        $str = str_replace('>', '&gt;', $str);
        $str = str_replace('<', '&lt;', $str);
    } else if ($type === 'remove_tag_item') {
        $str = str_replace('>', '&gt;', $str);
        $str = str_replace('<', '&lt;', $str);
        $str = str_replace('&lt;rs&gt;', '<rs>', $str);
        $str = str_replace('&lt;fs&gt;', '<fs>', $str);
    }
    return $str;
}

function save(&$obj) {
    //print_r($_REQUEST);
    if (is_array($_REQUEST)) {
        // Array extract to variable
        extract($_REQUEST);
    }

    $model = &$obj->model;

    $model->f5d_radio = $f5d_radio;
    $model->text_jump_url = $text_jump_url;
    $model->text_message = $text_message;
    $model->text_date_format = $text_date_format;

    $model->auto_move = $f5d_auto_move;
    $model->auto_move_sec = $f5d_auto_move_sec;
    $model->auto_move_url = $f5d_auto_move_url;

    $obj->updateWpOption($model); // Save database-model
}

function echoMenuList(&$obj, $msg = '') {
    // plugin
    $model = & $obj->model;
    $radio = $model->f5d_radio;
    
    if ($msg) {
        echo '<div id="message" class="updated"><p>' . $msg . '</p></div>';
    }
    ?>
      
<div class="postbox feeeeed_postbox">
  <h3>Options</h3>
  <div class="inside">
    
    <fieldset>
      <legend><input type="radio" name="f5d_radio" value="html" <?php if ($radio === 'html') { echo 'checked'; }?> /><?php _e('Put feed formatted Html','feeeeed');?></legend>
      <?php _e('Date format','feeeeed');?> <input type="text" name="text_date_format" size="60" value="<?php echo $model->text_date_format; ?>" />
      <p><?php _e('ex: "D, d M Y H:i:s", ex: "Y.m.d"', 'feeeeed'); ?></p>
    </fieldset>
      
    <fieldset>
      <legend><input type="radio" name="f5d_radio" value="jump" <?php if ($radio === 'jump') { echo 'checked'; }?> /><?php _e('Jump Homepage','feeeeed');?></legend>
      <?php _e('Jump to','feeeeed');?> <input type="text" name="text_jump_url" size="60" value="<?php echo $model->text_jump_url; ?>" />
    </fieldset>
      
    <fieldset>
      <legend><input type="radio" name="f5d_radio" value="msg" <?php if ($radio === 'msg') { echo 'checked'; }?> /><?php _e('Put message','feeeeed');?></legend>
      <?php/*<textarea name="text_message" cols="60" rows="10"><?php echo $model->text_message; ?></textarea>*/?>

      <?php
      /*
      wp_tiny_mce(false);
      add_filter('teeny_mce_buttons', 'teeny_mce_buttons');
      wp_enqueue_script('page');
      if (user_can_richedit()) {
          wp_enqueue_script('thickbox');
          add_action( 'admin_head', 'wp_tiny_mce' );
          wp_enqueue_script('editor');
          add_thickbox();
          wp_enqueue_script('media-upload');
          wp_enqueue_script('word-count');
      }
      */
     //the_editor(stripslashes($model->text_message), 'text_message');

        /*
     echo '<div id="postrichdiv" class="postarea">';
     if (function_exists('wp_tiny_mce')) wp_tiny_mce();
     the_editor(stripslashes($model->text_message), 'text_message');
     echo '</div>';
          */
        /*
    if ( current_user_can('edit_posts') ) {
        echo '<div id="postrichdiv" class="postarea">';
        the_editor('');
        echo '</div>';
    }
          */
        
        /*the_editor($content_to_load);*/
     the_editor(stripslashes($model->text_message), 'text_message');
    
    
     ?>
       
      <input type="checkbox" name="f5d_auto_move" value="checked" <?php echo $model->auto_move; ?> /><?php _e('After seconds to move URL','feeeeed');?>
      <br /><?php _e('Wait time','feeeee');?> <input type="text" name="f5d_auto_move_sec" value="<?php echo $model->auto_move_sec; ?>" /> <?php _e('seconds','feeeeed');?>
      <br /><?php _e('URL','feeeeed');?> <input type="text" name="f5d_auto_move_url" value="<?php echo $model->auto_move_url; ?>" size="60" />
    </fieldset>

    <fieldset>
      <input type="submit" name="submit_save" value="Save" onClick="F5dJs.do_submit('save');" />
    </fieldset>
      
  </div>
</div>
  <?php
}
?>
