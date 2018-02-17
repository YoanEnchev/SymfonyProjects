<?php

namespace AppBundle\Repository;

/**
 * TabletRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TabletRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAllTablets()
    {
        $sql = "SELECT * FROM products
        WHERE type = :type";
        $params = array(
            'type' => 'tablet',
        );

        return $this->getEntityManager()->getConnection()->executeQuery($sql, $params)->fetchAll();
    }
}
