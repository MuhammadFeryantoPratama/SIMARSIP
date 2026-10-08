@extends('layouts.guest')

@section('title', 'SIMARSIP - Sistem Manajemen Arsip Digital')

@section('content')
    @include('components.hero')
    @include('components.trusted-by')
    @include('components.features')
    @include('components.preview')
    @include('components.pricing')
    @include('components.cta')
@endsection
