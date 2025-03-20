<?php

namespace MvcEventCallback;

use mrblue\mvc\MvcEvent;

abstract class AbstractMvcEventCallback {

    abstract function run(MvcEvent $MvcEvent): void;

    function getCallback(): \closure {
        $Obj = $this;
        return function (MvcEvent $MvcEvent) use ($Obj) {
            return $Obj->run($MvcEvent);
        };
    }
}
