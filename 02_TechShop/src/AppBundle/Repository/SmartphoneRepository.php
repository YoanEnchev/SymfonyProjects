<?php

namespace AppBundle\Repository;

/**
 * SmartphoneRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SmartphoneRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAllSmartphones()
    {
        $sql = "SELECT * FROM products
        WHERE type = :type";
        $params = array(
            'type' => 'smartphone',
        );

        return $this->getEntityManager()->getConnection()->executeQuery($sql, $params)->fetchAll();
    }

    public function specificationsForOne($id)
    {
        $sql = "SELECT * FROM products
        INNER JOIN smartphones on products.id = smartphones.product_id
        WHERE smartphones.product_id = :id";
        $params = array(
            'id' => $id,
        );

        return $this->getEntityManager()->getConnection()->executeQuery($sql, $params)->fetchAll()[0];
    }
}
