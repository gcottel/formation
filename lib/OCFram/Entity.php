<?php
namespace OCFram;


abstract class Entity implements \ArrayAccess, \JsonSerializable
{
    use Hydrator;

    protected $erreurs = [],
        $id;

    public function __construct(array $donnees = [])
    {
        if (!empty($donnees))
        {
            $this->hydrate($donnees);
        }
    }

    public function isNew()
    {
        return empty($this->id);
    }

    public function erreurs()
    {
        return $this->erreurs;
    }

    public function id()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = (int) $id;
    }

    public function offsetGet($var)
    {
        if (isset($this->$var) && is_callable([$this, $var]))
        {
            return $this->$var();
        }
    }

    public function offsetSet($var, $value)
    {
        $method = 'set'.ucfirst($var);

        if (isset($this->$var) && is_callable([$this, $method]))
        {
            $this->$method($value);
        }
    }

    public function offsetExists($var)
    {
        return isset($this->$var) && is_callable([$this, $var]);
    }

    public function offsetUnset($var)
    {
        throw new \Exception('Impossible de supprimer une quelconque valeur');
    }
	
	
	public function getAttributeToJSON() {
		$ReflexionClass = new \ReflectionClass($this);
		$retour = [];
		foreach($ReflexionClass->getProperties() as $Property) {
			$retour[] = $Property->getName();
		}
		return $retour;
	}
	
	public function jsonSerialize()
	{
		$retour = [];
		
		foreach($this->getAttributeToJSON() as $property) {
			$retour[$property] = $this->$property;
		}
		return $retour;
	}
}