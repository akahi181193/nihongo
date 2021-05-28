@extends('layouts.app')

@section('content')
    <div class="container">
        {{-- @include('layouts.common-navbar') --}}

        <form class="form-inline w-100 justify-content-center" method="GET" action="{{ url('/home') }}">
            <input class="form-control mr-sm-2" value="{{ request()->get('keyword') }}" type="text" name="keyword"
                placeholder="タイトル検索" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>

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
                <div class="row justify-content-around align-items-center">
                    <b>Categories</b>
                    <button type="button" class="btn btn-primary" data-toggle="modal"
                        data-target="#exampleModal">Add</button>
                </div>
                <style>
                    #list-category {
                        height: calc(100vh - 55px - 37px - 5rem - 37px);
                        overflow-y: auto;
                    }
                </style>
                <div id="list-category" class="list-group mt-3">
                    @foreach ($categories as $item)
                        <a class="list-group-item list-group-item-action {{ request()->get('category') == $item->id ? 'active' : '' }}"
                            href="/home?category={{ $item->id }}">{{ $item->name }}</a>
                    @endforeach
                </div>
            </div>

            <div class="memos col-md-9">
                <table class="table table-striped" id="">
                    <thead>
                        <tr>
                            <th scope="col">タイトル</th>
                            <th scope="col">カテゴリ</th>
                            <th scope="col">内容</th>
                            <th scope="col">
                                <a href="/memos/add" class="btn btn-outline-primary" data-toggle="modal"
                                    data-target="#add-memo-modal">追加</a>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        @foreach ($memos as $memo)
                            <tr>
                                <td scope="row">{{ $memo->name }}</td>
                                <td>{{ $memo->category->name }}</td>
                                <td>{{ $memo->description }}</td>
                                <td>
                                    <a onclick="onEditButton({{ $memo->id }})" class="btn btn-outline-primary"
                                        data-toggle="modal" data-target="#edit-memo-modal">
                                        編集
                                    </a>
                                    <a href="/memos/delete/{{ $memo->id }}" class="btn btn-outline-danger">削除</a>
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
        <div class="modal fade" id="add-memo-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">メモ新規登録</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="memo-add-form" action="{{ route('storeMemo') }}" method="POST">
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
                                <label for="name" class="col-md-4 col-form-label">{{ __('タイトル') }}</label>
                                <div class="col-md-12">
                                    <input type="text" name="name" id="name" required class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description" class="col-md-4 col-form-label">{{ __('内容') }}</label>
                                <div class="col-md-12">
                                    <textarea name="description" id="description" rows="7" required
                                        class="form-control"></textarea>
                                </div>
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
        <div class="modal fade" id="edit-memo-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">edit</h5>
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
                                    <textarea name="description" id="edit-description" rows="7" required
                                        class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="row justify-content-center">
                                <button style="min-width: 100px" type="submit" class="btn btn-primary">編集</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!-- end edit modal-->

        <!-- modal category add -->

        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">add category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('storecategory') }}" method="POST">
                            @method('POST')
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">name</label>
                                <input type="text" name="name" class="form-control" id="recipient-name" required
                                    class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="message-text" class="col-form-label" required
                                    class="form-control">description</label>
                                <textarea class="form-control" id="message-text" name="description"></textarea>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <!-- endmodal -->

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
