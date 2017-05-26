<?php


///////////////////////
// DEBUG
///////////////////////

// Affichage debug print_r
if (!function_exists('aff_p')) {
	function aff_p($v) {
		echo "<pre style='background:#fff'>";
		print_r($v);
		echo "</pre>";
	}
}

// Affichage debug var_dump
if (!function_exists('aff_v')) {
	function aff_v($v) {
		echo "<pre style='background:#fff'>";
		var_dump($v);
		echo "</pre>";
	}
}

// Affichage debug var_export
if (!function_exists('aff_ve')) {
	function aff_ve($v) {
		echo "<pre style='background:#fff'>";
		var_export($v);
		echo "</pre>";
	}
}


// br dynamique
function e_br($n=1) {
	if ($n == 1 ) :
		echo "<br>";
	elseif ($n == 2 ) :
		echo "<br><br>";
	elseif ($n == 3) :
		echo "<br><br><br>";
	endif;
}
