@extends('layouts.content')

@section('content')
	<div class="profile_col_1">
		<div class="profile_col_1_inner">
			<div id="profile_user_avatar">
				<img src="{{ $user->avatar }}" alt="">
			</div>
		</div>
	</div>

	<div class="profile_col_2">
		<div class="profile_col_2_inner">
			<div id="profile_info">
				<div><h2>{{ $user->surname.' '.$user->name }}</h2></div>
				<div id="profile_birth"><p>Дата рождения: {{ $user->birth }}</p></div>
				<div id="profile_city"><p>Город: <cityname>{{ isset($user->city) ? $user->city : 'неизвестно' }}</cityname></p></div>
				@if(isset($user->school))
					<div id="school"><p></p></div>
				@endif
				@if(isset($user->university))
					<div id="university"><p>ВУЗ: {{ $user->university }}</p></div>
				@endif

				<div id="summ_heap">
					<div id="summ_friends">
						<p>{{ $user->friends ? $user->friends : 0 }}</p> friends
					</div>
					<div id="summ_photos">
						<p>{{ $user->photos ? $user->photos : 0 }}</p> photos
					</div>
				</div>

			</div>
		</div>
	</div>
@endsection