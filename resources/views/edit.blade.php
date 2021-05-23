@extends('layouts.app')
@section('content')
<div class="container-fluid">
<div class="col-md-3 offset-md-4">
    <form method="post" action="{{ route('updatememo',['id' => $edit_memo->id]) }}">
        @method('patch')
        @csrf
        <div class="col-md-12">
            <label for="exampleInputEmail1" class="form-label">タイトル</label>
            <input class="form-control" type="text" name="name" value="{{ $edit_memo->name }}">
        </div>
        <div class="col-md-12">
            <label for="formGroupExampleInput" class="form-label">カテゴリ</label>
            <br>
            <select name="category_id" id="category-id" class="form-select" aria-label="Default select example" >
            <option value="">カテゴリを選択</option>
            @php
            foreach ($categories as $category) {
                echo "<option value=\"   " . $category->id  . "   \">" . $category->name . '</option>';
            }
            @endphp
            </select>
        </div>
        <div class="col-md-12">
            <label for="description" class="form-label">内容</label><br>
            <textarea cols="50" rows="5" name="description"> {{ $edit_memo->description }}</textarea>
            <button type="submit" class="btn btn-outline-primary" >編集</button>
        </div>
    </form>
</div>
</div>
@endsection