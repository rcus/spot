<h1><?=$title?></h1>

<div id='users'>
<?php
foreach ($users as $user) {
    $info = $user->getProperties();
    $hash = md5( strtolower( trim( $info['email'] ) ) );
    $userUrl = $this->url->create('users/view/' . $info['acronym']);

echo <<<EOD
<div class="user">
    <a href="$userUrl"><img src="http://www.gravatar.com/avatar/$hash?d=identicon" class="gravatar"></a>
    <p><a href="$userUrl">{$info['name']}</a></p>
    <p class="links">Frågor | Svar</p>
</div>
EOD;
}
?>
</div>