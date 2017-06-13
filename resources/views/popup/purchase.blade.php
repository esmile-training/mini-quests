@include('common/css', ['file' => 'purchase'])

<?php
	$number[$purchaseData['productData']['id']] = 1;
?>

<div class="purchase">
	{{-- 背景画像 --}}
	<img src="{{IMG_URL}}shop/popup_Bg.png" class="purchase_bg">

	{{-- アイテムの画像 --}}
		@if( $purchaseData['productData']['id'] == 1)
		<div class="purchase_ticket_img">
			<img class="itemlist_flame_item_img" src="{{IMG_URL}}user/battleTicket.png" />
		</div>
		@else
		<div class="purchase_item_img">
			<img class="itemlist_flame_item_img" src="{{IMG_URL}}item/item{{$purchaseData['productData']['id']}}.png" />
		</div>
		@endif

	{{-- アイテムの名前 --}}
	<div class="purchase_name">
		{{$purchaseData['productData']['name']}}
	</div>

	{{-- アイテムの使う場所 --}}
	<div class="purchase_itemWhere">
		{{$purchaseData['itemData']['where']}}
	</div>
	
	{{-- アイテムの説明 --}}
	<div class="purchase_itemInfo">
		{{$purchaseData['itemData']['abilityInfo']}}
	</div>

	{{-- アイテムの個数 --}}
	<table border="0" class="purchase_totalNumber">
		<tr>
			<td width=22%></td>
			<td width=15%>
				<img src="{{IMG_URL}}popup/minus_Button.png" class="image_change">
			</td>
			<td width=26%>
				{{$number[$purchaseData['productData']['id']]}}
			</td>
			<td width=15%>
				<img src="{{IMG_URL}}popup/plus_Button.png" class="image_change">
			</td>
			<td width=22%></td>
		</tr>
	</table>
	
	{{-- アイテムの合計金額--}}
	<div class="purchase_totalPrice">
		{{$purchaseData['productData']['price']}}
	</div>
	
	{{-- 購入ボタン --}}
	<div class="purchase_button_purchase">
		<a href="{{APP_URL}}shop/updateBelongings?purchaseItemId={{$purchaseData['itemData']['id']}}&totalPrice={{$purchaseData['productData']['price']}}" class="clickfalse">
			<img src="{{IMG_URL}}popup/purchase_Button.png" class="image_change purchase_button_purchase_img">
		</a>
	</div>
</div>

<script type="text/javascript">

	var $cnt = 0;

	function cntUp{{$purchaseData['productData']['id']}}() {
		if(document.getElementById( "number{{$purchaseData['productData']['id']}}" ).innerHTML >= 10){
			return false;
		}
		document.getElementById( "number{{$purchaseData['productData']['id']}}" ).innerHTML = ++$cnt;
	}

	function cntDown{{$purchaseData['productData']['id']}}() {
		if(document.getElementById( "number{{$purchaseData['productData']['id']}}" ).innerHTML <= 0){
			return false;
		}
		document.getElementById( "number{{$purchaseData['productData']['id']}}" ).innerHTML = --$cnt;
	}
</script>