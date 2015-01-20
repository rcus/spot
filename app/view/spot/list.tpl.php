<?php
// Prevent guests to view this page.
$this->di = restrictedPage($this->di);
?>

<h1><?=$title?></h1>

<div id='questions'>
<?php
foreach ($questions as $question) {
    $info = $question->getProperties();
    // $hash = md5( strtolower( trim( $info['email'] ) ) );
    $questionUrl = $this->url->create('questions/view/' . $info['slug']);

echo <<<EOD
<div class="question">
    <h3><a href="$questionUrl">{$info['title']}</a></h3>
    <p class="date">{$info['created']}</p>
    <p class="links">Frågor | Svar</p>
</div>
EOD;
}
?>
</div>