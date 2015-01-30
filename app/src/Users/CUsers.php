<?php

namespace Rcus\Users;
 
/**
 * Model for Users.
 *
 */
class CUsers extends \Anax\MVC\CDatabaseModel
{
    /**
     * Find and return all.
     *
     * @return array
     */
    public function findAll()
    {
        $objData = parent::findAll();
        foreach ($objData as $key => $value) {
            $data[$key] = $value->getProperties();
            $data[$key] = array_merge($data[$key], $this->questions->howMany('authorId', $data[$key]['id']));
        }
        return $data;
    }

    /**
     * Find and return user by acronym.
     *
     * @param string $acronym Acronym for the user.
     * @return this
     */
    public function findByAcronym($acronym)
    {
        $this->db->select()
                 ->from($this->getSource())
                 ->where('acronym = ?');

        $this->db->execute([$acronym]);
        return $this->db->fetchInto($this);
    }

    /**
     * Find and return users id by acronym.
     *
     * @param string $acronym Acronym for the user.
     * @return int The id of the user.
     */
    public function getId($acronym)
    {
        $this->db->select('id')
                 ->from($this->getSource())
                 ->where('acronym = ?');

        $this->db->execute([$acronym]);
        $this->db->setFetchMode(\PDO::FETCH_ASSOC);
        $data = $this->db->fetchOne();
        return $data['id'];
    }

    /**
     * Prevent guests to view restricted pages.
     */
    function restrictedPage()
    {
        if ( !$this->session->has('acronym') ) {
            $this->session->set('denied', $this->request->getRoute());
            $this->response->redirect($this->url->create('users/login'));
        }
    }

}