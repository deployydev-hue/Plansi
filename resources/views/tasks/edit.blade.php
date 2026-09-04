@extends('layouts.app')

@section('title', 'Edit Task | PLANSI')

@section('content')
    @include('tasks.partials.task-form', [
        'mode' => 'edit',
    ])
@endsection
