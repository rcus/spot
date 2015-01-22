<?php

namespace Rcus\Questions;

/**
 * A class to handle questions
 *
 */
class CQuestions extends \Anax\MVC\CDatabaseModel
{
    /**
     * Find and return ten latest questions.
     *
     * @return array
     */
    public function findQuestions()
    {
        // Specify values to get
        $getValues = ['id', 'title', 'created', 'name', 'acronym', 'email'];

        // Get question from db
        $strGetValues = implode(", ", $getValues);
        $this->db->select($strGetValues)
                 ->from('VInfo')
                 ->where('type ="Q"')
                 ->orderby('created DESC')
                 ->limit(10);

        $this->db->execute();
        $this->db->setFetchMode(\PDO::FETCH_ASSOC);
        $data = $this->db->fetchAll();

        // Add Gravatar hash to questions
        foreach (array_keys($data) as $no) {
            if (isset($data[$no]['email'])) {
                $data[$no]['hash'] = md5( strtolower( trim( $data[$no]['email'] ) ) );
                unset($data[$no]['email']);
            }
            $data[$no]['t'] = self::getTags($data[$no]['id']);
            $data[$no] = array_merge($data[$no], self::howMany("qNo", $data[$no]['id']));
        }

        return $data;
    }

    /**
     * Find and return questions with a specific tag.
     *
     * @return array
     */
    public function findTaggedQuestions($id)
    {
        // Specify values to get
        $getValues = ['id', 'title', 'created', 'name', 'acronym', 'email'];

        // Get question from db
        $strGetValues = implode(", ", $getValues);
        $this->db->select($strGetValues)
                 ->from('VTaggedInfo')
                 ->where('tagId = ?')
                 ->orderby('created DESC');

        $this->db->execute([$id]);
        $this->db->setFetchMode(\PDO::FETCH_ASSOC);
        $data = $this->db->fetchAll();

        // Add Gravatar hash to questions
        foreach (array_keys($data) as $no) {
            if (isset($data[$no]['email'])) {
                $data[$no]['hash'] = md5( strtolower( trim( $data[$no]['email'] ) ) );
                unset($data[$no]['email']);
            }
            $data[$no]['t'] = self::getTags($data[$no]['id']);
            $data[$no] = array_merge($data[$no], self::howMany("qNo", $data[$no]['id']));
        }

        return $data;
    }

    /**
     * Find and return a specific question with answers and comments.
     *
     * @param int $id get value of id from specific row
     * @return array
     */
    public function viewQuestion($id)
    {
        // Specify values to get
        $getValues['q'] = ['id', 'authorId', 'title', 'text', 'created', 'name', 'acronym', 'email'];
        $getValues['a'] = ['id', 'authorId', 'text', 'created', 'name', 'acronym', 'email'];
        $getValues['c'] = ['id', 'commentTo', 'authorId', 'text', 'created', 'name', 'acronym', 'email'];

        // Get question from db
        $strGetValues = implode(", ", $getValues['q']);
        $this->db->select($strGetValues)
                 ->from('VInfo')
                 ->where('id = ?');

        $this->db->execute([$id]);
        $this->db->setFetchMode(\PDO::FETCH_ASSOC);
        $data['q'] = $this->db->fetchOne();

        // Add Gravatar hash to question
        if (isset($data['q']['email'])) {
            $data['q']['hash'] = md5( strtolower( trim( $data['q']['email'] ) ) );
            unset($data['q']['email']);
        }

        // Get tags for question
        $data['t'] = self::getTags($id);

        // Get answers from db
        $strGetValues = implode(", ", $getValues['a']);
        $this->db->select($strGetValues)
                 ->from('VInfo')
                 ->where('qNo = ? AND type = "A"');

        $this->db->execute([$id]);
        $this->db->setFetchMode(\PDO::FETCH_ASSOC);
        $data['a'] = $this->db->fetchAll();

        // Add Gravatar hash to answers
        foreach (array_keys($data['a']) as $no) {
            if (isset($data['a'][$no]['email'])) {
                $data['a'][$no]['hash'] = md5( strtolower( trim( $data['a'][$no]['email'] ) ) );
                unset($data['a'][$no]['email']);
            }
        }

        // Get comment from db
        $strGetValues = implode(", ", $getValues['c']);
        $this->db->select($strGetValues)
                 ->from('VInfo')
                 ->where('qNo = ? AND type = "C"');

        $this->db->execute([$id]);
        $this->db->setFetchMode(\PDO::FETCH_ASSOC);
        $comments = $this->db->fetchAll();

        // Add Gravatar hash to comments
        foreach (array_keys($comments) as $no) {
            if (isset($comments[$no]['email'])) {
                $comments[$no]['hash'] = md5( strtolower( trim( $comments[$no]['email'] ) ) );
                unset($comments[$no]['email']);
            }
        }

        // Re-order and merge comments to $data, all comments for a specific question/answer in one subarray
        $data['c'] = [];
        foreach ($comments as $comment) {
            $data['c'][$comment['commentTo']][] = $comment;
        }

        return $data;
    }

    /**
     * Get the title for a question.
     *
     * @param int $id The ID for a specific question.
     * @return string
     */
    public function getTitle($id)
    {
        $content = $this->findID($id);
        $info = $content->getProperties();
        return $info['title'];
    }

    /**
     * Get tags for a question.
     *
     * @param int $id The ID for a specific question.
     * @return string
     */
    public function getTags($id=null)
    {
        if (is_numeric($id)) {
            $this->theme->addStylesheet('css/tags.css');
            $this->db->select('tagId, tag')
                     ->from('VTagged')
                     ->where('qNo = ?');

            $this->db->execute([$id]);
            $this->db->setFetchMode(\PDO::FETCH_KEY_PAIR);
            $data = $this->db->fetchAll();
        }
        else {
            $this->db->select()
                     ->from('tags');

            $this->db->execute();
            $this->db->setFetchMode(\PDO::FETCH_KEY_PAIR);
            $data = $this->db->fetchAll();
        }
        return $data;
    }

    /**
     * Count how many questions (Q), answers (A) or comments (C) for a specific query (like question or authorId).
     *
     * @param string $col Defines what we are looking for, like text-id/authorId.
     * @param int $id ID of the specific question/author.
     * @return array
     */
    public function howMany($col, $id)
    {
        $this->db->select('type, COUNT(type)')
                 ->from($this->getSource())
                 ->where($col.' = ?')
                 ->groupBy('type');

        $this->db->execute([$id]);
        $this->db->setFetchMode(\PDO::FETCH_KEY_PAIR);
        return $this->db->fetchAll();
    }

    /**
     * Get the name of a tag.
     *
     * @param int $id The ID for a specific tag.
     * @return string
     */
    public function getTagName($id)
    {
        // Get tag from db
        $this->db->select()
                 ->from('tags')
                 ->where('id = ?');

        $this->db->execute([$id]);
        $this->db->setFetchMode(\PDO::FETCH_ASSOC);
        $data = $this->db->fetchOne();

        return $data['tag'];
    }


/**

*/


    /**
     * Count how many answers for a specific question.
     *
     * @param int $id get value of id from specific row
     * @return int
     */
    public function howManyAnswers($id)
    {
        $this->db->select(COUNT())
                 ->from($this->getSource())
                 ->where('qNo = ? AND type ="A"');

        $this->db->execute([$id]);
        return $this->db->fetchInto($this);
    }

    /**
     * Find and return answers for a specific question.
     *
     * @param int $id get value of id from specific row
     * @return array
     */
    public function findAnswers($id)
    {
        $getValues = ['id', 'authorId', 'text', 'created', 'name', 'acronym', 'email'];
        $strGetValues = implode(", ", $getValues);
        $this->db->select($strGetValues)
                 ->from('VInfo')
                 ->where('qNo = ? AND type = "A"');

        $this->db->execute([$id]);
        $this->db->setFetchModeClass(__CLASS__);
        $ret = $this->db->fetchAll();

        $answers = [];
        foreach ($ret as $answer) {
            $a = $answer->getProperties();
            foreach ($getValues as $value) {
                if ($value === 'email') {
                    $answers[$a['id']]['hash'] = md5( strtolower( trim( $a[$value] ) ) );
                }
                else
                    $answers[$a['id']][$value] = $a[$value];
            }
        }
        return $answers;
    }

    /**
     * Find and return comments connected to a specific question or answer.
     *
     * @param int $id get value of id from specific row
     * @return array
     */
    public function findComments($id)
    {
        $getValues = ['id', 'commentTo', 'authorId', 'text', 'created', 'name', 'acronym', 'email'];
        $strGetValues = implode(", ", $getValues);
        $this->db->select($strGetValues)
                 ->from('VInfo')
                 ->where('qNo = ? AND type = "C"')
                 ->orderby('created ASC');

        $this->db->execute([$id]);
        $this->db->setFetchModeClass(__CLASS__);
        $ret = $this->db->fetchAll();

        $comments = [];
        foreach ($ret as $comment) {
            $c = $comment->getProperties();
            foreach ($getValues as $value) {
                if ($value === 'email') {
                    $comments[$c['commentTo']][$c['id']]['hash'] = md5( strtolower( trim( $c[$value] ) ) );
                }
                else
                    $comments[$c['commentTo']][$c['id']][$value] = $c[$value];
            }
        }
        return $comments;
    }


}
