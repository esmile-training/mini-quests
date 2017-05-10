{{-- css  --}}
@include('common/css', ['file' => 'admin'])
@include('common/js', ['file' => 'admin'])

<div>
		キャラクター一覧
</div>

{{--所持キャラクターをすべて表示する--}}
<?php $n = 1; ?>
<form action="{{APP_URL}}retirementChara/searchCoach" method="get">
	<div>
		@foreach($viewData['charaList'] as $chara)
			<input type="image" src="{{IMG_URL}}{{$chara['imgId']}}.png" alt="キャライメージ"<
			name="uCharaId" value="{{$chara['id']}}" width="100" height="100">{{$chara['name']}}<br>
			{{--var_dump($chara)--}}
			{{-- popupボタン --}}
<div class="modal_container">
    <span class="modal_btn charastatus{{ $n }}">Show modal</span>
</div>

{{-- popupウインドウ --}}

<div class="modal charastatus{{ $n }}">
	@include('popup/charastatus')
	<div class="modal_frame">
			<div class="close">
			<span>close</span>
		</div>
	</div>
</div>

<?php $n++; ?>
		@endforeach
	</div>
</form>