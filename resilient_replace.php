<?php
// #!/usr/bin/php
function resilient_replace ($search, $replace, $subject) {
    $search_escaped = str_replace("\\", "\\\\\\\\\\\\\\\\\\\\", $search);
    $replace_escaped = str_replace("'", "\\'", $replace);
    $delta = strlen($replace) - strlen($search);
    // $nb_matches = preg_match
    $str = preg_replace(
        '/s:(\d+):(\\\?)"(.*?)\\2";/e',
        "'s:' 
        . (intval('\\1', 10) + preg_match_all('/" . $search_escaped . "/', '\\3', \$dummy) * (" . $delta . ")) 
        . ':\\2\"' 
        . preg_replace('/" . $search_escaped . "/', '" . $replace_escaped . "', '\\3') . '\\2\";'",
        $subject);
    return $str;
}
// $handle = fopen('php://stdin', 'r');
$handle = fopen('source.txt', 'r');
$buffer = '';
while(!feof($handle)) {
    $buffer .= fgets($handle);
}
fclose($handle);
echo '<pre>';
// echo $buffer;
echo resilient_replace('www.mondomaine.fr', "mon.autredomaine.fr/mondomaine", $buffer);
echo '</pre>';

?>
