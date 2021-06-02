@extends('layouts.app')
@section('content')
    <div class="container">
        @include('layouts.common-navbar')

        <div class="container">
            <table class="table table-striped" id="">
                <tbody id="table-body">
                    @foreach ($memos as $memo)
                        <tr>
                            <td>{{ $memo->name }}</td>
                            <td>{{ $memo->category->name }}</td>
                            <td>{{ $memo->description }}</td>
                            <td>
                                <a href="{{ route('restore-memo', ['id' => $memo->id]) }}" class="text-primary">
                                    <i class="fas fa-undo"></i>
                                </a>
                                <a href="{{ route('force-delete-memo', ['id' => $memo->id]) }}" class="text-danger ml-1">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

@endsection
