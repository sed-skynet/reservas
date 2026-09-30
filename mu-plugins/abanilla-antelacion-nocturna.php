<?php
/**
 * Plugin Name: Abanilla – Reservas (antelación nocturna + hora fin)
 * Description: (1) Entre las 22:00 y las 06:00 (hora de Madrid) exige 8 h de antelación mínima en Bookly; el resto del día se aplica el ajuste de Bookly (5 h). (2) En el paso de hora del formulario muestra cada hueco como «inicio – fin». (3) Obliga a cambiar la contraseña temporal en el primer acceso.
 * Version: 2.1
 *
 * Para desactivarlo, borrar este archivo de wp-content/mu-plugins/.
 */

defined( 'ABSPATH' ) || exit;

/* ============================================================
 * 1) Antelación nocturna: 8 h entre las 22:00 y las 06:00
 * ============================================================ */
defined( 'ABANILLA_NOCHE_DESDE' ) || define( 'ABANILLA_NOCHE_DESDE', 22 ); // Hora a partir de la cual se exigen 8 h.
defined( 'ABANILLA_NOCHE_HASTA' ) || define( 'ABANILLA_NOCHE_HASTA', 6 );  // Hora hasta la cual se exigen 8 h.
defined( 'ABANILLA_HORAS_NOCHE' ) || define( 'ABANILLA_HORAS_NOCHE', 8 );

if ( ! defined( 'ABANILLA_RESERVAS_CARGADO' ) ) :
define( 'ABANILLA_RESERVAS_CARGADO', 1 );

add_filter( 'option_bookly_gen_min_time_prior_booking', function ( $value ) {
	$hora = (int) wp_date( 'G' ); // Hora local según Ajustes → Generales (Europe/Madrid).

	if ( $hora >= ABANILLA_NOCHE_DESDE || $hora < ABANILLA_NOCHE_HASTA ) {
		// Bookly guarda este ajuste en horas. Si alguna versión lo guardara en segundos, se respeta esa unidad.
		return ( (int) $value > 100 ) ? ABANILLA_HORAS_NOCHE * HOUR_IN_SECONDS : ABANILLA_HORAS_NOCHE;
	}

	return $value;
} );

/* ============================================================
 * 2) Mostrar «inicio – fin» en el selector de horas
 * ============================================================ */
add_action( 'wp_footer', function () {
	if ( is_admin() ) {
		return;
	}
	global $wpdb;
	$rows = $wpdb->get_results( "SELECT id, duration FROM {$wpdb->prefix}bookly_services", ARRAY_A );
	if ( ! $rows ) {
		return;
	}
	$dur = array(); // id de servicio -> duración en minutos
	foreach ( $rows as $r ) {
		$dur[ (int) $r['id'] ] = (int) round( ( (int) $r['duration'] ) / 60 );
	}
	?>
<script id="abanilla-horas-fin">
(function () {
  var DUR = <?php echo wp_json_encode( $dur ); ?>;

  function fmt(mins) {
    mins = ((mins % 1440) + 1440) % 1440;
    var h = Math.floor(mins / 60), m = mins % 60;
    return (h < 10 ? '0' : '') + h + ':' + (m < 10 ? '0' : '') + m;
  }

  function replaceTime(node, nuevo) {
    for (var i = 0; i < node.childNodes.length; i++) {
      var c = node.childNodes[i];
      if (c.nodeType === 3 && /\d{2}:\d{2}/.test(c.nodeValue)) { c.nodeValue = nuevo; return true; }
      if (c.nodeType === 1 && replaceTime(c, nuevo)) return true;
    }
    return false;
  }

  function decorate(btn) {
    if (btn.dataset.abFin) return;
    var raw = btn.getAttribute('value');
    if (!raw) return;
    var data;
    try { data = JSON.parse(raw); } catch (e) { return; }
    if (!Array.isArray(data) || !data[0]) return;
    var svc = parseInt(data[0][1], 10);
    var t = String(data[0][2] || '').slice(11, 16);
    if (!/^\d{2}:\d{2}$/.test(t)) return;
    var dur = DUR[svc];
    if (!dur) return;
    var ini = parseInt(t.slice(0, 2), 10) * 60 + parseInt(t.slice(3, 5), 10);
    if (replaceTime(btn, t + ' – ' + fmt(ini + dur))) btn.dataset.abFin = '1';
  }

  function run() {
    var b = document.querySelectorAll('button.bookly-hour');
    for (var i = 0; i < b.length; i++) decorate(b[i]);
  }

  var timer;
  var obs = new MutationObserver(function () { clearTimeout(timer); timer = setTimeout(run, 60); });
  function start() {
    var forms = document.querySelectorAll('.bookly-form');
    for (var i = 0; i < forms.length; i++) obs.observe(forms[i], { childList: true, subtree: true });
    run();
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start);
  else start();
})();
</script>
	<?php
} );

/* ============================================================
 * 3) Cambio de contraseña temporal en el primer acceso
 * ============================================================ */
defined( 'ABANILLA_MIS_RESERVAS' ) || define( 'ABANILLA_MIS_RESERVAS', '/mis-reservas/' );

// Marca a los clientes nuevos (creados al reservar) con «contraseña temporal».
add_action( 'user_register', function ( $user_id ) {
	$u = get_userdata( $user_id );
	if ( $u && in_array( 'customer', (array) $u->roles, true ) ) {
		update_user_meta( $user_id, 'abanilla_pw_temporal', 1 );
	}
} );

// Al iniciar sesión con contraseña temporal, llevar a Mis Reservas.
add_filter( 'login_redirect', function ( $redirect_to, $requested, $user ) {
	if ( $user instanceof WP_User && get_user_meta( $user->ID, 'abanilla_pw_temporal', true ) ) {
		return home_url( ABANILLA_MIS_RESERVAS . '?cambiar-contrasena=1' );
	}
	return $redirect_to;
}, 20, 3 );

// Aviso en el front mientras la contraseña siga siendo la temporal.
add_action( 'wp_footer', function () {
	if ( is_admin() || ! is_user_logged_in() ) {
		return;
	}
	if ( ! get_user_meta( get_current_user_id(), 'abanilla_pw_temporal', true ) ) {
		return;
	}
	?>
<style>
	#abanilla-aviso-pw{position:fixed;left:0;right:0;bottom:0;z-index:99999;background:#12276c;color:#fff;
		padding:14px 18px;font-family:system-ui,"Segoe UI",sans-serif;font-size:15px;line-height:1.4;
		display:flex;gap:14px;align-items:center;justify-content:center;flex-wrap:wrap;box-shadow:0 -4px 16px rgba(0,0,0,.2)}
	#abanilla-aviso-pw a{display:inline-block;background:#fff;color:#12276c;font-weight:600;text-decoration:none;
		padding:8px 16px;border-radius:5px}
	#abanilla-aviso-pw b{color:#ffd36b}
</style>
<div id="abanilla-aviso-pw">
	<span>Está usando una <b>contraseña temporal</b>. Por seguridad, cámbiela en la pestaña «Mi perfil».</span>
	<a href="<?php echo esc_url( home_url( ABANILLA_MIS_RESERVAS ) ); ?>">Ir a Mis Reservas</a>
</div>
	<?php
} );

// Cuando el usuario cambia su contraseña, se quita la marca.
add_action( 'profile_update', function ( $user_id, $old ) {
	$new = get_userdata( $user_id );
	if ( $new && is_object( $old ) && $new->user_pass !== $old->user_pass ) {
		delete_user_meta( $user_id, 'abanilla_pw_temporal' );
	}
}, 10, 2 );

add_action( 'after_password_reset', function ( $user ) {
	if ( $user instanceof WP_User ) {
		delete_user_meta( $user->ID, 'abanilla_pw_temporal' );
	}
} );

endif; // ABANILLA_RESERVAS_CARGADO
