<?php

if ($_POST['q']) {
  $q = $_POST['q'];

  if ($q == 'getAllTranslationsByKey') {
    echo json_encode(getAllTranslationsByKey($_POST['key']));
  }
}


function getAllTranslationsByKey($key) {
  $langsPath = './langs/';
  $langFiles = array_slice(scandir($langsPath), 2);
  $result = [];

  foreach ($langFiles as $file) {
    $fileContent = json_decode(file_get_contents($langsPath . $file), true);
    $keyParts = explode('.', $key);

    foreach ($keyParts as $part) {
      $fileContent = $fileContent[$part];
    }

    array_push($result, [
      "lang" => explode('.', $file)[0],
      "translation" => $fileContent
    ]);
  }

  return $result;
}
