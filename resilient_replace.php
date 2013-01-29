#!/usr/bin/php
<?php
ini_set('memory_limit', -1);
ini_set('max_execution_time', 0);
if (empty($argv[1]) || empty($argv[2])) {
    file_put_contents('php://stderr', '
Usage: resilient_replace.php <search> <replace> [<file>]

Options:
    -i
        edit in place

    --only-into-serialized
        replace only into serialized data (do not replace into raw data)

        ');
    die();
}

// extrait les options
$options = array();
foreach ($argv as $key => $arg) {
    if (strpos($arg, '-') === 0) {
        $options[$arg] = 1;
        unset($argv[$key]);
    }
}
$argv = array_values($argv);

// recupere les parametres
$search  = $argv[1];
$replace = $argv[2];
$content_from_stdin = 0;
$filename = '';
if (empty($argv[3])) {
    $content_from_stdin = 1;
} else {
    $filename = $argv[3];
}

/*print_r($options);*/
/*echo 'search: ' . $search . PHP_EOL;*/
/*echo 'replace: ' . $replace . PHP_EOL;*/

function resilient_replace ($search, $replace, $subject, $only_into_serialized = false) {
    $str = $subject;
    $search_escaped = str_replace("\\", "\\\\\\\\\\\\\\\\\\\\", $search);
    $replace_escaped = str_replace("'", "\\'", $replace);

    //$debug_str = "
    //$search => $search_escaped
    //$replace => $replace_escaped";
    //file_put_contents('php://stderr', $debug_str . PHP_EOL);

    $delta = strlen($replace) - strlen($search);
    // $nb_matches = preg_match
    file_put_contents('php://stderr', 'replace into serialized' . PHP_EOL);
    $str = preg_replace(
        '/s:(\d+):(\\\?)"(.*?)\\2";/e',
        "'s:' 
        . (intval('\\1', 10) + preg_match_all('/" . $search_escaped . "/', '\\3', \$dummy) * (" . $delta . ")) 
        . ':\\2\"' 
        . preg_replace('/" . $search_escaped . "/', '" . $replace_escaped . "', '\\3') . '\\2\";'",
        $str);
    if (!$only_into_serialized) {
        file_put_contents('php://stderr', 'replace into raw' . PHP_EOL);
        $str = preg_replace('/' . $search . '/', $replace, $str);
    }
    return $str;
}
if ($content_from_stdin) {
    $handle = fopen('php://stdin', 'r');
} else {
    $handle = fopen($filename, 'r');
}
$buffer = '';
while(!feof($handle)) {
    $buffer .= fgets($handle);
}
fclose($handle);
$only_into_serialized = 0;
if (!empty($options['--only-into-serialized'])) {
    $only_into_serialized = 1;
}
$buffer = resilient_replace($search, $replace, $buffer, $only_into_serialized);
if (!empty($options['-i'])) {
    file_put_contents($filename, $buffer);
} else {
    echo $buffer;
}

?>
