<?php

namespace App\Service;

use App\Exception\SearchException;
use App\Model\Message;
use App\Model\Report\SearchableInterface;

readonly class SearchService
{
    public function __construct(private iterable $searchables)
    {
    }

    /**
     * @throws SearchException
     */
    public function search(Message $message): SearchableInterface
    {
        /** @var SearchableInterface $searchable */
        foreach ($this->searchables as $searchable) {
            if (
                $searchable->search(
                    $message->getDescription()
                )
            ) {
                return new $searchable();
            }
        }

        throw SearchException::fromMessage($message->getDescription());
    }
}
