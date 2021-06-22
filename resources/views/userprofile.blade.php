@extends('layouts.app')
@section('content')
@foreach ($user as $users)
@if (\Session::has('success'))
            <div class="success-alert alert alert-success alert-dismissible fade show"
                style="position: fixed; top: 100px; right: 0;" role="alert">
                <span>{!! \Session::get('success') !!}</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
@endif

<div class="container">
	@error('avatar')
			<div class="alert alert-danger">{{ $message }}</div>
@enderror
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="row gutters">
	<div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
		<div class="card h-100">
			<div class="card-body">
				<div class="account-settings">
					<div class="user-profile">
						<div class="user-avatar">
						<div class="text-center">
							<img class="rounded" style="width: 100%;height: 100%;" src="{{ asset('storage/avatar/' . $users->avatar) }}  " alt="user profile">
						</div>

							<div class="d-flex justify-content-end">
								<button type="button" class="btn btn-outline-primary">
									<label for="avatar"style="margin: 0px;/* padding: 0px; */">
									<i class="fas fa-edit"></i>
									</label>
								</button>

								<a href="{{ url('/deleteprofile/'.  Auth::user()->id )}}"><button type="button" class="btn btn-outline-danger"> <i class="fas fa-trash"></i></button></a>
							</div>
							
						</div>
						<h5 class="user-name">{{ $users->name }}</h5>
						<h6 class="user-email">{{ $users->email }}</h6>
					</div>
					<div class="about">
						<h5 class="mb-2 text-primary">自己紹介</h5>
						<p>{{ $users->about }}</p>
						
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
		<div class="card h-100">
			<div class="card-body">
			<form method="post" action="{{ route('updateprofile',['id' => Auth::user()->id ] )}}" enctype="multipart/form-data">
				@csrf
			
				<div class="row gutters">
					<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
						<h6 class="mb-3 text-primary">概要</h6>
					</div>
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
						<div class="form-group">
							<label for="fullName">名前</label>
							<input type="text" class="form-control" id="fullName" name="name" value="{{ $users->name }}" placeholder="Enter full name">
						</div>
					</div>
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
						<div class="form-group">
							<label for="eMail">メール</label>
							<input type="email" class="form-control" id="eMail" name="email" value="{{ $users->email }}" placeholder="Enter email ID">
						</div>
					</div>
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
						<div class="form-group">
							<label for="phone">性別</label><br>
							<input type="radio" id="male" name="sex" value="male" {{ ($users->sex=="male")? "checked" : "" }} >
							<label for="male">男性</label><br>
							<input type="radio" id="female" name="sex" value="female"{{ ($users->sex=="female")? "checked" : "" }} >
							<label for="female">女性</label><br>
							<input type="radio" id="other" name="sex" value="other" {{ ($users->sex=="other")? "checked" : "" }} >
							<label for="other">他</label>
						</div>
					</div>
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
						<div class="form-group">
							<label for="website"> ソーシャルリンク</label>
							<input value="{{ $users->social }}"name="social" class="form-control" 　>
						</div>
					</div>
				</div>
				<div class="row gutters">
					<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

					</div>
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
						<div class="form-group">
							<label for="Street">交際ステータス</label><br>
							<input   type="radio" id="single" name="relationship_status" value="single" {{ ($users->relationship_status =="single")? "checked" : "" }}>
							<label for="male">独身</label><br>
							<input type="radio" id="married" name="relationship_status" value="married" {{ ($users->relationship_status=="married")? "checked" : "" }}>
							<label for="female">既婚</label><br>
							<input type="radio" id="other" name="relationship_status" value="other" {{ ($users->relationship_status=="other")? "checked" : "" }}>
							<label for="other">交際中</label>
						</div>
					</div>
							
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
						<div class="form-group">
								<label for="about">自己紹介</label>
								<br>
								<textarea class="form-control" rows="4" name="about" placeholder="Enter text here...">{{ $users->about }}</textarea>
						</div>
					</div>
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
						<div class="form-group">
							<label for="birthday">誕生</label>
							<br>
							<input type="date" id="birthday" name="birthday" value="{{ $users->birthday }}">
						</div>
					</div>
					<input type="file" name="avatar" id="avatar" class="form-control" multiple style="display:none">
					
				</div>
				<div class="row gutters">
					<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
						<div class="text-right">
							<a href="{{route('home')}}"><button type="button"   class="btn btn-secondary">戻る</button></a>
							<button type="submit"  class="btn btn-primary">編集</button>
						</div>
					</div>
				</div>
				</div>
			</form>
		</div>
	</div>
</div>
</div>
<script>
        setTimeout(() => {
            $('.success-alert').remove();
        }, 2000);
		

</script>

@endforeach
@endsection