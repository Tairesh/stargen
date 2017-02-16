<?

$sol_mass = 1.98892*1E+30; // масса Солнца
$stef_bolts = 5.670373*1E-8; // постоянная Стефана-Больцмана
$sol_lum = 3.86*1E+33; // светимость Солнца



$spect_classes = array(
    array(
        'spectr_prefix' => '',
        'spectr' => 'F',
        'spectr_n_min' => 0,
        'spectr_n_max' => 9,
        'spectr_postfix' => 'V',
        'name' => 'белый карлик',
        'temper_min' => 6000,
        'temper_max' => 7500,
        'mass_min' => 1.2*$sol_mass,
        'mass_max' => 2.9*$sol_mass,
        'radius_min' => 700000,
        'radius_max' => 1100000
    ),
    array(
        'spectr_prefix' => '',
        'spectr' => 'F',
        'spectr_n_min' => 0,
        'spectr_n_max' => 9,
        'spectr_postfix' => 'V',
        'name' => 'жёлто-белый карлик',
        'temper_min' => 6000,
        'temper_max' => 7500,
        'mass_min' => 1.2*$sol_mass,
        'mass_max' => 2.9*$sol_mass,
        'radius_min' => 700000,
        'radius_max' => 1100000
    ),
    array(
		'spectr_prefix' => '',
        'spectr' => 'G',
        'spectr_n_min' => 0,
        'spectr_n_max' => 9,
        'spectr_postfix' => 'V',
        'name' => 'желтый карлик',
        'temper_min' => 5000,
        'temper_max' => 6000,
        'mass_min' => 0.8*$sol_mass,
        'mass_max' => 1.2*$sol_mass,
        'radius_min' => 300000,
        'radius_max' => 900000
    ),
    array(
		'spectr_prefix' => '',
        'spectr' => 'K',
        'spectr_n_min' => 0,
        'spectr_n_max' => 9,
        'spectr_postfix' => 'V',
        'name' => 'оранжевый карлик',
        'temper_min' => 3500,
        'temper_max' => 5000,
        'mass_min' => 0.5*$sol_mass,
        'mass_max' => 0.8*$sol_mass,
        'radius_min' => 100000,
        'radius_max' => 500000
    ),
    array(
		'spectr_prefix' => '',
        'spectr' => 'M',
        'spectr_n_min' => 0,
        'spectr_n_max' => 9,
        'spectr_postfix' => 'V',
        'name' => 'красный карлик',
        'temper_min' => 3500,
        'temper_max' => 2000,
        'mass_min' => 0.0767*$sol_mass,
        'mass_max' => 0.3333*$sol_mass,
        'radius_min' => 30000,
        'radius_max' => 100000
    )
);
