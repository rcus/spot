<?php
// Get tags as HTML in a string
$strTags = "";
foreach ($tags as $id => $tag) {
    $strTags .= "<div class='tag'>".
        "<a href='{$this->url->create('questions/tags/'.$id)}'>{$tag}</a>".
        "</div>";
}
?>

<h1><?=$title?></h1>

<div id='tags'>
   <?=$strTags?>
</div>