		{{-- サイズ等指定 --}}
		@include('common/battle')
		@include('common/css', ['file' => 'battle'])

		{{-- ポップアップ --}}
		@include('popup/wrap', [
			'class'		=> 'surrenderButton',
			'template'	=> 'surrender',
			'data'		=> ['cost' => $viewData['SurrenderCost']],
		])
		@include('popup/wrap', [
			'class'		=> 'helpButton',
			'template'	=> 'help',
			'data'		=> ['log' => $viewData['EnemyData']['difficulty']]
		])

		<img src="{{IMG_URL}}battle/battle_Bg{{$viewData['EnemyData']['difficulty']}}.png" class="battle_bg">

		{{-- バトル終了フラグが立っていなければヘッダー部分表示 --}}
		@if ($viewData['BattleData']['delFlag'] != 1)
		<table border="0" class="battle_hedder">
			<tr>
				<td width="20%">
					{{-- 難易度 --}}
					<img src="{{IMG_URL}}battle/difficulty{{$viewData['EnemyData']['difficulty']}}.png" >
				</td>
				<td width="20%"></td>
				<td width="20%"></td>
				<td width="20%">
					{{-- 降参ボタン --}}
					<a>
						<img class="modal_btn surrenderButton image_change" src="{{IMG_URL}}battle/surrender.png">
					</a>
				</td>
				<td width="20%">
					{{-- 説明ボタン --}}
					<a>
						<img class="modal_btn helpButton image_change" src="{{IMG_URL}}battle/help.png">
					</a>
				</td>
			</tr>
		</table>
		@endif

		{{-- 敵キャラステータスの表示領域 --}}
		<div class="battle_enemy_status">
			{{-- 敵キャラのステータス枠 --}}
			<img src="{{IMG_URL}}battle/enemy_Status_Bar.png" class="battle_enemy_status_bar">
			{{-- 敵キャラのアイコン --}}
			<img src="{{IMG_URL}}chara/icon/icon_{{$viewData['EnemyData']['imgId']}}.png" class="battle_enemy_status_icon" >
			{{-- 敵キャラのHP部分の領域 --}}
			<div class="battle_enemy_status_hp_bar_ragion">
				{{-- 敵キャラのHPバー枠 --}}
				<img src="{{IMG_URL}}battle/hp_Bar_Flame.png" class="battle_enemy_status_hp_bar_flame">
				{{-- 敵キャラのHPバー部分の領域 --}}
				<div style="position: absolute; left: 0%; top: 1%; width: {{$viewData['EnemyData']['battleHp'] / ( $viewData['EnemyData']['hp'] / 100)}}%; height: 70%;">
					{{-- 敵キャラのHPバー --}}
					<img src="{{IMG_URL}}battle/enemy_Hp_Bar.png" class="battle_enemy_status_hp_bar">
				</div>
			</div>
			{{-- 敵キャラのHPログ --}}
			<div class="battle_enemy_status_hp">
				{{ $viewData['EnemyData']['name'] }} のHP {{ $viewData['EnemyData']['battleHp'] }} / {{ $viewData['EnemyData']['hp'] }}
			</div>
			{{-- 敵キャラの攻撃力ログ --}}
			<div class="battle_enemy_status_atk">
				{{ $viewData['Type'][1] }} : {{ $viewData['EnemyData']['gooAtk']}}
				{{ $viewData['Type'][2] }} : {{ $viewData['EnemyData']['choAtk']}}
				{{ $viewData['Type'][3] }} : {{ $viewData['EnemyData']['paaAtk']}}
			</div>
		</div>

		{{-- 攻撃の結果が入っていたら --}}
		@if ($viewData['CharaData']['result'] != 0)
			<div class="battle_enemy_hand">
				<img src="{{IMG_URL}}battle/enemy_Hand_Bg.png" class="battle_enemy_hand_bg" >
				<img src="{{IMG_URL}}battle/hand{{$viewData['EnemyData']['hand']}}.png" class="battle_enemy_hand_icon" >
			</div>

			{{-- 勝敗の表示 --}}
			<div class="damage_log">
				<img src="{{IMG_URL}}battle/damagelog_Bg.png" class="damage_log_Bg" >
				<div class="damage_log_message">
					{{ $viewData['CharaData']['name'] }}
					は	
					{{ $viewData['Type'][$viewData['CharaData']['hand']] }}
					を出した！<br />
					{{ $viewData['EnemyData']['name'] }}
					は	
					{{ $viewData['Type'][$viewData['EnemyData']['hand']] }}
					を出した！<br />

					結果は{{ $viewData['Result'][$viewData['CharaData']['result']] }}！<br />

					{{-- ダメージログの表示 --}}
					@if ( $viewData['CharaData']['result'] == 1)
							{{$viewData['EnemyData']['name']}} に
						@if ( $viewData['CharaData']['hand'] == 1)
							{{$viewData['CharaData']['battleGooAtk']}} のダメージ <br />
						@elseif ( $viewData['CharaData']['hand'] == 2)
							{{ $viewData['CharaData']['battleChoAtk'] }} のダメージ <br />
						@elseif ( $viewData['CharaData']['hand'] == 3)
							{{ $viewData['CharaData']['battlePaaAtk'] }} のダメージ <br />
						@endif
					@elseif ($viewData['CharaData']['result'] == 2)
							{{$viewData['CharaData']['name']}} に
						@if ($viewData['EnemyData']['hand'] == 1)
							{{$viewData['EnemyData']['battleGooAtk']}} のダメージ <br />
						@elseif ($viewData['EnemyData']['hand'] == 2)
							{{ $viewData['EnemyData']['battleChoAtk'] }} のダメージ <br />
						@elseif ($viewData['EnemyData']['hand'] == 3)
							{{ $viewData['EnemyData']['battlePaaAtk'] }} のダメージ <br />
						@endif
					@elseif ($viewData['CharaData']['result'] == 3)
						お互いにダメージなし<br />
					@endif

					{{-- バトル終了のフラグが立っていたら --}}
					@if ($viewData['BattleData']['delFlag'] == 1)
						<a href="{{APP_URL}}battle/makeResultData">
							バトルリザルト画面へ
						</a>
					@endif
				</div>
			</div>
		@else
			{{-- 何も出してない敵の手の枠 --}}
			<div class="battle_enemy_hand">
				<img src="{{IMG_URL}}battle/enemy_Hand_Bg.png" class="battle_enemy_hand_bg" >
			</div>

			{{-- メッセージログの枠 --}}
			<div class="damage_log">
				<img src="{{IMG_URL}}battle/damagelog_Bg.png" class="damage_log_Bg" >
			</div>
		@endif

		{{-- バトル終了のフラグが立っていなければ次の攻撃の受け付け --}}
		@if ($viewData['BattleData']['delFlag'] != 1)
			{{-- それぞれのボタン表示 --}}
			<div class="battle_playerhand_button">
				<img src="{{IMG_URL}}battle/button_Bg.png" class="battle_playerhand_button_bg" >
				<a href="{{APP_URL}}battle/updateBattleData?hand=1" class="battle_playerhand_button_goo_linkregion" >
					<img src={{IMG_URL}}battle/hand1.png class="battle_playerhand_button_icon" >
				</a>
				<a href="{{APP_URL}}battle/updateBattleData?hand=2" class="battle_playerhand_button_cho_linkregion" >
					<img src={{IMG_URL}}battle/hand2.png class="battle_playerhand_button_icon" >
				</a>
				<a href="{{APP_URL}}battle/updateBattleData?hand=3" class="battle_playerhand_button_paa_linkregion" >
					<img src={{IMG_URL}}battle/hand3.png class="battle_playerhand_button_icon" >
				</a>
			</div>
		@endif

		{{-- 自キャラステータスの表示領域 --}}
		<div class="battle_player_status">
			{{-- 自キャラのステータス枠 --}}
			<img src="{{IMG_URL}}battle/player_Status_Bar.png" class="battle_player_status_bar">
			{{-- 自キャラのアイコン --}}
			<img src="{{IMG_URL}}chara/icon/icon_{{$viewData['CharaData']['imgId']}}.png" class="battle_player_status_icon" >
			{{-- 自キャラのHP部分の領域 --}}
			<div class="battle_player_status_hp_bar_ragion">
				{{-- 自キャラのHPバー枠 --}}
				<img src="{{IMG_URL}}battle/hp_Bar_Flame.png" class="battle_player_status_hp_bar_flame">
				{{-- 自キャラのHPバー部分の領域 --}}
				<div style="position: absolute; right: 0%; bottom: 1%; width: {{$viewData['CharaData']['battleHp'] / ( $viewData['CharaData']['hp'] / 100)}}%; height: 70%;">
					{{-- 自キャラのHPバー --}}
					<img src="{{IMG_URL}}battle/player_Hp_Bar.png" class="battle_player_status_hp_bar">
				</div>
			</div>
			{{-- 自キャラのHPログ --}}
			<div class="battle_player_status_hp">
				{{ $viewData['CharaData']['name'] }} のHP {{ $viewData['CharaData']['battleHp'] }} / {{ $viewData['CharaData']['hp'] }}
			</div>
			{{-- 自キャラの攻撃力ログ --}}
			<div class="battle_player_status_atk">
				{{ $viewData['Type'][1] }} : {{ $viewData['CharaData']['gooAtk']}}
				{{ $viewData['Type'][2] }} : {{ $viewData['CharaData']['choAtk']}}
				{{ $viewData['Type'][3] }} : {{ $viewData['CharaData']['paaAtk']}}			
			</div>
		</div>

	</div>

	{{-- jsの宣言 --}}
	<script type="text/javascript" src="{{APP_URL}}js/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="{{APP_URL}}js/modal.js"></script>
	<script type="text/javascript" src="{{APP_URL}}js/imgChange.js"></script>
</body>