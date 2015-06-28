<?php

namespace ESJ\Earley\Recognizer\Rule\Entry;

class Eof implements Entry
{
    public function __toString()
    {
        return '\0';
    }
}
