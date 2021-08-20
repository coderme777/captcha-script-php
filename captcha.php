<?php
	class Captcha 
	{
		const WIDTH = 120;
		const HEIGHT = 70;
		const LENGTH = 5;//количество проверочных символов
		const BG_LENGTH = 40;//фоновые символы, шум
		const FONT_SIZE = 16;
		const FONT = 'fonts/comic.ttf';
		
		private static $noises = ['b','2','8','n','3','c','7','k','1']; //массив с фоновым шумом
		private static $colors = ['10','40','70','100','130','160','190','202','220','250'];//используемые цвета символов
		
		public static function generate() {
			session_start();
			$src = imagecreatetruecolor(self::WIDTH, self::HEIGHT);;//создаем холст
			$bg_color = imagecolorallocate($src, 255, 255, 255);//создаем белый цвет
			imageFill($src, 0, 0, $bg_color);//закрашиваем холст белым выбирая произвольную точку
			//создадим цикл из букв для шума
			for ($i = 0; $i < self::BG_LENGTH; $i++) {
				$color = imagecolorallocatealpha($src, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255), 100);//генерируем случайные цвета для шума
            			$noise = self::$noises[mt_rand(0, count(self::$noises) - 1)];//создаем шум символов из обозначенного массива случайным образом
            			$size = mt_rand(self::FONT_SIZE - 3, self::FONT_SIZE + 3);//задаем произвольный размер шрифта для шума
			
			//выводим символы шума опр-го размера, их поворот на угол и координаты x и y
           			 imagettftext($src, $size, mt_rand(0, 45), mt_rand(self::WIDTH * 0.1, self::WIDTH * 0.9),
           			 mt_rand(self::HEIGHT * 0.1, self::HEIGHT * 0.9), $color, self::FONT, $noise);
			}
			
			//получаем разрешенные символы в цикле
			$code = '';//переменная для сбора символов
			for ($i = 0; $i < self::LENGTH; $i++) {				
				$color = imagecolorallocatealpha($src, self::$colors[mt_rand(0, count(self::$colors) - 1)],
            			self::$colors[mt_rand(0, count(self::$colors) - 1)],
            			self::$colors[mt_rand(0, count(self::$colors) - 1)], mt_rand(20, 40));
            			$noise = self::$noises[mt_rand(0, count(self::$noises) - 1)];//в данном случае выбираем буквы уже для разрешенных символов
           			$size = mt_rand(self::FONT_SIZE * 2 - 2, self::FONT_SIZE * 2 + 2);
            			$x = ($i + 1) * self::FONT_SIZE  + mt_rand(1, 5);//задаем порядок символов по x и y
            			$y = self::HEIGHT * 2  / 3 + mt_rand(1, 5);
            			$code .= $noise;
            			imagettftext($src, $size, rand(0, 15), $x, $y, $color, self::FONT, $noise);		
			}		
			$_SESSION['code'] = $code; //выводим символы через сессию
			header('Content-type: image/gif');
			imagegif($src);
		}
		
		public static function check($code) 
		{//метод сравнения вводимых символов с генерируемыми
        	if (!session_id()) session_start();//если сессия не была начата, то ее создаем
       		return $code === $_SESSION['code'];
		}
	}	
?>
