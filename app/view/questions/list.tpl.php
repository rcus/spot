<?php
// Get questions as HTML in a string
$strQuestions = "";
foreach ($questions as $question) {
    // Prepare tags, no of answers and comments
    $strTags = "";
    foreach ($question['t'] as $id => $tag) {
        $strTags .= "<div class='tag'>".
            "<a href='{$this->url->create('questions/tags/'.$id)}'>{$tag}</a>".
            "</div>";
    }
    $statistics = (isset($question['A'])) ? "{$question['A']} svar | " : "Inga svar | ";
    $statistics .= (isset($question['C'])) ? (($question['C'] == 1) ? "1 kommentar" : "{$question['C']} kommentarer") : "Inga kommentarer";

    $strQuestions .= "<div class='question'>".
        "<h3><a href='{$this->url->create('questions/view/'.$question['id'])}'>{$question['title']}</a></h3>".
        "<p class='date'>{$question['created']}</p>".
        "<p class='tags'>$strTags</p>".
        "<p class='name'><a href='{$this->url->create('users/view/'.$question['acronym'])}' class='userlink'>".
        "<img src='http://www.gravatar.com/avatar/{$question['hash']}?d=identicon&s=36' class='gravatar'> {$question['name']}</a> ".
        "| <a href='{$this->url->create('questions/view/'.$question['id'])}'>$statistics</a></p>".
        "</div>";
}
?>

<h1><?=$title?></h1>

<div id='questions'>
   <?=$strQuestions?>
</div>