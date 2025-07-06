<?php

namespace Botble\DataSynchronize\Http\Requests;

use Botble\Support\Http\Requests\Request;

class ExportRequest extends Request
{
    public function rules(): array
    {
        return [
            'format' => ['required', 'string', 'in:csv,xlsx'],
            'columns' => ['nullable', 'array'],
            'columns.*' => ['required', 'string'],
            'chunk_size' => ['nullable', 'integer', 'min:50', 'max:5000'],
            'use_chunked_export' => ['nullable'],
            'include_variations' => ['nullable'],
            'optimize_memory' => ['nullable'],
            'use_streaming' => ['nullable'],
            'stream' => ['nullable', 'boolean'],
        ];
    }
}
