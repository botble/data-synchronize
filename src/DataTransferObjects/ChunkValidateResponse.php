<?php

namespace Botble\DataSynchronize\DataTransferObjects;

class ChunkValidateResponse extends ChunkResponse
{
    public function __construct(
        public int $offset,
        public int $count,
        public int $total,
        public string $fileName,
        public array $errors = [],
    ) {
        parent::__construct($offset, $count);
    }
}
