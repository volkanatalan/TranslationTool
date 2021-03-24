<?php

echo "
<meta charset=\"utf-8\">
<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css\" integrity=\"sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm\" crossorigin=\"anonymous\">
";

$langsPath = './langs/';
$langFiles = array_slice(scandir($langsPath), 2);
// echo json_encode($langFiles);

if (count($langFiles) > 0) {
  $firstFileContent = json_decode(file_get_contents($langsPath . $langFiles[0]), true);
  // $categories = array_keys($firstFileContent);
  // echo json_encode($categories );
  // echo gettype($firstFileContent['general']);
  // var_dump($firstFileContent['shortMonths']);

  foreach ($firstFileContent as $categoryKey => $category) {
    echo "<h3 class='categoryTitle'>$categoryKey</h3>";

    $categoryTranslations = $firstFileContent[$categoryKey];
    // $categoryTranslationsKeys = array_keys($categoryTranslations);

    $lineCount = 0;
    foreach ($categoryTranslations as $translationKey => $translation) {
      $backgroundColor = $lineCount % 2 === 0 ? '#e8f4ff' : '#cadced';
      echo "
        <div
          class='translationRow'
          style=\"background-color: $backgroundColor; \" 
          onClick=\"onPressTranslation('$categoryKey.$translationKey')\"> 
          <div style=\"color: gray\">$translationKey</div> 
          <div> $translation</div>
        </div>
      ";

      $lineCount++;
    }
    echo '<br/>';
  }
}

echo "

  <!-- Modal -->
  <div class=\"modal fade\" id=\"exampleModal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
  <div class=\"modal-dialog\" role=\"document\">
    <div class=\"modal-content\">
      <div class=\"modal-header\">
        <h5 class=\"modal-title\" id=\"exampleModalLabel\">Modal title</h5>
        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
          <span aria-hidden=\"true\">&times;</span>
        </button>
      </div>
      <div class=\"modal-body\">
      </div>
      <div class=\"modal-footer\">
        <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Close</button>
        <button type=\"button\" class=\"btn btn-primary\">Save changes</button>
      </div>
    </div>
  </div>
</div>

  
  <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js\"></script>
  <script src=\"https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js\" integrity=\"sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q\" crossorigin=\"anonymous\"></script>
  <script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js\" integrity=\"sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl\" crossorigin=\"anonymous\"></script>

  <script>
    function onPressTranslation(key){
      console.log(key)

      $.post(\"./service.php\", {q: 'getAllTranslationsByKey', key}, function(data){
        data = JSON.parse(data)
        console.log(data)
        $(\".modal-body\").html(generateModalHtml(data))
        $(\"#exampleModal\").modal()
      })
    }

    function generateModalHtml(data){
      let html = ''
      
      data.forEach(function(language, index){
        html += `
        <div class=\"input-group mb-3\">
          <div class=\"input-group-prepend\">
            <span class=\"input-group-text\" id=\"basic-addon1\">` + language.lang + `</span>
          </div>
          <input type=\"text\" class=\"form-control\" aria-describedby=\"basic-addon1\" value=\"` + language.translation + `\">
        </div>
        `
      })

      return html
    }
  </script>

  <style>
  html{
    
  }

  .categoryTitle{
    padding: 0px 20px 0px 20px;
  }

  .translationRow{
    padding: 5px 20px 5px 20px;
    cursor: pointer;
  }
  </style>
";
