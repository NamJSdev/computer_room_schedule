@extends('layouts.app')

@section('title', 'Home')

@section('content')
{{-- Calendar --}}
@include('components.calendar')
{{-- End Calendar --}}
@endsection
