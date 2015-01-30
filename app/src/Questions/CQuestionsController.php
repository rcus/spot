<?php

namespace Rcus\Questions;

/**
 * A controller for question related events.
 *
 */
class CQuestionsController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
        $this->questions = new \Rcus\Questions\CQuestions();
        $this->questions->setDI($this->di);
        $this->theme->addStylesheet('css/questions.css');
    }

    /**
     * List all questions.
     *
     * @return void
     */
    public function indexAction()
    {
        $questions = $this->questions->findQuestions();

        $this->theme->setTitle("Alla frågor");
        $this->views->add('questions/list', [
            'questions' => $questions,
            'title' => "Alla frågor"
        ], 'main');
    }

    /**
     * View a question.
     *
     * @param string $id ID of question.
     * @return void
     */
    public function viewAction($id = null)
    {
        if (!isset($id)) {
            die("Missing parameter");
        }

        // Get question, answers and comments
        $data = $this->questions->viewQuestion($id);

        $this->theme->setTitle($this->questions->getTitle($id));
        $this->views->add('questions/view', [
            'data' => $data
        ]);
    }

    /**
     * View questions with a specific tag.
     *
     * @param string $id ID of question.
     * @return void
     */
    public function tagsAction($id = null)
    {
        // Get all tags from db
        if (is_null($id)) {
            $tags = $this->questions->getTags();

            $this->theme->setTitle("Alla taggar");
            $this->views->add('questions/tags', [
                'tags' => $tags,
                'title' => "Alla taggar"
            ], 'main');
        }

        elseif (is_numeric($id)) {
            $questions = $this->questions->findTaggedQuestions($id);
            $title = $this->questions->getTagName($id);

            $this->theme->setTitle($title);
            $this->views->add('questions/list', [
                'questions' => $questions,
                'title' => "Tagg: $title"
            ], 'main');
        }
        else {
            die("Missing parameter");
        }
    }

    /**
     * Add new question.
     *
     * @return void
     */
    public function addAction()
    {
        // Prevent guests to view restricted pages.
        $this->users->restrictedPage();

        // Get the form
        $form = self::questionForm();

        // Check the status of the form
        $status = $form->check();

        if ($status === true) {
            $fieldsOk = true;
            $msg = "";
            // Check if all fields are valid ($fieldsOk)
            if (empty($form->value('title'))) {
                $msg .= "Du måste fylla rubrik för din fråga!<br/>";
                $fieldsOk = false;
            }
            if (empty($form->value('text'))) {
                $msg .= "Du måste fylla din text!<br/>";
                $fieldsOk = false;
            }
            if (empty($_POST['tags'])) {
                $msg .= "Du måste ange minst en tagg!<br/>";
                $fieldsOk = false;
            }
            $msg .= "<a href='javascript:history.back();'>Gå tillbaka till formuläret.</a>";

            // Save data, if $fieldsOk = true
            if ($fieldsOk) {
                $this->questions->save([
                    'qNo' => $form->value('qNo'),
                    'type' => $form->value('type'),
                    'authorId' => $this->users->getId($this->session->get('acronym')),
                    'title' => $form->value('title'),
                    'text' => $form->value('text'),
                    'tags' => $_POST['tags']
                ]);

                $this->response->redirect($this->url->create('questions/view/'.$this->questions->id));
            }
            else {
                // Error, show msg.
                $this->theme->setTitle('Fel i formulär');
                $this->views->addString("<h1>Fel i formulär</h1><p>" . $msg . "</p>", 'main');
            }
        }
        else {
            $this->theme->setTitle('Ställ en fråga');
            $this->views->addString("<h1>Ställ en fråga</h1>" . $form->getHTML(), 'main');
        }
    }

    /**
     * Add an answer.
     *
     * @param int $qNo No of question to answer.
     * @return void
     */
    public function writeAction($type, $ref)
    {
        // Prevent guests to view restricted pages.
        $this->users->restrictedPage();

        // Get the form
        $type = ($type === 'answer') ? "A" : "C";
        $form = self::textForm($type, $ref);

        // Check the status of the form
        $status = $form->check();

        if ($status === true) {
            $fieldsOk = true;
            $msg = "";
            // Check if all fields are valid ($fieldsOk)
            if (empty($form->value('text'))) {
                $msg .= "Du måste fylla din text!<br/>";
                $fieldsOk = false;
            }
            $msg .= "<a href='javascript:history.back();'>Gå tillbaka till formuläret.</a>";

            // Save data, if $fieldsOk = true
            if ($fieldsOk) {
                $this->questions->save([
                    'qNo' => $form->value('qNo'),
                    'commentTo' => $form->value('commentTo'),
                    'type' => $type,
                    'authorId' => $this->users->getId($this->session->get('acronym')),
                    'text' => $form->value('text')
                ]);

                $this->response->redirect($this->url->create('questions/view/'.$this->questions->getqNo($ref)));
            }
            else {
                // Error, show msg.
                $this->theme->setTitle('Fel i formulär');
                $this->views->addString("<h1>Fel i formulär</h1><p>" . $msg . "</p>", 'main');
            }
        }
        else {

            // Prepare for answer or comment
            if ($type === "A") {
                $title = "Svara på en fråga";
                $content = "<h1>Svar till: {$this->questions->getTitle($ref)}</h1>".
                    "<div class='qtext'>{$this->textFilter->doFilter($this->questions->getText($ref), 'shortcode, markdown')}</div>";
            }
            else {
                $title = "Lämna en kommentar";
                $content = "<h1>Lämna en kommentar</h1>";
            }
            $this->theme->setTitle($title);
            $this->views->addString($content . $form->getHTML(), 'main');
        }
    }

    /**
     * Edit a user.

     *
     * @return void
     */
    public function editAction()
    {
        // Get the user
        $user = $this->users->findByAcronym($this->session->get('acronym'));

        // Get the form
        $form = self::textForm($user);

        // Check the status of the form
        $status = $form->check();
         
        if ($status === true) {
            $this->users->save([
                'acronym' => $form->value('acronym'),
                'email' => $form->value('email'),
                'name' => $form->value('name')
            ]);

            if ($form->value('password') <> '') {
                $this->users->save([
                    'password' => password_hash( $form->value('password'), PASSWORD_DEFAULT)
                ]);
            }

            $this->session->set('acronym', $form->value('acronym'));
            $this->response->redirect($this->url->create('users/view/'.$form->value('acronym')));
        }

        $this->theme->setTitle('Ändra din profil');
        $this->views->addString("<h1>Ändra din profil</h1>" . $form->getHTML(), 'main');
    }

    /**
     * Textform.
     *
     * @param mixed $param Type to create or text to edit.
     * @return object The form.
     */
    private function questionForm($param = 'Q') {

        $tags = $this->questions->getTags();
        $info = null;
        $legend = 'Skriv en fråga';
        // if (is_object($text)) {
        //     $info = $text->getProperties();
        //     $param = null;
        // }
        $this->theme->addStylesheet('css/form.css');
        $form = new \Mos\HTMLForm\CForm(['legend' => $legend], [
                'id' => [
                    'type'       => 'hidden',
                    'value'      => $info['id']
                ],
                'qNo' => [
                    'type'       => 'hidden',
                    'value'      => $info['qNo']
                ],
                'type' => [
                    'type'       => 'hidden',
                    'value'      => 'Q'
                ],
                'title' => [
                    'type'       => 'text',
                    'label'      => 'Rubrik',
                    // 'value'      => $info['title']
                ],
                'text' => [
                    'type'       => 'textarea',
                    'label'      => 'Text',
                    'description'=> 'Du kan formatera din text som <a href="http://daringfireball.net/projects/markdown/syntax" target="_blank">Markdown</a>.'
                ],
                'tags' => [
                    'type'       => 'checkbox-multiple',
                    'values'     => $tags,
                    // 'checked'    => array('potato', 'pear')
                ],
                'submit' => [
                    'type'       => 'submit',
                    'value'      => 'Skicka',
                    'callback'   => function ($form) {
                        $form->saveInSession = false;
                        return true;
                    }
                ]
            ]);
        return $form;
    }

    /**
     * Textform.
     *
     * @param mixed $param Type to create or object to edit.
     * @param int $id Id of text to connect with answer/comment.
     * @return object The form.
     */
    private function textForm($param, $ref=null) {
        $info = null;
        $id = null;
        $commentTo = null;
        $text = null;
        if (is_object($param)) {
            $info = $param->getProperties();
            $param = $info['type'];
            $id = $info['id'];
            $qNo = $info['qNo'];
            $commentTo = $info['commentTo'];
            $text = $info['text'];
        }
        elseif ($param === "A") {
            $qNo = $ref;
        }
        else {
            $qNo = $this->questions->getqNo($ref);
            $commentTo = $ref;
        }

        $legend = ($param === "C") ? 'Din kommentar' : 'Ditt svar på frågan';

        $this->theme->addStylesheet('css/form.css');
        $form = new \Mos\HTMLForm\CForm(['legend' => $legend], [
                'id' => [
                    'type'       => 'hidden',
                    'value'      => $id
                ],
                'qNo' => [
                    'type'       => 'hidden',
                    'value'      => $qNo
                ],
                'commentTo' => [
                    'type'       => 'hidden',
                    'value'      => $commentTo
                ],
                'type' => [
                    'type'       => 'hidden',
                    'value'      => $param
                ],
                'text' => [
                    'type'       => 'textarea',
                    'label'      => 'Text',
                    'value'      => $text,
                    'description'=> 'Du kan formatera din text som <a href="http://daringfireball.net/projects/markdown/syntax" target="_blank">Markdown</a>.'
                ],
                'submit' => [
                    'type'       => 'submit',
                    'value'      => 'Skicka',
                    'callback'   => function ($form) {
                        $form->saveInSession = false;
                        return true;
                    }
                ]
            ]);
        return $form;
    }
}
