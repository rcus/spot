<?php

namespace Rcus\Users;

/**
 * A controller for users events.
 *
 */
class CUsersController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
        $this->users = new \Rcus\Users\CUsers();
        $this->users->setDI($this->di);
        $this->theme->addStylesheet('css/users.css');
    }

    /**
     * List all users.
     *
     * @return void
     */
    public function indexAction()
    {
        $users = $this->users->findAll();

        $this->theme->setTitle("Alla användare");
        $this->views->add('users/list', [
            'users' => $users,
            'title' => "Alla användare"
        ], 'main');
    }

    /**
     * View a user.
     *
     * @param string $acronym Acronym of user to display.
     * @return void
     */
    public function viewAction($acronym = null)
    {
        if (!isset($acronym)) {
            die("Missing parameter");
        }

        $user = $this->users->findByAcronym($acronym);

        $this->theme->setTitle("Användare: ".$acronym);
        $this->views->add('users/view', [
            'user' => $user
        ]);
    }

    /**
     * Add new useraccount.
     *
     * @return void
     */
    public function addAction()
    {
        // Get the form
        $form = self::userForm();

        // Check the status of the form
        $status = $form->check();
         
        if ($status === true) {
            $this->users->save([
                'acronym' => $form->value('acronym'),
                'email' => $form->value('email'),
                'name' => $form->value('name'),
                'password' => password_hash( $form->value('password'), PASSWORD_DEFAULT)
            ]);

            $this->response->redirect($this->url->create('users/login'));
        }

        $this->theme->setTitle('Skapa konto');
        $this->views->addString("<h1>Skapa konto</h1>" . $form->getHTML(), 'main');
    }

    /**
     * Log in user.
     *
     * @return void
     */
    public function loginAction()
    {
        // Find out if referral page was restricted
        if (empty($this->session->has('denied')))
            $msg = "";
        else
            $msg = "<p>Sidan du försökte nå kräver att du är inloggad. Logga in med dina uppgifter nedan, eller <a href='{$this->url->create('users/add')}'>skapa ett konto</a>.</p>";

        // Get the form
        $form = self::loginForm($this->session->get('denied'));
        $this->session->set('denied', null);

        // Check the status of the form
        $status = $form->check();
         
        if ($status === true) {
            // Get userinfo
            $user = $this->users->findByAcronym($form->value('acronym'));

            // Check if user exist and password is ok
            if ($user && password_verify($form->value('password'), $user->getProperties()['password'])) {
                $this->session->set('acronym', $form->value('acronym'));
                $this->response->redirect($this->url->create($form->value('referral')));
            }
            else {
                $form->output = "<span style='color:red;'>Användarnamnet eller lösenordet stämmer inte. Försök igen eller skapa ett konto.</span>";
            }
        }

        $this->theme->setTitle('Logga in');
        $this->views->addString("<h1>Logga in</h1>" . $msg . $form->getHTML(), 'main');
    }

    /**
     * Log out user.
     *
     * @return void
     */
    public function logoutAction()
    {
        // Unset session
        $this->session->set('acronym', null);
        $this->theme->setTitle('Utloggad');
        $this->views->addString("<h1>Du har nu loggat ut</h1><p><a href='{$this->url->create('')}'>Gå tillbaka till startsidan.</a></p>", 'main');
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
        $form = self::userForm($user);

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
     * Userform.
     *
     * @param object $user user to edit.
     *
     * @return object the form.
     */
    private function userForm($user = null) {
        $info = null;
        $legend = 'Skapa konto';
        $pwMsg = '';
        $pwValid = ['not_empty'];
        if (!is_null($user)) {
            $info = $user->getProperties();
            $legend = 'Redigera uppgifter';
            $pwMsg = "Fylls bara i om du vill ändra ditt lösenord.";
            $pwValid = [];
        }
        $this->theme->addStylesheet('css/form.css');
        $form = new \Mos\HTMLForm\CForm(['legend'=>$legend], [
                'id' => [
                    'type'       => 'hidden',
                    'value'      => $info['id']
                ],
                'name' => [
                    'type'       => 'text',
                    'label'      => 'Namn',
                    'value'      => $info['name'],
                    'validation' => ['not_empty']
                ],
                'acronym' => [
                    'type'       => 'text',
                    'label'      => 'Användarnamn',
                    'value'      => $info['acronym'],
                    'validation' => ['not_empty']
                ],
                'email' => [
                    'type'       => 'text',
                    'label'      => 'E-post',
                    'value'      => $info['email'],
                    'description'=> 'Används enbart till Gravatar, kommer inte att visas.',
                    'validation' => ['not_empty', 'email_adress']
                ],
                'password' => [
                    'type'       => 'password',
                    'label'      => 'Lösenord',
                    'description'=> $pwMsg,
                    'validation' => $pwValid
                ],
                'password_confirm' => [
                    'type'       => 'password',
                    'label'      => 'Lösenordet igen',
                    'validation' => ['match' => 'password']
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
     * Login form.
     *
     * @param object $user user to edit.
     *
     * @return object the form.
     */
    private function loginForm($ref) {
        $this->theme->addStylesheet('css/form.css');
        $form = new \Mos\HTMLForm\CForm(['legend'=>'Inloggning'], [
                'referral' => [
                    'type'       => 'hidden',
                    'value'      => $ref
                ],
                'acronym' => [
                    'type'       => 'text',
                    'label'      => 'Användarnamn',
                    'validation' => ['not_empty']
                ],
                'password' => [
                    'type'       => 'password',
                    'label'      => 'Lösenord',
                    'validation' => ['not_empty']
                ],
                'submit' => [
                    'type'       => 'submit',
                    'value'      => 'Logga in',
                    'callback'   => function ($form) {
                        $form->saveInSession = false;
                        return true;
                    }
                ]
            ]);
        return $form;
    }


}