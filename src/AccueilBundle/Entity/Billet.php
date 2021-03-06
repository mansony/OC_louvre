<?php

namespace AccueilBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Billet
 *
 * @ORM\Table(name="billet")
 * @ORM\Entity(repositoryClass="AccueilBundle\Repository\BilletRepository")
 */
class Billet
{
    /**
     * @ORM\ManyToOne(targetEntity="AccueilBundle\Entity\Tarifs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tarifs;

    /**
     * @ORM\ManyToOne(targetEntity="AccueilBundle\Entity\Reservation", inversedBy="billets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reservation;

    /**
    * @ORM\ManyToOne(targetEntity="AccueilBundle\Entity\Pays")
    * @ORM\JoinColumn(nullable=false)
    */
    private $pays;

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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateNaissance", type="date")
     */
    private $dateNaissance;

    /**
     * @var boolean
     *
     * @ORM\Column(name="TarifReduit", type="boolean")
     */
    private $tarifReduit;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;

    public function __construct()
    {
        $this->pays = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Billet
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return Billet
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     * @return Billet
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime 
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set reservation
     *
     * @param \AccueilBundle\Entity\Reservation $reservation
     * @return Billet
     */
    public function setReservation(\AccueilBundle\Entity\Reservation $reservation = null)
    {
        $this->reservation = $reservation;

        return $this;
    }

    /**
     * Get reservation
     *
     * @return \AccueilBundle\Entity\Reservation 
     */
    public function getReservation()
    {
        return $this->reservation;
    }

    /**
     * Set pays
     *
     * @param \AccueilBundle\Entity\Pays $pays
     * @return Billet
     */
    public function setPays(\AccueilBundle\Entity\Pays $pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return \AccueilBundle\Entity\Pays 
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set tarifReduit
     *
     * @param boolean $tarifReduit
     * @return Billet
     */
    public function setTarifReduit($tarifReduit)
    {
        $this->tarifReduit = $tarifReduit;

        return $this;
    }

    /**
     * Get tarifReduit
     *
     * @return boolean 
     */
    public function getTarifReduit()
    {
        return $this->tarifReduit;
    }

    /**
     * Set tarifs
     *
     * @param \AccueilBundle\Entity\Tarifs $tarifs
     * @return Billet
     */
    public function setTarifs(\AccueilBundle\Entity\Tarifs $tarifs)
    {
        $this->tarifs = $tarifs;

        return $this;
    }

    /**
     * Get tarifs
     *
     * @return \AccueilBundle\Entity\Tarifs 
     */
    public function getTarifs()
    {
        return $this->tarifs;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Billet
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }
}
