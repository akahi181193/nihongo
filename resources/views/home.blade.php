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

        <table class="table">
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
                        echo '<td><a href="' . route('editMemo', ['id' => $memo->id]) . '" class="btn btn-outline-primary" >編集</a>';
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

    @endsection

    @section('scripts')
        <script>
            setTimeout(() => {
                $('#success-alert').remove();
            }, 1000);
        </script>
    @endsection
