<?php 
 
namespace Mst\DoctrineWorkbenchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="workbench_schema")
 * @ORM\Entity
 */
class WorkbenchSchema implements \JsonSerializable
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name; 
    /**
     * @ORM\Column(name="zoom", type="float")
     */
    private $zoom; 
    /**
     * @ORM\Column(name="schema", type="text")
     */
    private $schema; 

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
     * Set name
     *
     * @param string $name
     *
     * @return Schema
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set zoom
     *
     * @param double $zoom
     *
     * @return Schema
     */
    public function setZoom($zoom)
    {
        $this->zoom = $zoom;

        return $this;
    }

    /**
     * Get zoom
     *
     * @return \double
     */
    public function getZoom()
    {
        return $this->zoom;
    }

    /**
     * Set schema
     *
     * @param string $schema
     *
     * @return Schema
     */
    public function setSchema($schema)
    {
        $this->schema = $schema;

        return $this;
    }

    /**
     * Get schema
     *
     * @return string
     */
    public function getSchema()
    {
        return $this->schema;
    }

    public function jsonSerialize()
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'schema' => $this->getSchema(),
            'zoom' => $this->getZoom(),
        );
    }
}
