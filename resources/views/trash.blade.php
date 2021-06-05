@extends('layouts.app')
@section('content')
    <div class="container">
        @include('layouts.common-navbar')

        <div class="container">
        <div class="row">
            <div class="col-md-3">
                <table class="table table-striped">
                    <thead>
                        <tr>
                        <th colspan="1"scope="col">カテゴリ</th>
                        <th colspan="1"scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($category as $categorys)
                            <tr>
                                <th scope="row">{{ $categorys->name }}</th>
                            
                                <td>
                                    <a href="{{ route('restore-memo', ['id' => $categorys->id]) }}" class="text-primary">
                                        <i class="fas fa-undo"></i>
                                    </a>
                                    <a href="{{ route('force-delete-memo', ['id' => $categorys->id]) }}" class="text-danger ml-5">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-9">
                <table class="table table-striped" id="">
                    <tbody id="table-body">
                    <tr>
                        <th scope="col">タイトル</th>
                        <th scope="col">カテゴリ</th>
                        <th scope="col">内容</th>
                        <th scope="col">写真</th>
                        <th scope="col">音声</th>
                        <th scope="col">
                    </tr>
                        @foreach ($memos as $memo)
                            <tr>
                                <td>{{ $memo->name }}</td>
                                <td>{{ !empty($memo->category->name) ? $memo->category->name:'' }}</td>
                                <td>{{ $memo->description }}</td>
                                <td> 
                                @isset($memo->images)
                                        <img width="responssive" height="50px"
                                        src="{{ asset('storage/images/memos/' . $memo->images) }}"
                                        enctype="multipart/form-data" alt="写真">
                                @endisset</td>
                                <td>
                                    <a class="" data-toggle="modal" data-target=".bd-example-modal-sm">
                                        <i class="fas fa-play-circle"></i>
                                    </a>
                                    </td>
                                    <!-- audiomodal -->
                                    <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog"
                                        aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <audio width="responssive" height="50px" controls>
                                                    <source src="{{ asset('storage/audio/memos/' . $memo->audio) }}"
                                                        type="audio/ogg">
                                                </audio>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- endaudiomodal -->
                                <td>
                                    <a href="{{ route('restore-memo', ['id' => $memo->id]) }}" class="text-primary">
                                        <i class="fas fa-undo"></i>
                                    </a>
                                    <a href="{{ route('force-delete-memo', ['id' => $memo->id]) }}" class="text-danger ml-5">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </div>

@endsection
