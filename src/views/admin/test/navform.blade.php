@extends('adm_theme::layouts.app')
@section('page_heading','test input form')
@section('section')
@include('backend::includes.flash')
@include('backend::includes.components')
{{-- Form::open(['route' => $routename]) --}}
{{ Form::model($row,['route' => $routename]) }}
@foreach($components as $comp)
<hr style="clear:both"/>
	name : <b>{{ $comp }}</b><br/>
	value: {{ $row->$comp}}<br/>
	{!! Form::$comp([]) !!}<br/>
@endforeach

{{ Form::bsSubmit('vai') }}
{!! Form::close() !!}
@endsection