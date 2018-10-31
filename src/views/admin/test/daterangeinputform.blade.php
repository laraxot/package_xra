@extends('adm_theme::layouts.app')
@section('page_heading','test date range input form')
@section('section')
@include('backend::includes.flash')
@include('backend::includes.components')
{{-- Form::open(['route' => $routename]) --}}
{{ Form::model($row,['route' => $routename]) }}
@foreach($components as $comp)
<hr style="clear:both"/>
	name : <b>{{ $comp }}</b><br/>
	@php
		$k0=$comp.'_start';
		$k1=$comp.'_end';
	@endphp
	start - end : {{ $row->$k0 }} -  {{ $row->$k1 }}
	{{ Form::$comp($k0,$k1) }}<br/>
@endforeach

{{ Form::bsSubmit('vai') }}
{!! Form::close() !!}
@endsection