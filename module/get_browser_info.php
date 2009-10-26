<?php
//get_browser_info();
function get_browser_info() {
    $bw = array('Opera', 'Chrome', 'Netscape', 'Firefox', 'MSIE');
    
    $agent = getenv('HTTP_USER_AGENT');
    
    $f = split('[/ \t\)\(;]+', $agent);
    //print_r($f);

    foreach ($bw as $i => $name) {
        foreach ($f as $j => $v) {
            if ($name === $v) {
                //print '<p>[' . $name .'/' .$f[$j + 1] . ']</p>';
                $r['name'] = $name;
                $r['version'] = $f[$j + 1];
                return $r;
            }
        }
    }
    //print 'Unknown';
    $r['name'] = 'Unknown';
    $r['version'] = 0;
    return $r;
}
?>
