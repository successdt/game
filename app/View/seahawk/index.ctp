<div style="position: relative; width: 600px; height: 600px;">
 <canvas id="bg-canvas" width="600" height="600" style="position: absolute; left: 0; top: 0; z-index: 0;"></canvas>
 <canvas id="main-canvas" width="600" height="600" style="position: absolute; left: 0; top: 0; z-index: 1;"></canvas>
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
	
	//Cánh quạt quay
	setInterval(function(){
		$('.rotor').toggleClass('sprite');
	}, 50)
	
	$(document).keydown(function(e){
		switch(e.keyCode){
			case Keys.LEFT_ARROW:
				heliRotateAngle -= 5;
				heliRotate(heliRotateAngle);
				break;
			case Keys.RIGHT_ARROW:
				heliRotateAngle += 5;
				heliRotate(heliRotateAngle);
				break;
			case Keys.UP_ARROW:
				forward();
				break;
			case Keys.DOWN_ARROW:
				backward();
				break;
			case Keys.SPACE:
			break;
		}

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
		bgPositionY += Math.round(20 * Math.sin(radianAngle));
		bgPositionX += Math.round(20 * Math.cos(radianAngle));
		$('#bg-canvas').css('background-position', bgPositionX + 'px ' + bgPositionY + 'px');
	}
	function backward(){
		var radianAngle = (heliRotateAngle - 90) * Math.PI / 180;
		bgPositionY -= Math.round(20 * Math.sin(radianAngle));
		bgPositionX -= Math.round(20 * Math.cos(radianAngle));
		$('#bg-canvas').css('background-position', bgPositionX + 'px ' + bgPositionY + 'px');		
	}
<?php echo $this->Html->scriptEnd() ?>