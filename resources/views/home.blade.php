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
                        <span class="ml-1">新規 カテゴリ</span>
                    </button>
                </div>
                <style>
                    #list-category {

                        height: calc(100vh - 55px - 37px - 5rem - 37px);
                        overflow-y: auto;

                        @media screen and (max-width: 768px) {}

                    }

                    #list-category::-webkit-scrollbar {
                        display: none;
                    }

                </style>
                <div id="list-category" class="list-group mt-3">
                    @foreach ($categories as $item)
                        <div class="list-group-item list-group-item-action" style="display: flex; flex-flow: row;">
                            <div class="col-10">
                                <a class="{{ request()->get('category') == $item->id ? 'active' : '' }}"
                                    href="/home?category={{ $item->id }}">{{ $item->name }}</a>
                            </div>
                            <div class="col-2">
                                <a href="{{ route('categoryDelete', ['id' => $item->id]) }}">
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
                        type="text" name="keyword" placeholder="タイトル" aria-label="Search">
                    <button
                        class="btn btn-outline-success ml-0 ml-sm-1 ml-md-1 ml-lg-1 col-md-2 col-sm-3 col-xs-3 mt-1 mt-sm-0 mt-md-0 mt-lg-0"
                        type="submit">検索</button>
                </form>


                <table class="table table-striped mt-3" id="">
                    <thead>
                        <tr>
                            <th scope="col">タイトル</th>
                            <th scope="col">カテゴリ</th>
                            <th scope="col">内容</th>
                            <th scope="col">写真</th>
                            <th scope="col">音声</th>
                            <th scope="col">

                                <a class="btn btn-outline-primary" data-toggle="modal" data-target="#add-memo-modal">
                                    <i class="far fa-plus-square"></i>
                                    <span class="ml-1">追加</span>
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        @foreach ($memos as $memo)
                            <tr>
                                <td scope="row">{{ $memo->name }}</td>
                                <td>{{ !empty($memo->category->name) ? $memo->category->name : ' 削除しました。' }}</td>
                                <td>{{ $memo->description }}</td>
                                <td>
                                    @isset($memo->images)
                                        <img width="responssive" height="50px"
                                            src="{{ asset('storage/images/memos/' . $memo->images) }}"
                                            enctype="multipart/form-data">
                                    @endisset
                                </td>
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

                                <!-- Edit and Delete button on lick -->

                                <!-- edit button  -->
                                <td>
                                    <a onclick="onEditButton({{ $memo->id }})" class="text-primary" data-toggle="modal"
                                        data-target="#edit-memo-modal">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <!-- delete button  -->
                                    <a href="{{ route('delete-memo', ['id' => $memo->id]) }}" class="text-danger ml-5">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
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
                        <h5 class="modal-title" id="category-modalLabel">メモ新規登録</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="memo-add-form" action="{{ route('storeMemo') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="form-group row">
                                <label for="category-id" class="col-md-4 col-form-label">{{ __('カテゴリ') }}</label>
                                <div class="col-md-12">
                                    <select name="category_id" id="category-id" class="form-control" required>
                                        <option value="">カテゴリを選択</option>
                                        @php
                                            foreach ($categories as $category) {
                                                echo "<option value=\"" . $category->id . "\">" . $category->name . '</option>';
                                            }
                                        @endphp
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label">{{ 'タイトル' }}</label>
                                <div class="col-md-12">
                                    <input type="text" name="name" id="name" required class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description" class="col-md-4 col-form-label">{{ '内容' }}</label>
                                <div class="col-md-12">
                                    <textarea name="description" id="description" rows="3" required
                                        class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="images" class="col-md-4 col-form-label">{{ '写真' }}</label>
                                <input type="file" name="images" id="images" class="form-control">
                            </div>
                            <div class="form-group row">
                                <label for="audio" class="col-md-4 col-form-label">{{ '音声' }}</label>
                                <input type="file" name="audio" id="audio" class="form-control">
                            </div>

                            <div class="row justify-content-center">
                                <button style="min-width: 100px" type="submit" class="btn btn-primary">追加</button>
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
                        <h5 class="modal-title" id="category-modalLabel">編集</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="edit-add-form">
                            <div class="form-group row">
                                <label for="category-id" class="col-md-4 col-form-label">{{ __('カテゴリ') }}</label>
                                <div class="col-md-12">
                                    <select name="category_id" id="edit-category-id" class="form-control" required>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label">{{ 'タイトル' }}</label>
                                <div class="col-md-12">
                                    <input type="text" name="name" id="edit-name" required class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description" class="col-md-4 col-form-label">{{ __('内容') }}</label>
                                <div class="col-md-12">
                                    <textarea name="description" id="edit-description" rows="3" required
                                        class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="images" class="col-md-4 col-form-label">{{ __('写真') }}</label>
                                <input type="file" name="images" id="images" required class="form-control">
                            </div>

                            <div class="form-group row">
                                <label for="audio" class="col-md-4 col-form-label">{{ '音声' }}</label>
                                <input type="file" name="audio" id="audio" class="form-control">
                            </div>

                            <div class="row justify-content-center">
                                <button style="min-width: 100px" type="submit" class="btn btn-primary">更新</button>
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
                        <h5 class="modal-title" id="category-modalLabel">新規カテゴリを追加</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('storecategory') }}" method="POST">
                            @method('POST')
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">カテゴリの名</label>
                                <input type="text" name="name" class="form-control" id="recipient-name" required
                                    class="form-control">
                            </div>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
                            <button type="submit" class="btn btn-primary">追加</button>
                    </div>
                </div>
                </form>
            </div>

        </div>
    </div>
    </div>
    <!-- endmodal -->

    {{-- <!-- modal edit category -->
        <div class="modal fade" id="edit-category-modal" tabindex="-1" role="dialog" aria-labelledby="category-modalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="category-modalLabel">編集</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="edit-add-form">
                            <div class="form-group row">
                                <label for="category-id" class="col-md-4 col-form-label">{{ __('カテゴリ') }}</label>
                                <div class="col-md-12">
                                    <select name="category_id" id="edit-category-id" class="form-control" required>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
        <!-- end modal edit category --> --}}

@endsection

@section('scripts')
    <!-- handle alert queue -->
    <script>
        window.onload = function() {
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
                    $('#edit-description').val(res.description);
                },
                error: (error) => {

                }
            });

            $('#edit-add-form').on('submit', (event) => {
                event.preventDefault();

                const formValue = $('#edit-add-form').serializeArray();
                const payload = formValue.reduce((s, v) => {
                    s[v.name] = v.value;
                    return s;
                }, {});

                $.ajax('/memos/' + id, {
                    method: 'patch',
                    data: payload, //update data
                    success: function() {
                        $('#edit-memo-modal').modal('toggle');
                        localStorage.setItem('alert-queue', '編集しました。');
                        location.reload();
                    },
                    error: (error) => {
                        console.log(error);
                    }
                });
            });
        }

    </script>
@endsection
