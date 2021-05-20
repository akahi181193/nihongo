@extends('layouts.app')

@section('content')
    <div class="container">
        @include('layouts.common-navbar')
        @if (\Session::has('success'))
            <div id="success-alert" class="alert alert-success alert-dismissible fade show"
                style="position: fixed; top: 100px; right: 0;" role="alert">
                <span>{!! \Session::get('success') !!}</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <table class="table" id="">
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
                @php
                    foreach ($memos as $key => $memo) {
                        echo "<tr>\n";
                        echo "<td scope=\"row\">" . $memo->name . "</td>\n";
                        echo '<td>' . $memo->category->name . "</td>\n";
                        echo '<td>' . $memo->description . "</td>\n";
                        echo '<td><a href="' . route('editMemo', ['id' => $memo->id]) . '" class="btn btn-outline-primary" data-toggle="modal"
                            data-target="#edit-memo-modal" >編集</a>';
                        echo '<a href="' . route('deleteMemo', ['id' => $memo->id]) . ' " class="btn btn-outline-danger" >解消</a></td>';
                        echo "</tr>\n";
                    }
                @endphp
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

        <!-- Modal 1-->
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
        <!-- end modal 1 -->
        <!-- start edit Modal 2 -->
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
                        <form id="edit-add-form" action="/memos/update/{{isset($memo->id)?$memo->id:''}}" method="POST" id="edit_form">
                        <!-- <form id="edit-add-form" action="route('updatememo', ['id' => $memo->id])" method="POST" id="edit_form"> -->
                            {{ csrf_field() }}
                            {{ method_field('patch') }}                
                            <div class="form-group row">
                                <label for="category-id" class="col-md-4 col-form-label">{{ __('カテゴリ') }}</label>
                                <div class="col-md-12">
                                    <select name="category_id" id="category-id" class="form-control" required>
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
                                    <input type="text" value="{{isset($memo->name)?$memo->name:''}}"name="name" id="editname" required class="form-control">
                                </div>
                                <!-- <div class="col-md-12">
                                    <input type="text" value=" $memo->name "name="name" id="editname" required class="form-control">
                                </div> -->
                            </div>

                            <div class="form-group row">
                                <label for="description" class="col-md-4 col-form-label">{{ __('内容') }}</label>
                                <div class="col-md-12">
                                    <textarea name="description" id="editdescription" rows="7" required
                                        class="form-control">{{isset($memo->description)?$memo->description:''}}</textarea>
                                    <!-- <textarea name="description" id="editdescription" rows="7" required
                                        class="form-control">$memo->description</textarea> -->
                                </div>
                            </div>

                            <div class="row justify-content-center">
                                <button style="min-width: 100px" type="submit" class="btn btn-primary">update</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!-- end edit modal 2 -->
        <!-- modal category add -->
            
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <input type="text" name="name" class="form-control" id="recipient-name" required class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label" required class="form-control">description</label>
                            <textarea class="form-control" id="message-text" name="description" ></textarea>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" >+</button>
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
    <!-- <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript">
    $(document).ready( function () {
     var table =$('#').DataTable();
    //  start edit record
    table.on('click','edit',function(){
        $tr =$(this).closest('tr');
        if ($($tr).hasClass('child')) {
            $tr = $tr.prev('.parent');
        }
        var data = table.row($tr).data();
        console.log(data);
        $('').val(data[1]);
        $('').val(data[2]);
        $('').val(data[3]);
        $('').val(data[4]);

        $('#editform').attr('action','/employee/'+data[0]);
        $('#editform').modal('show');
    });
    } );
    
    </script> -->
        <script>
            setTimeout(() => {
                $('#success-alert').remove();
            }, 1000);
        </script>
    @endsection
