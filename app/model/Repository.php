<?php

namespace TechnicalControl;

use Nette;



/**
 * Provádí operace nad databázovou tabulkou.
 */
abstract class Repository extends Nette\Object
{
    /** @var Nette\Database\Connection */
    protected $database;

    public function __construct(Nette\Database\Connection $db)
    {
        $this->database = $db;
    }

    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * Vrací objekt reprezentující databázovou tabulku.
     * @return Nette\Database\Table\Selection
     */
    protected function getTable()
    {
        // název tabulky odvodíme z názvu třídy
        preg_match('#(\w+)Repository$#', get_class($this), $m);
        return $this->database->table(lcfirst($m[1]));
    }
    
    /**
     * Vrací všechny řádky z tabulky.
     * @return Nette\Database\Table\Selection
     */
    public function findAll()
    {
        return $this->getTable();
    }

    /**
     * Vrací řádky podle filtru, např. array('name' => 'John').
     * @return Nette\Database\Table\Selection
     */
    public function findBy(array $by)
    {
        return $this->getTable()->where($by);
    }
    
    public function findById($id)
    {
        return $this->findBy(array('id' => $id))->fetch();
    }
    
}
