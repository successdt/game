<canvas id="canvas" width="800" height="600"></canvas>
<?php echo $this->Html->scriptStart(array('inline' =>false)) ?>
//<script>
	const RADIUS = 20;
	var cx = 100;
	var cy = 100;
	var speedX = 2;
	var speedY = 2;
	var _canvas;
	var _context;
	var _reqAnimation;
	var _angle = 0;
	var weaponId = 0;
//	var weapon = {};
	
	function update(){
		cx += speedX;
		cy += speedY;
		if(cx < 20 || cx > _canvas.width - 20)
			speedX = -speedX;
		if(cy < 20 || cy > _canvas.height - 20)
			speedY = -speedY;
		// draw
		_context.clearRect(0, 0, _canvas.width, _canvas.height);
		_context.beginPath();
		_context.arc(cx, cy, RADIUS, 0, Math.PI*2, false);
		_context.closePath();
		_context.fill();
		_reqAnimation(update);
	}
	
	function  drawWeapon(x, y){
		var speedX = (x > 0) ? 2 : -2;
		var speedY = (y > 0) ? 2 : -2;

		console.log(x, y);
		weaponId ++;
		var weapon = {
			x : 400,
			y : 300,
			speedX : speedX,
			speedY : speedY
		};

		weaponInterval = setInterval(function(){
			if (Math.abs(x / y) > 1){
				weapon.x += weapon.speedX;
				weapon.y = 300 - Math.round((weapon.x - 400) * y / x);				
			}
			else {
				weapon.y -= weapon.speedY;
				weapon.x = 400 - Math.round((weapon.y - 300) * x / y);				
			}
			console.log(weapon.x, weapon.y);
			_context.clearRect(0, 0, _canvas.width, _canvas.height);
			_context.beginPath();
			_context.arc(weapon.x, weapon.y, 5, 0, Math.PI*2, false);
			_context.closePath();
			_context.fill();
			if ((weapon.x > 800) || (weapon.x < 0) || (weapon.y > 600) || (weapon.x < 0))
				clearInterval(weaponInterval);		
		}, 10);
	}
	
	
	window.onload = function(){
	_canvas = document.getElementById("canvas");
	_context = _canvas.getContext("2d");
	_context.fillStyle = "red";
	cx = _canvas.width/2;
	cy = _canvas.height/2;

	_reqAnimation = window.requestAnimationFrame   ||
				window.mozRequestAnimationFrame    ||
				window.webkitRequestAnimationFrame  ||
				window.msRequestAnimationFrame    ||
				window.oRequestAnimationFrame    ;
//	if(_reqAnimation)
//		update();
//	else
//		alert("Your browser doesn't support requestAnimationFrame.");	
	};
	
	//click to fire
	$('#canvas').click(function(e){
		preX = e.pageX - canvas.offsetLeft;
		preY = e.pageY - canvas.offsetTop;

		drawWeapon(preX - 400, 300 - preY);
	});
<?php echo $this->Html->scriptEnd() ?>