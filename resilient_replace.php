#!/usr/bin/php
<?php
ini_set('memory_limit', -1);
ini_set('max_execution_time', 0);
if (empty($argv[1]) || empty($argv[2])) {
    file_put_contents('php://stderr', 'Usage : resilient_replace.php <search> <replace> [<file>]');
    die();
}

$search  = $argv[1];
$replace = $argv[2];

$content_from_stdin = 0;
if (empty($argv[3])) {
    $content_from_stdin = 1;
}

function resilient_replace ($search, $replace, $subject, $only_into_serialized = true) {
    $search_escaped = str_replace("\\", "\\\\\\\\\\\\\\\\\\\\", $search);
    $replace_escaped = str_replace("'", "\\'", $replace);

    //$debug_str = "
    //$search => $search_escaped
    //$replace => $replace_escaped";
    //file_put_contents('php://stderr', $debug_str . PHP_EOL);

    $delta = strlen($replace) - strlen($search);
    // $nb_matches = preg_match
    $str = preg_replace(
        '/s:(\d+):(\\\?)"(.*?)\\2";/e',
        "'s:' 
        . (intval('\\1', 10) + preg_match_all('/" . $search_escaped . "/', '\\3', \$dummy) * (" . $delta . ")) 
        . ':\\2\"' 
        . preg_replace('/" . $search_escaped . "/', '" . $replace_escaped . "', '\\3') . '\\2\";'",
        $subject);
    if (!$only_into_serialized) {
        $str = preg_replace('/' . $search . '/', $replace, $str);
    }
    return $str;
}
if ($content_from_stdin) {
    $handle = fopen('php://stdin', 'r');
} else {
    $handle = fopen($argv[3], 'r');
}
$buffer = '';
while(!feof($handle)) {
    $buffer .= fgets($handle);
}
fclose($handle);
$buffer = resilient_replace($search, $replace, $buffer);
echo $buffer;

?>
