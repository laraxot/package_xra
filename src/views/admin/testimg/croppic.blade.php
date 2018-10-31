@extends('adm_theme::layouts.app')
@section('page_heading','test chunk upload form')
@section('section')
@include('backend::includes.flash')
@include('backend::includes.components')
{{-- Form::open(['route' => $routename]) --}}
{{ Form::model($row,['route' => $routename]) }}
{{ Form::bsImgCroppic('testimg') }}<br/>

{{ Form::bsImgUnisharp('testimg') }}<br/>

{{-- Form::selectBank("bank_name") --}}

{{ Form::bsSubmit('vai') }}
{!! Form::close() !!}
@endsection