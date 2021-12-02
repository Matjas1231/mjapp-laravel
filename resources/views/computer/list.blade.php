@extends('layouts.layout')
@section('title')Lista komputerów @endsection

@section('page-heading')Lista komputerów @endsection

@section('content')


<table class="table table-striped">
    <thead>
        <tr>
            <th>Lp.</th>
            <th>Marka</th>
            <th>Model</th>
            <th>Typ</th>
            <th>Adres IP</th>
            <th>Nazwa komputera</th>
            <th>Pracownik</th>
            <th>Akcja</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($computers as $computer)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $computer->brand }}</td>
            <td>{{ $computer->model }}</td>
            <td>{{ $computer->computerType->type }}</td>
            <td>{{ $computer->ip_address }}</td>
            <td>{{ $computer->computer_name }}</td>
            {{-- <td>{{ $computer->worker->name }} {{ $computer->worker->surname }}</td> --}}
            @if (!empty($computer->worker->name))
                <td>{{ $computer->worker->name }} {{ $computer->worker->surname }}</td>
            @else
              <td>
                <b>Nieprzypisany</b>
              </td>
            @endif
            <td>
                <a href="{{ route('computer.show', ['computerId' => $computer->id]) }}">Szczegóły</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


@endsection
