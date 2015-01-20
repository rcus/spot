<?php

namespace Rcus\Questions;

/**
 * A class to handle questions
 *
 */
class CQuestions extends \Anax\MVC\CDatabaseModel
{
    /**
     * Find and return ten latest.
     *
     * @return array
     */
    public function findTen()
    {
        $this->db->select()
                 ->from($this->getSource())
                 ->limit(10);
     
        $this->db->execute();
        $this->db->setFetchModeClass(__CLASS__);
        return $this->db->fetchAll();
    }
}
