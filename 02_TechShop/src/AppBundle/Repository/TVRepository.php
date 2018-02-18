<?php

namespace AppBundle\Repository;

/**
 * TVRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TVRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAllTVs()
    {
        $sql = "SELECT * FROM products
        WHERE type = :type";
        $params = array(
            'type' => 'tv',
        );

        return $this->getEntityManager()->getConnection()->executeQuery($sql, $params)->fetchAll();
    }

    public function specificationsForOne($id)
    {
        $sql = "SELECT * FROM products
        INNER JOIN tvs on products.id = tvs.product_id
        WHERE tvs.product_id = :id";
        $params = array(
            'id' => $id,
        );

        return $this->getEntityManager()->getConnection()->executeQuery($sql, $params)->fetchAll()[0];
    }
}
