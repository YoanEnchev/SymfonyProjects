<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdditionalImage
 *
 * @ORM\Table(name="additional_images")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AdditionalImageRepository")
 */
class AdditionalImage
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * Many AdditionalImage have One CarAd.
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CarAd", inversedBy="additionalImages" ,cascade={"persist"})
     * @ORM\JoinColumn(name="car_id", referencedColumnName="id")
     */
    private $carAd;



    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return AdditionalImage
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return CarAd
     */
    public function getCarAd()
    {
        return $this->carAd;
    }

    /**
     * @param CarAd $carAd
     *
     * @return AdditionalImage
     */
    public function setCarAd($carAd)
    {
        $this->carAd = $carAd;

        return $this;
    }
}

