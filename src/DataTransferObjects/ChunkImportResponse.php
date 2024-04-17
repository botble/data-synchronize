<?php

namespace Botble\DataSynchronize\DataTransferObjects;

class ChunkImportResponse extends ChunkResponse
{
    public function __construct(
        public int $offset,
        public int $count,
        public int $imported,
    ) {
        parent::__construct($offset, $count);
    }
}
