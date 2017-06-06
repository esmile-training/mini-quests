{{--/*
 * 闘技場選択ビュー
 * 製作者：松井 勇樹
 * 最終更新日:2017/05/25
 */--}}

{{-- css  --}}
@include('common/css', ['file' => 'battleSelect'])
{{--大会の一覧を表示する--}}
<div class="arena_list">
	<font class="caption_font"> 闘技場を選択して下さい </font>
	@foreach($viewData['difficultyList'] as $arena)
			<div class = "arena_banner{{$arena['id']}}">
				<img class ="modal_btn arena{{$arena['id']}}" src="{{IMG_URL}}battle/difficulty{{$arena['id']}}.png">
			</div>
			<?php
				$arenaData['selectedCharaId'] = $viewData['selectedCharaId'];
				$arenaData['id'] = $arena['id'];
			?>
			{{-- popupウインドウ --}}
			@include('popup/wrap', [
				'class'		=> "arena{$arena['id']}",
				'template'	=> 'arenaSelect',
				'data'	=>	['arenaData' => $arenaData]
			])
	@endforeach
</div>
{{--看板の表示--}}
<img class="signboard_img" src="{{IMG_URL}}battle/signboard.png" alt="看板">
