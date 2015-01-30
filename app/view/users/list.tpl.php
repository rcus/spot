<?php
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

<h1><?=$title?></h1>

<div id='users'>
   <?=$strUsers?>
</div>
