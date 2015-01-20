<?php
/**
 * Theme related functions. 
 *
 */

/**
 *  Prevent guests to view restricted pages.
 */
function allowPage($di) {
    $allowed = array('', 'users/login', 'users/logout', 'users/add', 'about');
    if ( !in_array($di->request->getRoute(), $allowed) && !$di->session->has('acronym') ) {
        $di->session->set('denied', $di->request->getRoute());
        $di->response->redirect($di->url->create('users/login'));
    }
    return $di;
}

/**
 *  Prevent guests to view restricted pages.
 */
function restrictedPage($di)
{
    if ( !$di->session->has('acronym') ) {
        $di->session->set('denied', $di->request->getRoute());
        $di->response->redirect($di->url->create('users/login'));
    }
    return $di;
}

/**
 * Get title for the webpage by concatenating page specific title with site-wide title.
 *
 * @param string $title for this page.
 * @param string $titleAppend a general title to append.
 * @return string/null wether the favicon is defined or not.
 */
/*function get_title($title, $titleAppend = null) {
  return $title . $title_append;
}
*/


