<div style="position: relative; width: 600px; height: 600px;">
	<canvas id="bg-canvas" width="600" height="600" style="position: absolute; left: 0; top: 0; z-index: 0;"></canvas>
 	<div class="background">
		<div class="background-inner">
			<div class="main-ship"></div>
		</div>
 	</div>
 	<canvas id="main-canvas" width="600" height="600" style="position: absolute; left: 0; top: 0; z-index: 2;"></canvas>
<!--
 <canvas id="hawk-canvas" width="600" height="600" style="position: absolute; left: 0; top: 0; z-index: 2;"></canvas>
-->
<div id="hawk-wrapper">
	<?php echo $this->Html->image('image 431.png', array('id' => 'heli')) ?>
	<div class="rotor"></div>
</div>
</div>
<?php echo $this->Html->scriptStart(array('inline' =>false)) ?>
//<script>
	var bgCanvas;
	var mainCanvas;
//	var hawkCanvas;
	var bgContext;
	var mainContext;
//	var hawkContext;
	const FPS = 60;
	const TICKS = 1000/FPS;
	var lastUpdateTime;
	var heliRotateAngle = 180;
	var bgPositionX = 0;
	var bgPositionY = 0;
	var keyPress = [];
	var rocketId = 0;
	var warshipId = 0;
	//keycode
	var Keys = {
		BACKSPACE: 8,
		TAB: 9,
		ENTER: 13,
		SHIFT: 16,
		CTRL: 17,
		ALT: 18,
		PAUSE: 19,
		CAPS_LOCK: 20,
		ESCAPE: 27,
		SPACE: 32,
		PAGE_UP: 33,
		PAGE_DOWN: 34,
		END: 35,
		HOME: 36,
		LEFT_ARROW: 37,
		UP_ARROW: 38,
		RIGHT_ARROW: 39,
		DOWN_ARROW: 40,
		INSERT: 45,
		DELETE: 46,
		KEY_0: 48,
		KEY_1: 49,
		KEY_2: 50,
		KEY_3: 51,
		KEY_4: 52,
	};
	
	bgCanvas = document.getElementById("bg-canvas");
	bgContext = bgCanvas.getContext("2d");
	mainCanvas = document.getElementById("bg-canvas");
	mainContext = bgCanvas.getContext("2d");
//	hawkCanvas = document.getElementById("hawk-canvas");
//	hawkContext = _canvas.getContext("2d");


	function gameLoop(){
		var now = Date.now();
		var diffTime = now - lastUpdateTime;
		if(diffTime >= TICKS){
			processInput();
			updateGame();
			lastUpdateTime = now;
		}
		draw();
		var sleepTime = TICKS - diffTime;
		if(sleepTime<0)
			sleepTime = 0;
		setTimeout(gameLoop,sleepTime);
	}
	
	warship();
	setInterval(function(){
		//Cánh quạt quay
		$('.rotor').toggleClass('sprite');
		
		//Kiểm tra nút được ấn
		if (keyPress[Keys.UP_ARROW])
			forward();
		if (keyPress[Keys.DOWN_ARROW])
			backward();
		if (keyPress[Keys.LEFT_ARROW]){
			heliRotateAngle -= 5;
			heliRotate(heliRotateAngle);			
		}
		if (keyPress[Keys.RIGHT_ARROW]){
			heliRotateAngle += 5;
			heliRotate(heliRotateAngle);			
		}
	}, 50)
	setInterval(function(){
		if (keyPress[Keys.SPACE])
			newRocket();		
	}, 100);
	
	setInterval(function(){
		$('.main-ship').toggleClass('case1');		
	}, 1000);
		
	//Khi nút được nhấn
	$(document).keydown(function(e){
		keyPress[e.keyCode] = true;
	});
	//Khi thả nút ra
	$(document).keyup(function(e){
		keyPress[e.keyCode] = false;
	});
	
	//xoay máy bay theo chiều nhất định
	function heliRotate(angle){
		$('#heli').css('-webkit-transform', 'rotate(' + angle + 'deg)');		
		$('#heli').css('-moz-transform', 'rotate(' + angle + 'deg)');
		$('#heli').css('-o-transform', 'rotate(' + angle + 'deg)');
	}
	
	//Tiến về phía trước
	function forward(){
		var radianAngle = (heliRotateAngle - 90) * Math.PI / 180;
		bgPositionY += Math.round(10 * Math.sin(radianAngle));
		bgPositionX += Math.round(10 * Math.cos(radianAngle));
		updateBackground();
	}
	function backward(){
		var radianAngle = (heliRotateAngle - 90) * Math.PI / 180;
		bgPositionY -= Math.round(10 * Math.sin(radianAngle));
		bgPositionX -= Math.round(10 * Math.cos(radianAngle));
		updateBackground();	
	}
	function updateBackground(){
		//Giới hạn map
		if ((bgPositionY > 1200))
			bgPositionY = 1200;
		if ((bgPositionX > 1200))
			bgPositionX = 1200;
		if ((bgPositionY < -1200))
			bgPositionY = -1200;
		if ((bgPositionX < -1200))
			bgPositionX = -1200;
		$('.background-inner').css('left', -1200 + bgPositionX + 'px');
		$('.background-inner').css('top', - 1200 + bgPositionY + 'px');
//		$('#bg-canvas').css('background-position', bgPositionX + 'px ' + bgPositionY + 'px');		
	}
	
	//helicopter bắn rocket
	function newRocket(){
		var x = 1495 - bgPositionX;
		var y = 1478 - bgPositionY;
		var rocketAngle = 180 + heliRotateAngle;
		var id = rocketId;
		var lineSize = 0;
		rocketId++;
		
		$('.background-inner').append('<div class="rocket" id="rocket' + id + '"></div>');
		$('#rocket' + id).css('left',x + 'px').css('top',y + 'px');
		$('#rocket' + id).css('-webkit-transform', 'rotate(' + rocketAngle + 'deg)');		
		$('#rocket' + id).css('-moz-transform', 'rotate(' + rocketAngle + 'deg)');
		$('#rocket' + id).css('-o-transform', 'rotate(' + rocketAngle + 'deg)');
		
		//di chuyển tên lửa
		var interval = setInterval(function(){
			var radianAngle = (rocketAngle + 90) * Math.PI / 180;
			x -= Math.round(10 * Math.cos(radianAngle));
			y -= Math.round(10 * Math.sin(radianAngle));
			lineSize ++;
			$('#rocket' + id).css('left',x + 'px').css('top',y + 'px');
			if ((lineSize < 10) && (lineSize > 5))
				$('#rocket' + id).removeClass('stage' + (lineSize - 6)).addClass('stage' + (lineSize - 5));
			if ((x < 0) || (x > 3000) || (y < 0) || (y > 3000) || ((lineSize * 10) > 400)){
 				$('#rocket' + id).remove();
				clearInterval(interval);
			}			
		}, 30);
	}
	
	function warship(){
		var x = 1200;
		var y = 3000;
		var angle = 180 * Math.atan((1495 - x) / (y - 1478)) / Math.PI;
		var id = warshipId;
		var isDestroyed = false;
		
		warshipId++;
		$('.background-inner').append('<div class="warship" id="warship' + id + '"></div>');

		$('#warship' + id).css('left',x + 'px').css('top',y + 'px');
		$('#warship' + id).css('-webkit-transform', 'rotate(' + angle + 'deg)');		
		$('#warship' + id).css('-moz-transform', 'rotate(' + angle + 'deg)');
		$('#warship' + id).css('-o-transform', 'rotate(' + angle + 'deg)');

		//di chuyển tàu
		var interval = setInterval(function(){
			var radianAngle = (angle + 90) * Math.PI / 180;
			x -= Math.round(5 * Math.cos(radianAngle));
			y -= Math.round(5 * Math.sin(radianAngle));
			$('#warship' + id).css('left',x + 'px').css('top',y + 'px');
			
			//Nếu tới gần thì dừng lại và bắn đạn
			
			
			if (Math.sqrt(Math.abs(x - 1495) * Math.abs(x - 1495) + Math.abs(y - 1478) * Math.abs(y - 1478)) < 150){
				clearInterval(interval);
			}
			
			//Nếu tàu bị phá hủy
			if (isDestroyed || (x < 0) || (x > 3000) || (y < 0) || (y > 3000)){
 				$('#warship' + id).remove();
				clearInterval(interval);
			}
		}, 50);
	}
<?php echo $this->Html->scriptEnd() ?>