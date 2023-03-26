<?php
  $fileContent = file_get_contents("berichten.json");

  $berichten = json_decode($fileContent, true);

  // Controleer of de cookie al is ingesteld
  if(isset($_COOKIE['form_submitted'])) {
    $formSubmitted = true;
  } else {
    $formSubmitted = false;
  }

  if(isset($_POST["submit"]) && !$formSubmitted){
    $date = new DateTimeImmutable();

    $berichten += [$_POST["name"] => array($_POST["message"], $date->format('d-m-y h:m'))];

    $json = json_encode($berichten, JSON_PRETTY_PRINT);
    $fp = fopen("berichten.json", 'w');
    fwrite($fp, $json);
    fclose($fp);

    // Stel de cookie in om te voorkomen dat het formulier opnieuw wordt ingediend
    setcookie('form_submitted', true, time() + 60 * 60 * 24);
  }
?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gastenboek</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
  <h1>Gastenboek</h1>
  <div id="gastenboek">
    <h2>Laat jouw bericht achter</h2>
    <form method="post">
      <label for="name">Naam:</label>
      <input type="text" name="name" required>
      <label for="message">Bericht:</label>
      <textarea name="message" required></textarea>
      <button type="submit" name="submit">Verzenden</button>
    </form>
  </div>
  <h2>Berichten</h2>

  <div class="berichten">

      <?php 
        foreach(array_reverse($berichten) as $key => $value) {
          echo "<div class=\"bericht\">";
          echo "<p class=\"user\">" . htmlspecialchars($key) . "</p>";
          echo "<p>" . htmlspecialchars($value[0]) . "</p>";

          echo "<div class=\"datum\">";
          echo "<p class=\"date\">" . htmlspecialchars($value[1]) . "</p>";
          echo "</div>";

          echo "</div>";
        }
      ?>
    </div>
</body>
</html>
