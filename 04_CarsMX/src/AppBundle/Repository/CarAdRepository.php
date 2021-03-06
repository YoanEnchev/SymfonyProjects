<?php

namespace AppBundle\Repository;

/**
 * CarAdRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CarAdRepository extends \Doctrine\ORM\EntityRepository
{
    public function allCars()
    {
        $sql = 'SELECT * FROM car_ads';

        return $this->getEntityManager()->getConnection()->executeQuery($sql)->fetchAll();
    }

    public function allCarsPriceDesc()
    {
        $sql = 'SELECT * FROM car_ads
        ORDER BY price DESC';

        return $this->getEntityManager()->getConnection()->executeQuery($sql)->fetchAll();
    }

    public function allCarsPriceAsc()
    {
        $sql = 'SELECT * FROM car_ads
        ORDER BY price ASC';

        return $this->getEntityManager()->getConnection()->executeQuery($sql)->fetchAll();
    }

    public function allCarsYearDesc()
    {
        $sql = 'SELECT * FROM car_ads
        ORDER BY manufacture_year DESC';

        return $this->getEntityManager()->getConnection()->executeQuery($sql)->fetchAll();
    }

    public function allCarsYearAsc()
    {
        $sql = 'SELECT * FROM car_ads
        ORDER BY manufacture_year ASC';

        return $this->getEntityManager()->getConnection()->executeQuery($sql)->fetchAll();
    }

    public function allCarsPowerDesc()
    {
        $sql = 'SELECT * FROM car_ads
        ORDER BY engine_power DESC';

        return $this->getEntityManager()->getConnection()->executeQuery($sql)->fetchAll();
    }

    public function allCarsPowerAsc()
    {
        $sql = 'SELECT * FROM car_ads
        ORDER BY engine_power ASC';

        return $this->getEntityManager()->getConnection()->executeQuery($sql)->fetchAll();
    }

    public function deleteAllAdditionalImages($addId)
    {
        $sql = "DELETE FROM additional_images
        WHERE car_id = :id";
        $params = array(
            'id' => $addId,
        );
        return $this->getEntityManager()->getConnection()->executeQuery($sql, $params);
    }

    public function removeFromCheckLaterLists($addId)
    {
        $sql = "DELETE FROM users_ads_check_later
        WHERE ad_id = :id";
        $params = array(
            'id' => $addId,
        );
        return $this->getEntityManager()->getConnection()->executeQuery($sql, $params);
    }

    public function searchForCar($make, $model, $fuel, $transmission, $doors, $fromYear, $maxPrice, $sort, $toYear)
    {
        $sql = "SELECT * FROM car_ads
        WHERE id > 0 ";

        if($make != "Any") {
            $sql .= "AND make = :make ";
        }
        if($model != "Any") {
            $sql .= "AND model = :model ";
        }
        if($fuel != "Any") {
            $sql .= "AND fuel = :fuel ";
        }
        if($transmission != "Any") {
            $sql .= "AND transmission = :transmission ";
        }
        if($doors != "Any") {
            $sql .= "AND doors = :doors ";
        }
        if($fromYear != "Any") {
            $sql .= "AND manufacture_year > :fromYear ";
        }
        if($maxPrice != "Any") {
            $sql .= "AND price < :maxPrice ";
        }
        if($toYear != "Any") {
            $sql .= "AND manufacture_year < :toYear ";
        }

        switch ($sort)
        {
            case 'expensiveCheap':
                $sql .= "ORDER BY price DESC";
                break;
            case 'cheapExpensive':
                $sql .= "ORDER BY price ASC";
                break;
            case 'newOld':
                $sql .= "ORDER BY manufacture_year DESC";
                break;
            case 'oldNew':
                $sql .= "ORDER BY manufacture_year ASC";
                break;
            case 'morePowerLessPower':
                $sql .= "ORDER BY engine_power DESC";
                break;
            case 'lessPowerMorePower':
                $sql .= "ORDER BY engine_power ASC";
                break;
        }

        $params = array(
            'make' => $make,
            'model' => $model,
            'fuel' => $fuel,
            'transmission' => $transmission,
            'doors' => $doors,
            'fromYear' => $fromYear,
            'maxPrice' => $maxPrice,
            'toYear' => $toYear
        );

        return $this->getEntityManager()->getConnection()->executeQuery($sql, $params)->fetchAll();
    }

    public function latestAddedAds()
    {
        $sql = "SELECT * FROM car_ads
        ORDER BY date_added DESC
        LIMIT 10";

        return $this->getEntityManager()->getConnection()->executeQuery($sql)->fetchAll();
    }
}
