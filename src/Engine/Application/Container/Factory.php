<?php namespace Engine\Application\Container;

use Engine\DI\HasInjection;
use Engine\Event\HasEventsManager;
use Engine\Engine;
use Engine\DI\ServiceRegister;

class Factory implements Contract
{
    use HasInjection, HasEventsManager;

    /**
     * Registers
     *
     * @var array
     */
    protected $registers = [];

    public function addRegister($name)
    {
        // TODO: Implement addRegister() method.
        if (!$this->hasRegister($name)) {
            $this->registers[] = $name;
        }

        return $this;
    }

    public function getRegisters()
    {
        // TODO: Implement getRegisters() method.
        return is_null($this->registers) || !is_array($this->registers) ? [] : $this->registers;
    }

    public function hasRegister($name)
    {
        // TODO: Implement hasRegister() method.
        return in_array($name, $this->registers);
    }

    public function removeRegister($name)
    {
        // TODO: Implement removeRegister() method.
        foreach ($this->getRegisters() as $i => $register) {
            if (is_string($register) && $register === $name) {
                unset($this->registers[$i]);
                break;
            }
        }

        return $this;
    }

    public function removeRegisters()
    {
        // TODO: Implement removeRegisters() method.
        $this->registers = [];

        return $this;
    }

    public function setRegisters($registers = array())
    {
        // TODO: Implement setRegisters() method.
        if (is_array($registers)) {
            foreach ($registers as $name) {
                $this->addRegister($name);
            }
        }

        return $this;
    }

    public function makeRegisters()
    {
        // TODO: Implement makeRegisters() method.
        if (count($this->registers)) {
            $di = di();
            foreach ($this->registers as $i => $name) {
                if (is_string($name)) {
                    $register = Engine::newInstance($name);
                    if ($register instanceof ServiceRegister) {
                        $register->setDI($di);
                        $this->registers[$i] = $register;
                    } else {
                        throw new InvalidInstanceException("$name must implement " . ServiceRegister::class);
                    }
                }
            }
        }

        return $this;
    }
}