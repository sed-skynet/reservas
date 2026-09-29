<?php
/**
 * Plugin Name: Abanilla – Antelación nocturna de reservas
 * Description: Entre las 22:00 y las 06:00 (hora de Madrid) exige 8 h de antelación mínima en Bookly. El resto del día se aplica el ajuste de Bookly → Configuración → General → Tiempo mínimo antes de la reserva (5 h).
 * Version: 1.0
 *
 * Para desactivarlo, borrar este archivo de wp-content/mu-plugins/.
 */

defined( 'ABSPATH' ) || exit;

const ABANILLA_NOCHE_DESDE = 22; // Hora a partir de la cual se exigen 8 h.
const ABANILLA_NOCHE_HASTA = 6;  // Hora hasta la cual se exigen 8 h.
const ABANILLA_HORAS_NOCHE = 8;

add_filter( 'option_bookly_gen_min_time_prior_booking', function ( $value ) {
	$hora = (int) wp_date( 'G' ); // Hora local según Ajustes → Generales (Europe/Madrid).

	if ( $hora >= ABANILLA_NOCHE_DESDE || $hora < ABANILLA_NOCHE_HASTA ) {
		// Bookly guarda este ajuste en horas. Si alguna versión lo guardara en segundos, se respeta esa unidad.
		return ( (int) $value > 100 ) ? ABANILLA_HORAS_NOCHE * HOUR_IN_SECONDS : ABANILLA_HORAS_NOCHE;
	}

	return $value;
} );
