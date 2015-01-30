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

<div id='sidebar'>
    <h2>Undrar du något?</h2>
    <div><a href='<?=$this->url->create('questions/add')?>'>Ställ en fråga</a></div>
</div>