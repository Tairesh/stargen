<?

$MASS_E = 5.972*1E+24; // масса Земли
$G = 6.6742*1E-11; // Гравитационная постоянная

$atm_types = array (
			array ( // 0 Нет атмосферы
			    'min_eff_temp' => 0, // в Кельвинах
			    'max_eff_temp' => INF,
				'press_min' => 0,
				'press_max' => 0,
				'firsth_gase_percents_min' => 0,
				'firsth_gase_percents_max' => 0,
				'second_gase_percents_min' => 0,
				'second_gase_percents_max' => 0,
				'firsth_gases' => array(),
				'second_gases' => array(),
				'gases' => array(),
				'clouds_min' => 0,
				'clouds_max' => 0
			),
			array ( // 1 Земная атмосфера
			    'min_eff_temp' => 230, // в Кельвинах
			    'max_eff_temp' => 300,
				'press_min' => 100,
				'press_max' => 120,
				'firsth_gase_percents_min' => 65,
				'firsth_gase_percents_max' => 75,
				'second_gase_percents_min' => 20,
				'second_gase_percents_max' => 25,
				'firsth_gases' => array('N2'),
				'second_gases' => array('O2'),
				'gases' => array('Ar','H2O','CO2','He','SO2','CH4','H2','CO','O3'),
				'clouds_min' => 1,
				'clouds_max' => 99
			),
			array ( // 2 Марсианская атмосфера
			    'min_eff_temp' => 100, // в Кельвинах
			    'max_eff_temp' => 350,
				'press_min' => 0.1,
				'press_max' => 5,
				'firsth_gase_percents_min' => 95,
				'firsth_gase_percents_max' => 99,
				'second_gase_percents_min' => 1,
				'second_gase_percents_max' => 2,
				'firsth_gases' => array('CO2'),
				'second_gases' => array('N2','Ar'),
				'gases' => array('N2','Ar','O2','CO','NO','SO2'),
				'clouds_min' => 0,
				'clouds_max' => 5
			),
			array ( // 3 атмосфера Титана
			    'min_eff_temp' => 10, // в Кельвинах
			    'max_eff_temp' => 150,
				'press_min' => 100,
				'press_max' => 200,
				'firsth_gase_percents_min' => 90,
				'firsth_gase_percents_max' => 99,
				'second_gase_percents_min' => 1,
				'second_gase_percents_max' => 9,
				'firsth_gases' => array('N2'),
				'second_gases' => array('CH4'),
				'gases' => array('H2','C3H8','C2H6','C4H2','CH3','C2H2','CO2','CO','He'),
				'clouds_min' => 50,
				'clouds_max' => 100
			),
			array ( // 4 атмосфера Венеры
			    'min_eff_temp' => 220, // в Кельвинах
			    'max_eff_temp' => 350,
				'press_min' => 7000,
				'press_max' => 10000,
				'firsth_gase_percents_min' => 90,
				'firsth_gase_percents_max' => 99,
				'second_gase_percents_min' => 1,
				'second_gase_percents_max' => 9,
				'firsth_gases' => array('CO2'),
				'second_gases' => array('N2','SO2'),
				'gases' => array('N2','SO2','Ar','H2O','CO','He','Ne','HCl','HF'),
				'clouds_min' => 100,
				'clouds_max' => 100
		    ),
		    array ( // 5 "атмосфера" Меркурия
			    'min_eff_temp' => 300, // в Кельвинах
			    'max_eff_temp' => INF,
				'press_min' => 0.0001,
				'press_max' => 0.01,
				'firsth_gase_percents_min' => 30,
				'firsth_gase_percents_max' => 80,
				'second_gase_percents_min' => 20,
				'second_gase_percents_max' => 50,
				'firsth_gases' => array('O2'),
				'second_gases' => array('Na','H2'),
				'gases' => array('He','K','H2O','CO2','N2','Ar','Ne','Ca','Mg'),
				'clouds_min' => 0,
				'clouds_max' => 0
			),
			array ( // 6 "атмосфера" Тритона или Плутона
			    'min_eff_temp' => 0, // в Кельвинах
			    'max_eff_temp' => 100,
				'press_min' => 0.0001,
				'press_max' => 0.01,
				'firsth_gase_percents_min' => 90,
				'firsth_gase_percents_max' => 99.99,
				'second_gase_percents_min' => 5,
				'second_gase_percents_max' => 10,
				'firsth_gases' => array('N2'),
				'second_gases' => array('CH4','H2'),
				'gases' => array('CH4','H2','CO2','He','O2'),
				'clouds_min' => 0,
				'clouds_max' => 0
			),
			
        );

$planet_types = array(

array( // 0 Малая каменная
		'name' => 'малая планета',
		'radius_min' => 1000, // в километрах
        'radius_max' => 3187,
        'mass_min' => 0.01,   // в массах Земли
        'mass_max' => 0.1,
        'atm_types' => array(0,1,2,3,5,6)
),

array( // 1 средняя каменная
		'name' => 'каменная планета',
		'radius_min' => 3187, // в километрах
        'radius_max' => 6374,
        'mass_min' => 0.1, // в массах Земли
        'mass_max' => 1.5,
        'atm_types' => array(0,1,2,3,4,5,6) // 6 и 0 удалить
),

array( // 2 суперземля
        'name' => 'большая каменная планета',
        'radius_min' => 6374,
        'radius_max' => 12756,
        'mass_min' => 1.5,
        'mass_max' => 7,
        'atm_types' => array(0,1,2,3,4) // 0 удалить
)

);
