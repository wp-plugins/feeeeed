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

   <div id="poststuff">
     
   <h2>Feeeeed </h2>
   <p><?php _e('Rss-feed not support browser (Chrome and older than IE6) is reading rss-feed then ..... :','feeeeed');?></p>
    <form name="formF5d" method="post">
      <input type="hidden" name="action" value="" />
      <input type="hidden" name="args" value="" />
        
    <?php
    if ($action === 'save') {
        save($obj);
        echoMenuList($obj);
    } else {
        echoMenuList($obj);
    }
    
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

    $obj->updateWpOption($model); // Save database-model
}

function echoMenuList(&$obj, $msg = '') {
    if ($msg) {
        printf("<div class=\"msg\">%s</div>", $msg);
    }
    // plugin
    $model = & $obj->model;
    $radio = $model->f5d_radio;
    
    if ($msg) {
        echo '<fieldset><legend><strong><font color="red">Error message</font></strong></legend>' . $msg . '</fieldset>';
    }
    ?>
      
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
     the_editor(stripslashes($model->text_message), 'text_message');
     ?>
    </fieldset>

    <fieldset>
      <legend><?php _e('Operation','feeeeed');?></legend>
      <input type="submit" name="submit_save" value="Save" onClick="F5dJs.do_submit('save');" />
    </fieldset>
  <?php
}
?>
