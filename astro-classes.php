<?php

/*********************************************************************
*                Классы астрономических объектов                     *
*             звезды, планеты, спутники, астероиды                   *
*********************************************************************/

abstract class CelestialBody {

protected $name; // Название
protected $text; // Текст с инфой, как в DF

protected $mass; // масса в кг.
protected $radius;  // радиус в м.
protected $galaxy_coords = array("x" => NULL, "y" => NULL, "z" => NULL); // галактические координаты, в парсеках от центра.
protected $sattelites = array(); // Спутники

function __construct ($name = false, $text = false, $coords = false, $mass = 0, $radius = 0, $sattelites = array()) {
	
	if ($name === false) {
		// Случайное имя
		$name = "Test".rand(1,1000);
	}
	
	$this->name = $name;
	
	if ($text === false) $text = "Об этом космическом объекте ничего не известно";
	$text = "Это космический объект<br><br>".$text;
	$this->text = $text;
	
	$this->mass = $mass;
	$this->radius = $radius;
	
	$this->galaxy_coords = $coords;
	
	
	$this->sattelites = $sattelites;
}

function getInfo($param) {
	return (isset($this->$param))?$this->$param:false;
}

function setInfo($param,$data) {
	return (isset($this->$param))?$this->$param=$data:false;
}

}

/* Звезда */

require_once ('data/spectral.php'); // инфа о спектральных классах

class Star extends CelestialBody {
	
	protected $spectral; // Спектральный класс
	protected $temp; // Температура поверхности в Кельвинах
	protected $mass_sol; // Масса относительно Солнца
	protected $luminosity_sol; // Светимость относительно светимости Солнца

function __construct ($name = false, $coords = false, $spect_id = false, $mass = false, $radius = false, $temp = false, $planets = false) {
	
	global $spect_classes,$sol_mass;
	
	$text = "Это звезда<br>";
	
	if ($name === false) {
			$name = "Star".rand(1,1000);
	}
	
	$text .= "Она называется «{$name}»<br>";
	
	if ($spect_id === false) {
			// Генерируем случайный спектральный класс
			$spect_id = array_rand($spect_classes);
	} 

			$spect = $spect_classes[$spect_id];
			$spectr_n = rand($spect['spectr_n_min'],$spect['spectr_n_max']);
			$this->spectral = $spect['spectr_prefix'].$spect['spectr'].$spectr_n.$spect['spectr_postfix'];
	
	$name = $spect['name']." ".$name;
	
	$text .= "Она принадлежит к классу «{$spect['name']}»<br>";
	
	if ($mass === false) {
			// Генерируем случайную массу
			$mass = rand(round($spect['mass_min']/1E+25),round($spect['mass_max']/1E+25))*1E+25;
	}
	
	$this->mass_sol = $mass / $sol_mass;
	
	if ($radius === false) {
			// Генерируем случайный радиус
			$radius = rand(round($spect['radius_min']/1000),round($spect['radius_max']/1000))*1000;;
	}
	
	if ($temp === false) {
			// Генерируем случайную температуру
			$temp = round($spect['temper_min']+$spectr_n*($spect['temper_max']-$spect['temper_min'])/($spect['spectr_n_max']-$spect['spectr_n_min']-1));
	}
	
	$this->temp = $temp;
	
	if ($this->mass_sol > 20) {
		$this->luminosity_sol = 1800*$this->mass_sol;
	} elseif ($this->mass_sol > 2) {
		$this->luminosity_sol = pow($this->mass_sol,3.5);
	} elseif ($this->mass_sol > 0.43) {
		$this->luminosity_sol = pow($this->mass_sol,4);
	} else {
		$this->luminosity_sol = 0.23 * pow($this->mass_sol,2.3);
	}
	
	if (!(is_array($coords))) {
		$coords["x"] = rand(-1000,1000);
		$coords["y"] = rand(-1000,1000);
		$coords["z"] = rand( -200, 200);
	}
	//$this->galaxy_coords = $coords;
	
	if ($planets === false) {
			// Генерируем случайные планеты
			$planets = array();
			
			$count_planets = rand(0,10);
			for ($i = 0; $i < $count_planets; $i++) {
				
				// Веселая формула для орбитального радиуса
				// Является эмпирической, т.е. тупо повторяет пропорции Солнечной системы
				// TODO: переделать, чтоб было реалистичнее, по формулам из astro_formulas.pdf
				$orb = ($i === 0)?rand(1,3)/10*$this->mass_sol:(0.3+0.2*pow(2,$i))*$this->mass_sol;
				
				$planets[] = new Planet($orb, $this->mass_sol, $this->luminosity_sol, $coords);
			}
	}
	
	$count_planets = sizeof($planets);
	//$text .= "У неё {$count_planets} планет<br>";
// Убрано потому что планеты могут добавляться динамически.
	
	parent::__construct($name, $text, $coords, $mass, $radius, $planets);

	
}

function addPlanet($orbital = false,$name = false, $planet_type_id = false, $atm_type = false, $mass = false, $radius = false, $sattelites = false) {

$i = false;

// Зона обитаемости
if ($orbital === false) $orbital = pow($this->getInfo("luminosity_sol"),0.5);

$_PLANET = new Planet($orbital, $this->getInfo('mass_sol'),  $this->getInfo('luminosity_sol'),  $this->getInfo('galaxy_coords'), $name, $planet_type_id, $atm_type, $mass, $radius, $sattelites);

// TODO зависит от эксцентриситета, так что когда он появится, нужно переделать
$e = 0; // Эксцентриситет
$big_axial = $orbital; // Большая полуось орбиты
$Nint = 3; $Next = 3; // Коэффы зависят от эксцентриситета сложным образом

// Максимальный допустимый радиус стабильной орбиты соседней планеты или астероида, расположенного ближе к звезде, чем данная
$r_min = $big_axial*((1-$e)-$Nint*pow($_PLANET->getInfo('mass')/(3*$this->getInfo('mass')),1/3));

// Минимальный допустимый радиус стабильной орбиты соседней планеты, расположенной дальше от звезды, чем данная
$r_max = $big_axial*((1+$e)+$Next*pow($_PLANET->getInfo('mass')/(3*$this->getInfo('mass')),1/3));


$planets = array();
$flag_added = false;
$n = false;

if (sizeof($this->getInfo('sattelites')) === 0) { // Если планет нет, то тупо добавляем нашу
	$planets[] = $_PLANET;
	$flag_added = true;
	$i = sizeof($_PLANET) -1;
}

foreach ($this->getInfo('sattelites') as $id => $planet) { // Проходимся по планетам и создаем новый список
if ($planet->getInfo('orbital_radius') >= $r_min and $planet->getInfo('orbital_radius') <= $r_max) {
	$planets[] = $_PLANET;  // Если есть планета в обитаемой зоне, то меняем её на нашу
	$flag_added = true;
	$i = sizeof($_PLANET) -1;
} else $planets[] = $planet;
if ($planet->getInfo('orbital_radius') > $r_max and $n === false) {
	$n = $id; // запоминаем следующую планету за обитаемой зоной
}
}

if ($flag_added === false) { // Добавляем нашу планету в обитаемую зону.
if ($n === false) { // за обитаемой зоной и в ней нет планет
$planets[] = $_PLANET; // тупо добавляем нашу планету
$i = sizeof($_PLANET) -1;
} else {
$planets[$n] = $_PLANET; // меняем следующую планету на нашу
$i = $n;
}
}
	



$this->setInfo('sattelites',$planets);

return $i;

}


}


/* Любые спутники, планеты и прочие мелкие тела (относительно звезд) */

abstract class Sattelite extends CelestialBody {
	
	protected $orbital_radius; // орбитальный радиус в а.е.
	protected $orbital_period; // орбитальный период в годах
	
		
	protected $day; // Период обращения вокруг своей оси в часах
	protected $axial_tilt; // Наклон оси вращения к эклиптике
	
	protected $mass_earth; // масса в массах Земли
	protected $gravity;    // ускорение свободного падения в м/с2
	
	protected $atmosphere; // атмосфера
	
	function __construct($name, $text, $orbital, $mass_center, $coords, $mass, $radius, $atm, $day = false, $axial_tilt = false, $sattelites = array()) {
		
		global $MASS_E,$G;
		
		$this->orbital_radius = $orbital;
		$this->orbital_period = pow(pow($orbital,3)/$mass_center,0.5);
		
    // TODO добавить редкие длинные дни (типа Венеры)
	// TODO добавить орбитальный резонанс (Меркурий, Луна)
    if ($day === false) $day = rand(10,50);

    $this->day = $day;
	
	// Наклон оси вращения к эклиптике
	// TODO подумать как сделать пореалистичнее
	// От этого же будет зависеть погода и времена года
	 if ($axial_tilt === false) $axial_tilt = rand(0,5)?rand(0,30):rand(0,180);
	 
	 $this->axial_tilt = $axial_tilt;
	 
	 $this->mass_earth = $mass;
	 $mass = $mass*$MASS_E;
	 
	 // Гравитация
	 $this->gravity = $G*$mass/pow($radius*1000,2);
	 
	 // Установка атмосферы
	 $this->atmosphere = $atm;
		
		parent::__construct($name, $text, $coords, $mass, $radius, $sattelites);
	}
}

/************************************************************
 *                   Планета                                *
 * *********************************************************/

require_once ("data/planet_types.php");

class Planet extends Sattelite {
	

	
	protected $energy; // Солнечная постоянная
	protected $albedo; // Альбедо
	protected $eff_temp;  // Эффективная тепература в Кельвинах
	protected $eff_temp_С; // Она же в Цельсиях

	
function __construct($orbital, $mass_sol, $luminosity_sol, $coords = false, $name = false, $planet_type_id = false, $atm_type_id = false, $mass = false, $radius = false, $sattelites = false) {
	
		global $stef_bolts,$planet_types,$atm_types;
		
	$text = "Это планета<br>";
	
	if ($name === false) {
			$name = "Planet".rand(1,1000);
	}
	
	$text .= "Она называется «{$name}»<br>";
		
	$this->energy = 1366*$luminosity_sol/pow($orbital,2);
	
	// TODO сделать реалистичную генерацию https://vk.com/topic-34572933_28618649
	$this->albedo = rand(100,700)/1000; // говно
	
	$this->eff_temp = pow($this->energy*(1-$this->albedo)/(4*$stef_bolts),0.25);
	$this->eff_temp_С = $this->eff_temp - 273;


	
	if ($planet_type_id === false) $planet_type_id = array_rand($planet_types);
	$planet_type = $planet_types[$planet_type_id];
	
	if ($radius === false) $radius = rand($planet_type['radius_min'],$planet_type['radius_max']);
	
	//$mass = rand(round($planet_type['mass_min']*100),round($planet_type['mass_max']*100))/100;
	
	/* Хитрая формула чтобы считать более менее реалистичную
	 * но в то же время случайную массу, в зависимости от радиуса
	 * WARNING проверено только на малых планетах!!! */
if ($mass === false) {
	$mass = $planet_type['mass_min']+($planet_type['mass_max']-$planet_type['mass_min'])*($radius-$planet_type['radius_min'])/($planet_type['radius_max']-$planet_type['radius_min']);
	$mass = $mass + rand(-round($mass*100),round($mass*100))/200;
}
	$name = $planet_type['name'].' '.$name;
	
	$text .= "Она принадлежит к типу «{$planet_type['name']}»<br>";
	
	/*********************************************************
	 *          Генерация атмосферы by Tairesh               *
	 *        TODO стоит вынести в отдельный класс           *
	 * *******************************************************/
	
if ($atm_type_id === false) {
	// удаляем все неподходящие типы атмосфер
	foreach ($planet_type['atm_types'] as $n => $atm_type_id) {
		$atm_type_tmp = $atm_types[$atm_type_id];
		if (!($this->eff_temp >= $atm_type_tmp['min_eff_temp'] && $this->eff_temp <= $atm_type_tmp['max_eff_temp'])) 
		    unset ($planet_type['atm_types'][$n]);
	}
	
	
	/* Если не нашли подходящего по температуре - пиздец */
	if (sizeof($atm_types) === 0) die ('Atmosphere generation error!');
	
$atm_type_id = $planet_type['atm_types'][array_rand($planet_type['atm_types'])];
	}
// Выбираем тип атмосферы 
	$atm_type = $atm_types[$atm_type_id];

	/* Случайное давление */
	if ($atm_type['press_min'] > 1) 
	$pressure = rand($atm_type['press_min'],$atm_type['press_max']);
	else $pressure = rand($atm_type['press_min']*10000,$atm_type['press_max']*10000)/10000;
	// Облака
	$clouds = rand($atm_type['clouds_min'],$atm_type['clouds_max']);
	
	$gases = array();
	
	if ($pressure > 0) { // Генерируем состав атмосферы
		
	$percents = 100;
	// Основной газ
	$firsth_gase = $atm_type['firsth_gases'][array_rand($atm_type['firsth_gases'])];
	$firsth_gase_percents = rand($atm_type['firsth_gase_percents_min']*100,$atm_type['firsth_gase_percents_max']*100)/100;
	
	$percents -= $firsth_gase_percents;
	// Второй газ
	$second_gase = $atm_type['second_gases'][array_rand($atm_type['second_gases'])];
	$second_gase_percents = rand($atm_type['second_gase_percents_min']*100,$atm_type['second_gase_percents_max']*100)/100;
	
	if ($second_gase_percents > $percents) $second_gase_percents = $percents;
	
	$percents -= $second_gase_percents;
	
	$gases = array($firsth_gase => $firsth_gase_percents, $second_gase => $second_gase_percents);
	
	// Удаляем первый и второй газ из списка остальных газов
	if ( in_array($firsth_gase,$atm_type['gases']) or in_array($second_gase,$atm_type['gases'])) {
		foreach ($atm_type['gases'] as $id => $gase) {
		     if ($gase === $firsth_gase or $gase === $second_gase) unset($atm_type['gases'][$id]);
		}
	}
	
		
	while ($percents > 0 && sizeof($atm_type['gases'])) { // генерируем остальные газы
		$percents = round($percents,2);
		
		$gase_id = array_rand($atm_type['gases']);
		$gase = $atm_type['gases'][$gase_id];
		
		$gase_percents = round(rand($percents*500,$percents*1000)/1000,2);
		
		$gases[$gase] = $gase_percents;
		
		$percents -= $gase_percents;
		unset ($atm_type['gases'][$gase_id]);
	    
	}
	
    }
    // Сохраняем
    $atm = array (
        'pressure'  => $pressure,
        'structure' => $gases,
        'clouds'    => $clouds
    );
    
     /******************************************************
     *             Конец генерации атмосферы               *
     ******************************************************/
	
	parent::__construct($name, $text, $orbital, $mass_sol, $coords, $mass, $radius, $atm, $sattelites);
	
}
	
}
