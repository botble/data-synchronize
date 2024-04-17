<?php

namespace Botble\DataSynchronize\DataTransferObjects;

class ChunkResponse
{
    public function __construct(
        public int $offset,
        public int $count,
    ) {
    }

    public function getFrom(): int
    {
        return $this->offset + 1;
    }

    public function getTo(): int
    {
        return $this->offset + $this->count;
    }
}
