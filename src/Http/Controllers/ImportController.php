<?php

namespace Botble\DataSynchronize\Http\Controllers;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\DataSynchronize\Http\Requests\DownloadTemplateRequest;
use Botble\DataSynchronize\Http\Requests\ImportRequest;
use Botble\DataSynchronize\Importer\Importer;
use Exception;
use Illuminate\Support\Arr;

abstract class ImportController extends BaseController
{
    abstract protected function getImporter(): Importer;

    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('core/base::layouts.tools'))
            ->add(trans('packages/data-synchronize::data-synchronize.tools.export_import_data'), route('data-synchronize.tools.data-synchronize'));
    }

    public function index()
    {
        $this->pageTitle($this->getImporter()->getHeading());

        return $this->getImporter()->render();
    }

    public function validateData(ImportRequest $request)
    {
        try {
            $result = $this->getImporter()->validate(
                $request->input('file_name'),
                $request->input('offset'),
                $request->input('limit'),
            );

            return $this
                ->httpResponse()
                ->setMessage(Arr::pull($result, 'message'))
                ->setData($result);
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
            $result = $this->getImporter()->import(
                $request->input('file_name'),
                $request->input('offset'),
                $request->input('limit'),
            );

            return $this
                ->httpResponse()
                ->setMessage(Arr::pull($result, 'message'))
                ->setData($result);
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
}
