<?php

if ( $this->session->has('acronym') ) {
    $userHandle = "<a href='{$this->url->create('users/edit')}'>{$this->session->get('acronym')}</a> | <a href='{$this->url->create('users/logout')}'>Logga ut</a>";
}
else {
    $userHandle = "<a href='{$this->url->create('users/add')}'>Skapa konto</a> | <a href='{$this->url->create('users/login')}'>Logga in</a>";
}

?>

<div><a href="<?=$this->url->create('about')?>">Om</a> | <?=$userHandle?></div>