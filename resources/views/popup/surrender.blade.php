@include('common/css', ['file' => 'surrender'])

<div>
降参しますか？
</div>
<div>
降参費用：{{$cost}}<br />
</div>

<table border="0" class="surrender_button">
	<tr>
		<td width="25%"></td>
		<td width="20%">
			<a href="{{APP_URL}}battle/makeResultData">
				<img class="surrender_button_yes image_change" src="{{IMG_URL}}popup/yes_Button.png">
			</a>
		</td>
		<td width="10%"></td>
		<td width="20%">
			<div class="no">
				<img class="image_change" src="{{IMG_URL}}popup/no_Button.png">
				<span>no</span>
			</div>
		</td>
		<td width="25%"></td>
	</tr>
</table>