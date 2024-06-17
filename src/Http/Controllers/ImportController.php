<?php

namespace Botble\DataSynchronize\Http\Controllers;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\DataSynchronize\Http\Requests\DownloadTemplateRequest;
use Botble\DataSynchronize\Http\Requests\ImportRequest;
use Botble\DataSynchronize\Importer\Importer;
use Exception;
use Illuminate\Http\Request;

abstract class ImportController extends BaseController
{
    abstract protected function getImporter(): Importer;

    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('core/base::layouts.tools'))
            ->add(trans('packages/data-synchronize::data-synchronize.tools.export_import_data'), route('tools.data-synchronize'));
    }

    public function index()
    {
        $this->pageTitle($this->getImporter()->getHeading());

        return $this->getImporter()->render();
    }

    public function validateData(ImportRequest $request)
    {
        try {
            $response = $this->prepareImporter($request)->validate(
                $request->input('file_name'),
                $request->input('offset'),
                $request->input('limit'),
            );

            $message = $response->getFromOffset() <= $response->getNextOffset() ? trans('packages/data-synchronize::data-synchronize.import.validating_message', [
                'from' => number_format($response->getFromOffset()),
                'to' => number_format($response->getNextOffset()),
            ]) : null;

            $dataToResponse = [
                'total' => $response->total,
                'offset' => $response->offset,
                'count' => $response->count,
                'file_name' => $response->fileName,
                'errors' => $response->errors,
            ];

            $httpResponse = $this->httpResponse();

            if ($message) {
                $httpResponse->setMessage($message);
            }

            return $httpResponse
                ->setData($dataToResponse);
        } catch (Exception $e) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage($e->getMessage());
        }
    }

    public function import(ImportRequest $request)
    {
        if (BaseHelper::hasDemoModeEnabled()) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(trans('core/base::system.disabled_in_demo_mode'));
        }

        try {
            $response = $this->prepareImporter($request)->import(
                $request->input('file_name'),
                $request->input('offset'),
                $request->input('limit'),
            );

            $from = $response->getFromOffset();
            $to = $response->getNextOffset();

            $total = $request->integer('total') + $response->imported;

            if ($from <= $to) {
                $message = $this->getImporter()->getImportingMessage($from, $to);
            } else {
                if ($total > 0) {
                    $message = $this->getImporter()->getDoneMessage($total);
                } else {
                    $message = trans('packages/data-synchronize::data-synchronize.import.no_data_message', [
                        'label' => $this->getImporter()->getLabel(),
                    ]);
                }
            }

            return $this
                ->httpResponse()
                ->setMessage($message)
                ->setData([
                    'offset' => $response->offset,
                    'count' => $response->count,
                    'total' => $total,
                    'failures' => $response->failures,
                ]);
        } catch (Exception $e) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage($e->getMessage());
        }
    }

    public function downloadExample(DownloadTemplateRequest $request)
    {
        if (BaseHelper::hasDemoModeEnabled()) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(trans('core/base::system.disabled_in_demo_mode'));
        }

        return $this->getImporter()->downloadExample($request->input('format'));
    }

    protected function prepareImporter(Request $request): Importer
    {
        return $this->getImporter();
    }
}
