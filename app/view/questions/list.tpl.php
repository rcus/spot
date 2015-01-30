<?php
// Get questions as HTML in a string
$strQuestions = "";
if (empty($questions)) {
    $strQuestions = "Inga frågor hittades med denna tagg.";
}
else {
    foreach ($questions as $question) {
        // Prepare tags, no of answers and comments
        $strTags = "";
        foreach ($question['t'] as $id => $tag) {
            $strTags .= "<div class='tag'>".
                "<a href='{$this->url->create('questions/tags/'.$id)}'>{$tag}</a>".
                "</div>";
        }
        $statistics = "<i class='fa fa-exclamation'></i> ". (isset($question['A']) ? $question['A'] : "0");
        $statistics .= "<i class='fa fa-comment'></i> ". (isset($question['C']) ? $question['C'] : "0");

        $strQuestions .= "<div class='question'>".
            "<h3><a href='{$this->url->create('questions/view/'.$question['id'])}'>{$question['title']}</a></h3>".
            "<p class='date'>{$question['created']}</p>".
            "<p class='tags'>$strTags</p>".
            "<p class='name'><a href='{$this->url->create('users/view/'.$question['acronym'])}' class='userlink'>".
            "<img src='http://www.gravatar.com/avatar/{$question['hash']}?d=identicon&s=36' class='gravatar'> {$question['name']}</a> ".
            "<a href='{$this->url->create('questions/view/'.$question['id'])}'>$statistics</a></p>".
            "</div>";
    }
}
?>

<h1><?=$title?></h1>

<div id='questions'>
   <?=$strQuestions?>
</div>

<div id='sidebar'>
    <h2>Undrar du något?</h2>
    <div><a href='<?=$this->url->create('questions/add')?>'>Ställ en fråga</a></div>
</div>