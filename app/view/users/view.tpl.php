<?php
$info = $user->getProperties();
$hash = md5( strtolower( trim( $info['email'] ) ) );
?>
<h1><?=$info['name']?></h1>

<div id="profile">
    <img src="http://www.gravatar.com/avatar/<?=$hash?>?d=identicon&s=100" class="gravatar">
    <p>Användarnamn: <?=$info['acronym']?></p>

    <h3>Frågor av <?=$info['name']?></h3>
    <h3>Svar från <?=$info['name']?></h3>

</div>