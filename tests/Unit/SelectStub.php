<?php

namespace Tests\Unit;


/**
 * Простой стаб "селекта" из родителя:
 * поддерживает цепочку ->where()->execute() и сохраняет аргументы where.
 */

final class SelectStub
{
    /** @var list<array> */
    public array $whereArgs = [];
    /** @var mixed */
    private $result;

    /**
     * @param mixed $result то, что вернёт execute()
     */
    public function __construct($result)
    {
        $this->result = $result;
    }

    public function where(array $where): self
    {
        $this->whereArgs[] = $where;
        return $this;
    }

    /** @return mixed */
    public function execute()
    {
        return $this->result;
    }
}
