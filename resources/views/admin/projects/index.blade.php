@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="mt-2">Lista Progetti</h1>
            <span class="fs-4 add-btn d-flex align-items-center justify-content-center text-white me-5"><a
                    href="{{ route('admin.projects.create') }}"><i class="fa-solid fa-plus"></i></a></span>
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <p>Clicca il nome per avere maggiori informazioni</p>
            <p class="me-1">Aggiungi progetto</p>
        </div>

        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        <table class="table table-striped table-hove ms-body">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $project)
                    <tr>
                        <th scope="row">{{ $project->id }}</th>
                        <td><a
                                href="{{ route('admin.projects.show', ['project' => $project->slug]) }}">{{ $project->title }}</a>
                        </td>
                        <td>{{ $project->description }}</td>
                        <td><a class="btn btn-primary"
                                href="{{ route('admin.projects.edit', ['project' => $project->slug]) }}">Modifica</a></td>
                        <td>
                            <form action="{{ route('admin.projects.destroy', ['project' => $project->id]) }}"
                                method="POST">
                                @method('DELETE')
                                @csrf
                                <button class="btn btn-danger">Elimina</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
