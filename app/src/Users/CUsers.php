<?php

namespace Rcus\Users;
 
/**
 * Model for Users.
 *
 */
class CUsers extends \Anax\MVC\CDatabaseModel
{
    /**
     * Find and return user by acronym.
     *
     * @param int $id get value of id from specific row
     *
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
}