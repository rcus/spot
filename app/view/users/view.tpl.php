<?php
// Prepare some info
$info = $user->getProperties();
$hash = md5( strtolower( trim( $info['email'] ) ) );

// Questions by user
$strQuestions = (empty($data['q'])) ? "Inga frågor ställda. Ännu..." : "";
foreach ($data['q'] as $question) {
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
        "<p class='date'>{$question['created']} $statistics</p>".
        "<p class='tags'>$strTags</p>".
        "</div>";
}

// Answers by user
$strAnswers = (empty($data['a'])) ? "Har inte svarat på några frågor. Ännu..." : "";
foreach ($data['a'] as $question) {
    // Prepare tags, no of answers and comments
    $strTags = "";
    foreach ($question['t'] as $id => $tag) {
        $strTags .= "<div class='tag'>".
            "<a href='{$this->url->create('questions/tags/'.$id)}'>{$tag}</a>".
            "</div>";
    }
    $statistics = "<i class='fa fa-exclamation'></i> ". (isset($question['A']) ? $question['A'] : "0");
    $statistics .= "<i class='fa fa-comment'></i> ". (isset($question['C']) ? $question['C'] : "0");

    $strAnswers .= "<div class='question'>".
        "<h3><a href='{$this->url->create('questions/view/'.$question['qNo'])}'>{$question['title']}</a></h3>".
        "<p class='date'>{$question['created']} $statistics</p>".
        "<p class='tags'>$strTags</p>".
        "</div>";
}

?>
<h1><?=$info['name']?></h1>

<div id="profile">
    <img src="http://www.gravatar.com/avatar/<?=$hash?>?d=identicon&s=100" class="gravatar">
    <p>Användarnamn: <?=$info['acronym']?></p>

    <h3>Frågor av <?=$info['name']?></h3>
    <div id="questions">
        <?=$strQuestions?>
    </div>
    <h3>Svar från <?=$info['name']?></h3>
    <div id="questions">
        <?=$strAnswers?>
    </div>

</div>