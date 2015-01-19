<div>
<span class='sitetitle'><a href='<?=$this->url->create()?>'><?=isset($siteTitle) ? $siteTitle : "Spotify In Sight"?></a></span>
<?php if ($this->views->hasContent('navbar')) : ?>
    <div id='navbar'>
        <?php $this->views->render('navbar')?>
    </div>
<?php endif; ?>
</div>
