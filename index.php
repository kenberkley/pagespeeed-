<?php
$url = 'https://www.furniture-work.co.uk/';
$key = 'AIzaSyDmBwhttM0rcCUA9jrECp24jMZbVqOIs1o';
// View https://developers.google.com/speed/docs/insights/v1/getting_started#before_starting to get a key
$data = json_decode(file_get_contents("https://www.googleapis.com/pagespeedonline/v1/runPagespeed?url=$url&key=$key"));
$dat = $data->formattedResults->ruleResults;
foreach($dat as $d) {
  $name = $d->localizedRuleName;
  $score = $d->ruleScore;
  print "\nTest: " . $name . "\n";
  print "Score: " . $score . "\n";
  if ($score != 100) {
    if (isset($d->urlBlocks[0]->header->args)) {
      $advice_header = replace_placeholders($d->urlBlocks[0]->header->format, $d->urlBlocks[0]->header->args);
    }
    else {
      $advice_header = $d->urlBlocks[0]->header->format;
    }
    print "Advice: " . $advice_header . "\n";
    foreach ($d->urlBlocks[0]->urls as $url) {
      $advice = replace_placeholders($url->result->format, $url->result->args);
      print $advice . "\n";
    }
  }
};
function replace_placeholders($format, $args) {
  $i = 1;
  foreach ($args as $arg) {
    $format = str_replace("\$" . $i, "$arg->value", $format);
    $i++;
  }
  return $format;
}
