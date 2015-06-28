<?php

namespace ESJ\Earley\Recognizer\Rule\Entry;

class Eof extends LiteralList
{
    public function __construct()
    {
        parent::__construct([
            new Literal(chr(0))
        ]);
    }

    public function __toString()
    {
        return '\0';
    }
}
