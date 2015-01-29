<?php

namespace Dacorp\ExtraBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class LangPreferenceEvent extends Event
{

    protected $preferredLang;

    /**
     * @param mixed $preferredLang
     */
    public function setPreferredLang($preferredLang)
    {
        $this->preferredLang = $preferredLang;
    }

    /**
     * @return mixed
     */
    public function getPreferredLang()
    {
        return $this->preferredLang;
    }

    public function __construct($newLang)
    {
        $this->preferredLang = $newLang;
    }
}
