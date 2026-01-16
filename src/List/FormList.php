<?php

namespace List;

use \Model\Form as Model;

/**
 * @method ?Model get( int $id )
 * @method ?Model search( string $field , mixed $is , bool $strict = false )
 * @method self filter( string $field , mixed $is , bool $strict = true )
 * @method self filterIn( string $field , array $in , bool $strict = true )
 * @method Model current()
 * @method Model offsetGet()
 */

class FormList extends AbstractList {

    function __construct(array $items = []) {
        parent::__construct(Model::class, $items);
    }
}
