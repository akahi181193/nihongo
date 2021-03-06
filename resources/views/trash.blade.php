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
                            <th colspan="1" scope="col">{{__('category')}}</th>
                            <th colspan="1" scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                        <tr>
                            <th scope="row">{{ $category->name }}</th>

                            <td>
                                <a href="{{ route('restore-category', ['id' => $category->id]) }}" class="text-primary">
                                    <i class="fas fa-undo"></i>
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
                            <th scope="col">{{__('title')}}</th>
                            <th scope="col">{{__('a part of speech')}}</th>
                            <th scope="col">{{__('category')}}</th>
                            <th scope="col">{{__('content')}}</th>
                            <th scope="col">{{__('images')}}</th>
                            <th scope="col">{{__('audio')}}</th>
                            <th scope="col">
                        </tr>
                        @foreach ($memos as $memo)
                        <tr>
                            <td>
                                <div class="fixmenu">{{ $memo->name }}</div>
                            </td>
                            <td>
                                <div class="fixmenu">{{ !empty($memo->category->name) ? $memo->category->name:'' }}
                                </div>
                            </td>
                            <td>
                                <div class="fixmenu">{{ $memo->description }}</div>
                            </td>
                            <td>
                                @isset($memo->images)
                                <img width="responssive" height="50px"
                                    src="{{ asset('storage/images/memos/' . $memo->images) }}"
                                    enctype="multipart/form-data" alt="??????">
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
                                <a href="{{ route('force-delete-memo', ['id' => $memo->id]) }}"
                                    class="text-danger ml-5">
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
