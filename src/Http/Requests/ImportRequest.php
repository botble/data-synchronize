<?php

namespace Botble\DataSynchronize\Http\Requests;

use Botble\Support\Http\Requests\Request;

class ImportRequest extends Request
{
    public function rules(): array
    {
        return [
            // A bare file name only. This value is concatenated onto the import storage
            // path, so anything with a directory separator would let the importer read
            // or move files elsewhere on the disk.
            'file_name' => ['required', 'string', 'max:255', 'regex:/^[^\/\\\\]+$/', 'not_regex:/\.\./'],
            'offset' => ['required', 'integer'],
            'limit' => ['required', 'integer'],
            'total' => ['nullable', 'integer'],
        ];
    }
}
