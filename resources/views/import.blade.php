@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    @include('packages/data-synchronize::partials.importer')
@stop
