<?php

require_once('workflows.php');
require_once('simple_html_dom.php');

$wf = new Workflows();
// get query
$query = $argv[1];

//define URL
$url = "http://codic.jp/search?q=".urlencode($query);
$html = file_get_html( $url );


//English or Japanese
if ( ctype_alnum ( $query )) {
    $int = 0;
    foreach($html->find('.translated') as $list) {
        $word =  $list->plaintext;
        $part =  $html->find('.word-class')[$int]->plaintext;
        $desc =  $html->find('.description')[$int]->innertext;
        $desc =  preg_replace( '/<br(\s+\/)?>/i',' ',$desc);
       $like =   $html->find('.likes')[$int]->plaintext;
        $wf->result(time(), "$word", "$word", "", 'icon.png');
        $int ++;
    }    
} else {
    $count = 0;
    foreach($html->find('.translated') as $list) {
        $word =  $list->plaintext;
       // $part =  $html->find('.word-class')[$count]->innnertext;
        $desc =  $html->find('.description')[$count]->innertext;
        $desc =  preg_replace( '/<br(\s+\/)?>/i','',$desc);
        $desc = preg_replace('/\s+/', ' ', $desc);
        $like =   $html->find('.likes')[$count]->plaintext;
        $wf->result(time(), "$word", "$word"." ".$part, "♡"."$like".": "."$desc", 'icon.png');
        $count ++;
    }   
}

if($int == 0 && $count == 0)
    $wf->result(time(), "No Result", "no result", "no results", 'icon.png');


echo $wf->toxml();

?>