/*
 * f5d.js
 * -*- Encoding: utf8n -*-
 *
 * Don't named 'class' and 'start' to variable at IE6.
 * Be an error when assign an object to not declared variable, Don't foget var.
 * Javascript Online Lint:
 *   http://www.javascriptlint.com/online_lint.php
 */

// namespace
var F5dJs;
if (typeof F5dJs === 'undefined' || !F5dJs) {
    F5dJs = {};
}

F5dJs.inputCheck = function () {
  var msg = '';
  f = document.formF5d;
  if (F5dJs.strcmp(f.action.value, 'addnew') === 0) {
    if (f.menuName.value.length === 0) {
      msg += "<?php _e('Input new menu name.', 'f5d'); ?>";
    }
  }

  return msg;
};

F5dJs.strcmp = function (str1, str2) {
    var ct;
    var cmp;

    if((cmp = str1.length - str2.length) !== 0){
        return cmp;
    }

    for(ct = 0; ct < str1.length; ct++) {
        var c1 = str1.charCodeAt(ct);
        var c2 = str2.charCodeAt(ct);
        if((cmp = (c1 - c2)) !== 0){
            break;
        }
    }
    return cmp;
};

F5dJs.do_submit = function (str) {
    var f = document.formF5d;
    f.action.value = str;
    msg = F5dJs.inputCheck();
    if (msg.length > 0) {
      alert(msg);
      return;
    }
    f.submit();
};

