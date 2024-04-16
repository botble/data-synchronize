<?php

namespace Botble\DataSynchronize\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Media\Chunks\Exceptions\UploadMissingFileException;
use Botble\Media\Chunks\Handler\DropZoneUploadHandler;
use Botble\Media\Chunks\Receiver\FileReceiver;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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

    protected function saveFile(UploadedFile $file): BaseHttpResponse
    {
        $fileName = $this->createFilename($file);
        $destination = Storage::disk('local')->path('uploads');

        $file->move($destination, $fileName);

        if (! in_array($file->getClientOriginalExtension(), ['csv', 'xlsx'])) {
            File::delete("$destination/$fileName");

            return $this
                ->httpResponse()
                ->setError()
                ->setMessage('File type is not supported.');
        }

        return $this
            ->httpResponse()
            ->setMessage(sprintf('File %s has been uploaded successfully. Start validating data...', $file->getClientOriginalName()))
            ->setData([
                'file_name' => $fileName,
            ]);
    }

    protected function createFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename = str_replace(".$extension", '', $file->getClientOriginalName());
        $filename .= sprintf('-%s.%s', md5(uniqid()), $extension);

        return $filename;
    }
}
