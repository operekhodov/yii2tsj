<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ListView;

/**
 * @var yiiwebView $this
 * @var appmodelsMessage $message
 * @var yiidbActiveQuery $messagesQuery
 */
?>
<?php Pjax::begin([
    'timeout' => 3000,
    'enablePushState' => false,
    'linkSelector' => false,
    'formSelector' => '.pjax-form'
]) ?>
<?= $this->render('_chat', compact('messagesQuery', 'message','lastQuery')) ?>
<?php Pjax::end() ?>




<?//setInterval(updateList, 5000);?>

<?/*
if($(window).scrollTop() + $(window).height() == $(document).height()) {
var scrolled = false;
function updateScroll(){
    if(!scrolled){
        var element = document.getElementById("list-messages");
        element.scrollTop = element.scrollHeight;
    }
}

$("#list-messages").on('scroll', function(){
    scrolled=true;
});

var rloadlist = setInterval( function updateList() {	$.pjax.reload({container: '#list-messages'}); } );
var rloadlast = setInterval( function updateLast() {	$.pjax.reload({container: '#last-messages'}); } );
clearInterval(rloadlist);
clearInterval(rloadlast);

var a = $(".direct-chat-messages").height();
var b = $("#list-messages").height();
var c = $(".direct-chat-messages").scrollTop();
var ac = a+c;

$( "#btn_send" ).click(function() {
	$(".last-chat-messages").hide();
});

$(document).ready(function(){ $(".direct-chat-messages").scrollTop($("#list-messages").height()); });




$(".direct-chat-messages").scroll(function() {

	var a = $(".direct-chat-messages").height();
	var b = $("#list-messages").height();
	var c = $(".direct-chat-messages").scrollTop();
	var ac = a+c;

	if( ( ac+0.5 >= b ) && ( ac-0.5 <= b ) ) {
		$(".last-chat-messages").hide();
	}else{
		$(".last-chat-messages").show();
	}
});
alert(ac+" "+b);
if( ( ac+0.5 >= b ) && ( ac-0.5 <= b ) ) {
	setInterval(updateList, 5000);
}else{
	setInterval(updateLast, 5000);
}

$( "#go_last" ).click(function() {
	$(".last-chat-messages").hide();
	$(".direct-chat-messages").scrollTop($("#list-messages").height());
});



-----
$(document).ready(function(){ 	
		var a = $(".direct-chat-messages").height();
		var b = $("#list-messages").height();
		var c = $(".direct-chat-messages").scrollTop();
		var ac = a+c;
		if ( ( ac+0.5 >= b ) && ( ac-0.5 <= b ) ) {
			clearInterval(rloadlast);
			$(".last-chat-messages").hide();
			if (list == true) {
				var rloadlist = setInterval( function updateList() { $.pjax.reload({container: '#list-messages'}); }, 5000 );
				var list = false;
				var last = true;
			}
			alert("Бздынь");
		}	
	$(".direct-chat-messages").scroll(function() {
		if ( ( ac+0.5 >= b ) && ( ac-0.5 <= b ) ) {
			clearInterval(rloadlast);
			$(".last-chat-messages").hide();
			if (list == true) {
				var rloadlist = setInterval( function updateList() { $.pjax.reload({container: '#list-messages'}); }, 5000 );
				list = false;
				last = true;
			}
			alert("Бздынь 2 возвращение");
		}else{
			clearInterval(rloadlist);
			$(".last-chat-messages").show(); 
			if (last == true) {
				var rloadlast = setInterval( function updateLast() { $.pjax.reload({container: '#last-messages'}); }, 5000 ); 
				last = false;
				list = true;
			} 
		}
	});

});

----
при загрузке страницы
	установи позиции лист_окна вниз
	обновляй лист_окно
если произошло действие скролла
		1
		2
		3
		4
	если скролл_вал == высоте лист_окна то / (нижняя позиция) /
		останови обновление ласт_окна
		скрой ласт_окно
		обновляй лист_окно
	иначе 
		останови обновление лист_окна
		покажи ласт_окно
		обновляй ласт_окно




*/?>