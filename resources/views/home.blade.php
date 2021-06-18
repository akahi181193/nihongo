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

                    .custom-list-item {
                        color: #212529;
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
                            <a class="col-10" style="text-decoration: none;" href="/home?category={{ $item->id }}">
                                <div
                                    class="custom-list-item{{ request()->get('category') == $item->id ? '-active' : '' }}">
                                    {{ $item->name }}
                                </div>
                            </a>
                            <div class="btn-group dropright col-2">
                                <a class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
                                <div class="dropdown-menu">
                                <ul class="list-group">
                                <li class="list-group-item">
                                    <button type="button" class="btn btn-primary btn-block w-100" onclick="editCategory({{$item-> id}})" data-toggle="modal" data-target="#edit-category-modal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </li>
                                <li class="list-group-item">
                                <a style="color: #e3342f;" href="{{ route('categoryDelete', ['id' => $item->id]) }}">
                                    <i class="fas fa-trash"></i>
                                </a>
                                </li>
                                </ul>
                                   
                                </div>
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
                            <th scope="col"><div class="">タイトル</div></th>
                            <th scope="col">品詞</th>
                            @if (!request()->get('category'))
                                <th scope="col">カテゴリ</th>
                            @endif
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
                                    <a onclick="onEditButton({{ $memo->id }})" class="text-primary custom-link" data-toggle="modal"
                                        data-target="#edit-memo-modal">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <!-- delete button  -->
                                    <a class="text-danger ml-5 custom-link" data-toggle="modal" data-target="#delete-Modal">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            {{-- Delete Modal --}}
                            <div class="modal fade custom-link" id="delete-Modal" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">削除</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            この {{ $memo->name }}　削除してよろしいでしょうか?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">キャンセル</button>
                                            <a href="{{ route('delete-memo', ['id' => $memo->id]) }}"><button
                                                    type="button" class="btn btn-danger">削除</button></a>
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
                                        @foreach ($categories as $category)
                                            <option @if (request()->get('category') == $category->id) selected="selected" @endif value="{{$category->id}}">
                                                {{$category->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="name" class="col-md-4 col-form-label">{{ '品詞' }}</label>
                                        <select name="wordclass" id="wordclass" class="form-control">
                                            <option value="động từ">động từ</option>
                                            <option value="động từ">tính từ</option>
                                            <option value="động từ">trạng từ</option>
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
                                <div class="col-md-12">
                                <input type="file" name="images" id="images" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="audio" class="col-md-4 col-form-label">{{ '音声' }}</label>
                                <div class="col-md-12">
                                <input type="file" name="audio" id="audio" class="form-control">
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
                        <form id="edit-add-form" method="POST" action="/memos/" enctype="multipart/form-data">
                            @csrf
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
                                <label for="wordclass" class="col-md-4 col-form-label">{{ '品詞' }}</label>
                                <div class="col-md-12">
                                    <input type="text" name="wordclass" id="edit-wordclass" class="form-control">
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
                                <label for="edit-image" class="col-md-4 col-form-label">{{ __('写真') }}</label>
                                <input type="file" name="images" id="edit-image" class="form-control">
                            </div>

                            <div class="form-group row">
                                <label for="edit-audio" class="col-md-4 col-form-label">{{ '音声' }}</label>
                                <input type="file" name="audio" id="edit-audio" class="form-control">
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

        <!-- edit category modal -->
       
        <div class="modal fade" id="edit-category-modal" tabindex="-1" role="dialog" aria-labelledby="category-modalLabel"
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
                        <form id="editCategory" action="/editCategory/" enctype="multipart/form-data" method="POST">
                        @csrf
                            
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">カテゴリの名</label>
                                <input type="text" name="nameCategory" id="nameCategory"  class="form-control">
                            </div>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
                            <button type="submit" class="btn btn-primary">更新</button>
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
        $(function() {
            $('#myList a:last-child').tab('show')
        })

    </script>
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

            $.ajax('/editCategory/' + id, {
                success: (res) => {
                    $('#nameCategory').val(res.nameCategory);
                    $('#editCategory').attr('action', '/editCategory/' + id);
                },
                error: (error) => {

                }
            });
            
        }
    
    </script>



@endsection

