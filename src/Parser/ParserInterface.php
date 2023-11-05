<?php

namespace App\Parser;

use App\Enum\FileType;
use App\Model\Message;

interface ParserInterface
{
    public function getType(): FileType;

    /**
     * @return Message[]
     */
    public function parse(\SplFileObject $file): array;
}
