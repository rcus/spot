<?php
// Prepare tags as HTML in a string
$strTags = "";
foreach ($data['t'] as $id => $tag) {
    $strTags .= "<div class='tag'>".
        "<a href='{$this->url->create('questions/tags/'.$id)}'>{$tag}</a>".
        "</div>";
}

// Get the question as HTML in a string
    // $content = $app->textFilter->doFilter($app->fileContent->get('about.md'), 'shortcode, markdown');

$strQuestion = "<p class='name'><a href='{$this->url->create('users/view/'.$data['q']['acronym'])}' class='userlink'>".
    "<img src='http://www.gravatar.com/avatar/{$data['q']['hash']}?d=identicon&s=36' class='gravatar'> {$data['q']['name']}</a></p>".
    "<div class='text'>{$this->textFilter->doFilter($data['q']['text'], 'shortcode, markdown')}</div>".
    "<p class='date'>{$data['q']['created']}</p>".
    "<p class='tags'>$strTags</p>".
    GetComments($data['q']['id'], $data['c']);

// Prepare link for write a answer
$strWriteAnswer = "";
if ($this->session->has('acronym')) {
    $strWriteAnswer = "<p class='answerLink'><a href='{$this->url->create('questions/write/answer/'.$data['q']['id'])}'>Svara på frågan.</a></p>";
}

// Get answers as HTML in a string
$strAnswers = "";
$noOfAnswers = 0;
foreach ($data['a'] as $a) {
    $noOfAnswers++;
    $strAnswers .= "<div class='answer'>".
        "<p class='name'><a href='{$this->url->create('users/view/'.$a['acronym'])}' class='userlink'><img src='http://www.gravatar.com/avatar/{$a['hash']}?d=identicon&s=36' class='gravatar'> {$a['name']}</a></p>".
        "<div class='text'>{$this->textFilter->doFilter($a['text'], 'shortcode, markdown')}</div>".
        "<p class='date'>{$a['created']}</p>".
        GetComments($a['id'], $data['c']).
        "</div>";
}

// Function to get comments in HTML
function GetComments($id, $comments) {
    global $di;
    $html = "";
    if (isset($comments[$id])) {
        foreach ($comments[$id] as $c) {
            $html .= "<div class='comment'>".
                "<p><a href='{$di->url->create('users/view/'.$c['acronym'])}' class='userlink'><img src='http://www.gravatar.com/avatar/{$c['hash']}?d=identicon&s=18' class='gravatar'> ".
                "<span class='name'>{$c['name']}</span></a> ".
                "<span class='info'>{$c['created']}</span></p> ".
                // "{$c['text']} ".
                "<div class='ctext'>{$di->textFilter->doFilter($c['text'], 'shortcode, markdown')}</div> ".
                "</div>";
        }
    }
    if ($di->session->has('acronym')) {
        $html .= "<div class='comment'><a href='{$di->url->create('questions/write/comment/'.$id)}'><i class='fa fa-comment'></i> Lämna en kommentar</a></div>";
    }
    return $html;
}
?>

<h1><?=$data['q']['title']?></h1>

<div id='qa'>
    <div class="question">
       <?=$strQuestion?>
    </div>

    <h2><?=$noOfAnswers?> svar</h2>
    <?=$strWriteAnswer?>
    <div class="answers">
        <?=$strAnswers?>
    </div>
</div>
