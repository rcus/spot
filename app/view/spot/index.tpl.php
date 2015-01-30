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

// Get tags as HTML in a string
$strTags = "";
foreach ($tags as $tag) {
    $strTags .= "<div class='tag'>".
        "<a href='{$this->url->create('questions/tags/'.$tag['tagId'])}'>{$tag['tag']} <sup>{$tag['amount']}</sup></a>".
        "</div>";
}

// Get users as HTML in a string
$strUsers = "";
foreach ($users as $user) {
    // Prepare some info
    $hash = md5( strtolower( trim( $user['email'] ) ) );
    $userUrl = $this->url->create('users/view/' . $user['acronym']);
    $statistics =  "<i class='fa fa-question'></i> ". (isset($user['Q']) ? $user['Q'] : "0");
    $statistics .= "<i class='fa fa-exclamation'></i> ". (isset($user['A']) ? $user['A'] : "0");
    $statistics .= "<i class='fa fa-comment'></i> ". (isset($user['C']) ? $user['C'] : "0");

    $strUsers .= "<div class='user' onclick=\"location.href='$userUrl';\" style='cursor:pointer;'>".
        "<a href='$userUrl'><img src='http://www.gravatar.com/avatar/$hash?d=identicon' class='gravatar'></a>".
        "<p><a href='$userUrl'>{$user['name']}</a></p>".
        "<p class='links'><a href='$userUrl'>$statistics</a></p>".
        "</div>";
}
?>

<h1>De 3 senaste frågorna</h1>

<div id='questions'>
   <?=$strQuestions?>
</div>

<div id='sidebar'>
    <h2>Populära taggar</h2>
    <div id='tags'>
       <?=$strTags?>
    </div>

    <h2>Aktiva användare</h2>
    <div id='users'>
       <?=$strUsers?>
    </div>

    <h2>Undrar du något?</h2>
    <div><a href='<?=$this->url->create('questions/add')?>'>Ställ en fråga!</a></div>
</div>
