<?php

$langsPath = './langs/';

if ($_POST['q']) {
  $q = $_POST['q'];

  if ($q == 'getAllTranslationsByKey') {
    echo json_encode(getAllTranslationsByKey($_POST['key']));
  }
  else if ($q == 'updateTranslations') {
    updateTranslations();
  }
}



function updateTranslations(){
  global $langsPath;

  $key = $_POST['key'];
  $translations = $_POST['translations'];
  
  foreach ($translations as $translation) {
    $filePath = $langsPath . $translation['file'];
    $fileContent = json_decode(file_get_contents($filePath), true);
    $keyParts = explode('.', $key);
    $keyPartsLength = count($keyParts);

    if($keyPartsLength === 1){
      $fileContent[$keyParts[0]] = $translation['value'];
    }
    else if($keyPartsLength === 2){
      $fileContent[$keyParts[0]][$keyParts[1]] = $translation['value'];
    }

    echo '$keyParts:' . json_encode($fileContent[$keyParts[0]][$keyParts[1]]) . '----';
    
    file_put_contents($filePath, json_encode($fileContent));
  }

  echo json_encode([
    "code" => 200
  ]);
}


function getAllTranslationsByKey($key) {
  global $langsPath;
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
