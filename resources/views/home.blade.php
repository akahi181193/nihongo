@extends('layouts.app')

@section('content')
<div class="container">
    {{-- @include('layouts.common-navbar') --}}

    @if (\Session::has('success'))
    <div class="success-alert alert alert-success alert-dismissible fade show"
        style="position: fixed; top: 100px; right: 0;" role="alert">
        <span>{!! \Session::get('success') !!}</span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="row mt-3">
        <div class="categories col-md-3">
            <div class="w-100">
                <button type="button" class="btn btn-primary btn-block w-100" data-toggle="modal"
                    data-target="#category-modal">
                    <i class="far fa-plus-square"></i>
                    <span class="ml-1">{{__('new category')}}</span>
                </button>
            </div>
            <style>
                #list-category {

                    height: calc(100vh - 55px - 37px - 5rem - 37px);
                    overflow-y: auto;

                    /* @media screen and (max-width: 768px) {} */

                }

                #list-category::-webkit-scrollbar {
                    display: none;
                }

                .custom-list-item {
                    color: #212529;
                }

                .list-group-item .action-group {
                    display: none !important;
                }

                .list-group-item:hover .action-group {
                    display: flex !important;
                }

                .custom-list-item-active {
                    color: #fff;
                }

                .description {
                    width: 150px;
                    white-space: nowrap;
                    text-overflow: ellipsis;
                    overflow-x: hidden;
                }

                .custom-link {
                    cursor: pointer;
                }

            </style>
            <div id="list-category" class="list-group mt-3">
                @foreach ($categories as $item)
                <div class="list-group-item list-group-item-action {{ request()->get('category') == $item->id ? 'active' : '' }}"
                    style="display: flex; flex-flow: row;">
                    <a class="col-8" style="text-decoration: none;" href="/home?category={{ $item->id }}">
                        <div class="custom-list-item{{ request()->get('category') == $item->id ? '-active' : '' }}">
                            {{ $item->name }}
                        </div>
                    </a>
                    <div class="action-group btn-group col-4"
                        style="display: flex; align-items: center; padding: 0; justify-content: flex-end">
                        <i style="cursor: pointer" onclick="editCategory({{ $item->id }})" data-toggle="modal"
                            data-target="#edit-category-modal" class="fas fa-edit"></i>
                        <a style="color: #e3342f; margin-left: 4px;"
                            href="{{ route('categoryDelete', ['id' => $item->id]) }}">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="memos col-md-9 mt-sm-3 mt-3 mt-md-0 mt-lg-0">
            <form class="form-inline w-100 justify-content-center" method="GET" action="{{ url('/home') }}">
                <input class="form-control col-md-6 col-sm-8 col-xs-11" value="{{ request()->get('keyword') }}"
                    type="text" name="keyword" placeholder="{{__('title')}}" aria-label="Search">
                <button
                    class="btn btn-outline-success ml-0 ml-sm-1 ml-md-1 ml-lg-1 col-md-2 col-sm-3 col-xs-3 mt-1 mt-sm-0 mt-md-0 mt-lg-0"
                    type="submit">{{__('search')}}</button>
            </form>


            <table class="table table-striped mt-3 table-hover}_>+]" id="">
                <thead>
                    <tr>
                        <th scope="col">
                            <div class="">{{__('title')}}</div>
                        </th>
                        <th scope="col">{{__('a part of speech')}}</th>
                        @if (!request()->get('category'))
                        <th scope="col">{{__('category')}}</th>
                        @endif
                        <th scope="col">{{__('content')}}</th>
                        <th scope="col">{{__('images')}}</th>
                        <th scope="col">{{__('audio')}}</th>
                        <th scope="col">
                            <a class="btn btn-outline-primary" data-toggle="modal" data-target="#add-memo-modal">
                                <i class="far fa-plus-square"></i>
                                <span class="ml-1">{{__('add')}}</span>
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    @foreach ($memos as $memo)
                    <tr>
                        <td scope="row" onclick="onEditButton({{ $memo->id }})" data-toggle="modal"
                            data-target="#edit-memo-modal">
                            <div class="custom-link">{{ $memo->name }}</div>
                        </td>
                        <td scope="row">
                            <div class="wordclass">
                                {{ $memo->wordclass }}
                            </div>
                        </td>
                        @if (!request()->get('category'))
                        <td scope="row" style="white-space: nowrap">
                            {{ !empty($memo->category->name) ? $memo->category->name : ' 削除しました。' }}
                        </td>
                        @endif

                        <td scope="row">
                            <div class="description">
                                {{ $memo->description }}
                            </div>
                        </td>
                        <td scope="row">
                            @isset($memo->images)
                            <a href="{{ asset('storage/images/memos/' . $memo->images) }}" data-lightbox="roadtrip">
                                <img width="responssive" height="50px"
                                    src="{{ asset('storage/images/memos/' . $memo->images) }}"
                                    enctype="multipart/form-data">
                            </a>
                            @endisset
                        </td>
                        <td scope="row">
                            <a class="custom-link" data-toggle="modal" data-target=".bd-example-modal-sm">
                                <i class="fas fa-play-circle"></i>
                            </a>
                        </td>

                        <!-- audiomodal -->
                        <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog"
                            aria-labelledby="mySmallModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    @isset($memo->audio)
                                    <audio width="responsive" height="50px" controls>
                                        <source src="{{ asset('storage/audio/memos/' . $memo->audio) }}"
                                            type="audio/ogg">
                                    </audio>
                                    @endisset
                                </div>
                            </div>
                        </div>

                        <!-- Edit and Delete button on lick -->

                        <!-- edit button  -->
                        <td>
                            <div>
                                <a onclick="onEditButton({{ $memo->id }})" class="text-primary custom-link"
                                    data-toggle="modal" data-target="#edit-memo-modal">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <!-- delete button  -->
                                <a class="text-danger ml-5 custom-link" data-toggle="modal" data-target="#delete-Modal">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    {{-- Delete Modal --}}
                    <div class="modal fade custom-link" id="delete-Modal" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">{{__('delete')}}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    {{__('do you want to delete this?')}}<br>
                                    {{ $memo->name }}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('cancle')}}</button>
                                    <a href="{{ route('delete-memo', ['id' => $memo->id]) }}"><button type="button"
                                            class="btn btn-danger">{{__('delete')}}</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4">
                            <div class="row justify-content-center">
                                {{ $memos->onEachSide(5)->links() }}
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>


    <!-- Modal add-->
    <div class="modal fade" id="add-memo-modal" tabindex="-1" role="dialog" aria-labelledby="category-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="category-modalLabel">{{__('Memo new registration')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="memo-add-form" action="{{ route('storeMemo') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label for="category-id" class="col-md-4 col-form-label">{{ __('category') }}</label>
                            <div class="col-md-12">
                                <select name="category_id" id="category-id" class="form-control" required>
                                    <option value="">{{__('select a category')}}</option>
                                    @foreach ($categories as $category)
                                    <option @if (request()->get('category') == $category->id) selected="selected" @endif
                                        value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="name" class="col-md-4 col-form-label">{{__('a part of speech') }}</label>
                                <select name="wordclass" id="wordclass" class="form-control">
                                    <option value="名詞">{{__('noun') }}</option>
                                    <option value="動詞">{{__('verb') }}</option>
                                    <option value="形容詞">{{__('adjective') }}</option>
                                    <option value="副詞">{{__('adverb') }}</option>
                                    <option value="接続詞">{{__('conjunction') }}</option>
                                    <option value="感嘆詞">{{__('interjection') }}</option>
                                    <option value="助動詞">{{__('Auxiliary verb')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label">{{__('title') }}</label>
                            <div class="col-md-12">
                                <input type="text" name="name" id="name" required class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label">{{__('content') }}</label>
                            <div class="col-md-12">
                                <textarea name="description" id="description" rows="3" required
                                    class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="edit-image" class="col-md-4 col-form-label">{{ __('images') }}</label>
                            <input type="file" name="images" id="edit-image" class="form-control">
                        </div>

                        <div class="form-group row">
                            <label for="edit-audio" class="col-md-4 col-form-label">{{__('audio') }}</label>
                            <input type="file" name="audio" id="edit-audio" class="form-control">
                        </div>

                        <div class="row justify-content-center">
                            <button style="min-width: 100px" type="submit" class="btn btn-primary">{{__('add') }}</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- end modal add -->

    <!-- start edit Modal -->
    <div class="modal fade" id="edit-memo-modal" tabindex="-1" role="dialog" aria-labelledby="category-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="category-modalLabel">{{__('edit') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="edit-add-form" method="POST" action="/memos/" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label for="category-id" class="col-md-4 col-form-label">{{ __('category') }}</label>
                            <div class="col-md-12">
                                <select name="category_id" id="edit-category-id" class="form-control" required>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label">{{__('title') }}</label>
                            <div class="col-md-12">
                                <input type="text" name="name" id="edit-name" required class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="name" class="col-md-4 col-form-label">{{__('a part of speech') }}</label>
                                <select name="wordclass" id="wordclass" class="form-control">
                                <option value="名詞">{{__('noun') }}</option>
                                    <option value="動詞">{{__('verb') }}</option>
                                    <option value="形容詞">{{__('adjective') }}</option>
                                    <option value="副詞">{{__('adverb') }}</option>
                                    <option value="接続詞">{{__('conjunction') }}</option>
                                    <option value="感動詞">{{__('interjection') }}</option>
                                    <option value="助動詞">{{__('Auxiliary verb')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label">{{ __('content') }}</label>
                            <div class="col-md-12">
                                <textarea name="description" id="edit-description" rows="3" required
                                    class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="edit-image" class="col-md-4 col-form-label">{{ __('images') }}</label>
                            <input type="file" name="images" id="edit-image" class="form-control">
                        </div>

                        <div class="form-group row">
                            <label for="edit-audio" class="col-md-4 col-form-label">{{ __('audio') }}</label>
                            <input type="file" name="audio" id="edit-audio" class="form-control">
                        </div>

                        <div class="row justify-content-center">
                            <button style="min-width: 100px" type="submit" class="btn btn-primary">{{ __('update') }}</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- end edit modal-->

    <!-- modal category add -->

    <div class="modal fade" id="category-modal" tabindex="-1" role="dialog" aria-labelledby="category-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="category-modalLabel">{{ __('add new category') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('storecategory') }}" method="POST">
                        @method('POST')
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">{{ __('category name') }}</label>
                            <input type="text" name="name" class="form-control" id="recipient-name" required
                                class="form-control">
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('add') }}</button>
                </div>
            </div>
            </form>
        </div>

    </div>

    <!-- edit category modal -->

    <div class="modal fade" id="edit-category-modal" tabindex="-1" role="dialog" aria-labelledby="category-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="category-modalLabel">{{ __('add new category') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editCategory" action="/editCategory/" enctype="multipart/form-data" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">{{ __('category name') }}</label>
                            <input type="text" name="name" id="nameCategory" class="form-control">
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('update') }}</button>
                </div>
            </div>
            </form>
        </div>

    </div>


    <!-- end category -->



</div>
</div>


<!-- endmodal -->
@endsection

@section('scripts')
<!-- handle alert queue -->
<script>
    window.onload = function () {
        const alertQueue = localStorage.getItem('alert-queue');
        if (alertQueue) {
            const alertEl = document.createElement('div');
            $(alertEl).addClass('success-alert alert alert-success alert-dismissible fade show');
            $(alertEl).attr('style', 'position: fixed; top: 100px; right: 0; z-index: 10;');
            $(alertEl).attr('role', 'alert');
            $(alertEl).html(
                `<span>${alertQueue}</span> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>`
            );

            $('.container')[0].append(alertEl);

            localStorage.setItem('alert-queue', '');
        }
    }

</script>

<script>
    setTimeout(() => {
        $('.success-alert').remove();
    }, 1000);

</script>

<!-- edit scripts -->
<script>
    function onEditButton(id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //res biếm callback function

        $.ajax('/memos/' + id, {
            success: (res) => {
                $('#edit-category-id').val(res.category_id);
                $('#edit-name').val(res.name);
                $('#edit-wordclass').val(res.wordclass);
                $('#edit-description').val(res.description);
                $('#edit-add-form').attr('action', '/memos/' + id);
            },
            error: (error) => {

            }
        });

    }

</script>
<script>
    function editCategory(id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //res biếm callback function

        $.ajax('/categories/' + id, {
            success: (res) => {
                $('#nameCategory').val(res.name);
                $('#editCategory').attr('action', '/categories/' + id);
            },
            error: (error) => {

            }
        });

    }

</script>



@endsection
