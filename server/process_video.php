<?php
  // common.php houses common setup and environment setup
  require("common.php");

  if (isset($_POST["guid"])) {
    $multi["guid"] = $_POST["guid"];
    $multi["title"] = "PHP Upload example";

    $video = VzaarApi\Video::create($multi);

    echo(json_encode([
      "id" => $video->id
    ]));
  }
?>
