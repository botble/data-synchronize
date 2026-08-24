<?php

namespace Botble\DataSynchronize\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Media\Chunks\Exceptions\UploadMissingFileException;
use Botble\Media\Chunks\Handler\DropZoneUploadHandler;
use Botble\Media\Chunks\Receiver\FileReceiver;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UploadController extends BaseController
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'max:1024'],
        ]);

        $receiver = new FileReceiver('file', $request, DropZoneUploadHandler::class);

        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }

        $save = $receiver->receive();

        if ($save->isFinished()) {
            return $this->saveFile($save->getFile());
        }

        $handler = $save->handler();

        return $this
            ->httpResponse()
            ->setData([
                'done' => $handler->getPercentageDone(),
            ]);
    }

    protected function saveFile(UploadedFile $fileUpload): BaseHttpResponse
    {
        $validator = Validator::make(['file' => $fileUpload], [
            'file' => [
                'required',
                'mimetypes:' . implode(',', config('packages.data-synchronize.data-synchronize.mime_types')),
                // The allowed mime types include text/plain, which any source file
                // sniffs as - so pin the extension as well, not just the mime type.
                'extensions:' . implode(',', config('packages.data-synchronize.data-synchronize.extensions')),
            ],
        ]);

        if ($validator->fails()) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage($validator->errors()->first());
        }

        $fileName = $this->createFilename($fileUpload);
        $disk = Storage::disk(config('packages.data-synchronize.data-synchronize.storage.disk'));
        $storagePath = config('packages.data-synchronize.data-synchronize.storage.path');
        $destination = $disk->path($storagePath);

        foreach ($disk->files($storagePath) as $existingFile) {
            $disk->delete($existingFile);
        }

        $fileUpload->move($destination, $fileName);

        return $this
            ->httpResponse()
            ->setMessage(trans('packages/data-synchronize::data-synchronize.import.uploaded_message', [
                'file' => $fileUpload->getClientOriginalName(),
            ]))
            ->setData([
                'file_name' => $fileName,
            ]);
    }

    protected function createFilename(UploadedFile $file): string
    {
        // getClientOriginalName() is attacker-controlled and is about to be used as a
        // path segment, so keep only the basename and strip anything that is not safe
        // in a file name before appending our own unique suffix.
        $extension = Str::lower($file->getClientOriginalExtension());
        $original = pathinfo(basename($file->getClientOriginalName()), PATHINFO_FILENAME);
        $name = preg_replace('/[^\w.-]+/u', '-', $original);
        // Collapse runs of dots: a name containing ".." is refused by ImportRequest,
        // which would leave the uploaded file impossible to import.
        $name = Str::limit(preg_replace('/\.{2,}/', '.', $name), 100, '');

        return sprintf('%s-%s.%s', trim($name, '-.') ?: 'import', md5(uniqid()), $extension);
    }
}
