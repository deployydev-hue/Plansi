@extends('layouts.app')

@section('title', 'Create Task | PLANSI')

@section('content')
    @include('tasks.partials.task-form', [
        'mode' => 'create',
        'task' => null,
    ])
@endsection
