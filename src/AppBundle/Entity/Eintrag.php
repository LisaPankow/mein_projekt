<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Eintrag
 *
 * @ORM\Table(name="eintrag")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EintragRepository")
 */
class Eintrag
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
     * @ORM\Column(name="ueberschrift", type="string", length=255, nullable=true)
     */
    private $ueberschrift;
    
    /**
     * @var string
     *
     * @ORM\Column(name="kategorie", type="string", length=255, nullable=true)
     */
    private $kategorie;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", nullable=true)
     */
    private $text;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datum", type="datetime")
     */
    private $datum;


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
     * Set ueberschrift
     *
     * @param string $ueberschrift
     *
     * @return Eintrag
     */
    public function setUeberschrift($ueberschrift)
    {
        $this->ueberschrift = $ueberschrift;

        return $this;
    }

    /**
     * Get ueberschrift
     *
     * @return string
     */
    public function getUeberschrift()
    {
        return $this->ueberschrift;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Eintrag
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set datum
     *
     * @param \DateTime $datum
     *
     * @return Eintrag
     */
    public function setDatum($datum)
    {
        $this->datum = $datum;

        return $this;
    }

    /**
     * Get datum
     *
     * @return \DateTime
     */
    public function getDatum()
    {
        return $this->datum;
    }

    /**
     * Set kategorie
     *
     * @param string $kategorie
     *
     * @return Eintrag
     */
    public function setKategorie($kategorie)
    {
        $this->kategorie = $kategorie;

        return $this;
    }

    /**
     * Get kategorie
     *
     * @return string
     */
    public function getKategorie()
    {
        return $this->kategorie;
    }
}
