<?php
/**
 * Plugin Name: Abanilla – Citas para administradores
 * Description: Página con todas las citas de Bookly sin entrar en el panel de WordPress. Shortcodes: [abanilla_citas] (tabla con filtros) y [abanilla_boton_citas] (aviso «Iniciar sesión» sin sesión; botón a las citas para administradores). Renombra «Acceder» a «Iniciar sesión» en el menú y añade «Citas» para administradores.
 * Version: 1.2
 *
 * Solo lectura: para aprobar, rechazar o editar se usa el enlace «Abrir en Bookly».
 */

defined( 'ABSPATH' ) || exit;

const ABANILLA_CITAS_CAPACIDAD = 'manage_options'; // Quién puede ver las citas (administradores).
const ABANILLA_CITAS_MAX       = 500;              // Máximo de filas por consulta.
const ABANILLA_CITAS_URL       = '/citas/';        // Página con el shortcode [abanilla_citas].

function abanilla_citas_puede_ver() {
	return is_user_logged_in() && current_user_can( ABANILLA_CITAS_CAPACIDAD );
}

// La página con el listado nunca debe guardarse en caché.
add_action( 'template_redirect', function () {
	$post = get_queried_object();
	if ( $post instanceof WP_Post && has_shortcode( $post->post_content, 'abanilla_citas' ) ) {
		nocache_headers();
	}
} );

// Tarjeta discreta bajo los botones principales: icono, título, texto y botón secundario.
function abanilla_citas_tarjeta( $icono, $titulo, $texto, $url, $boton ) {
	$iconos = array(
		'candado'    => '<path d="M7 11V8a5 5 0 0 1 10 0v3"/><rect x="4" y="11" width="16" height="10" rx="2"/><circle cx="12" cy="16" r="1.5"/>',
		'calendario' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/><path d="m9 15 2 2 4-4"/>',
	);

	static $css = false;
	$estilos = '';
	if ( ! $css ) {
		$css     = true;
		$estilos = '<style>
			.ab-acceso,.ab-acceso *{box-sizing:border-box}
			.ab-acceso{display:flex;align-items:center;gap:16px;max-width:560px;margin:8px auto 0;padding:14px 18px;
				background:rgba(255,255,255,.82);backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);
				border:1px solid rgba(18,39,108,.12);border-radius:12px;box-shadow:0 6px 20px rgba(18,39,108,.10);text-align:left}
			.ab-acceso__icono{flex:0 0 44px;height:44px;display:flex;align-items:center;justify-content:center;border-radius:50%;background:#12276c;color:#fff}
			.ab-acceso__icono svg{width:22px;height:22px}
			.ab-acceso__texto{flex:1;line-height:1.35}
			.ab-acceso__titulo{display:block;font-weight:600;color:#12276c;font-size:15px}
			.ab-acceso__sub{display:block;color:#4a5578;font-size:13px}
			.ab-acceso__boton{flex:0 0 auto;display:inline-flex;align-items:center;gap:6px;padding:9px 16px;border:2px solid #12276c;border-radius:6px;
				color:#12276c !important;font-weight:600;font-size:14px;text-decoration:none !important;transition:background .2s,color .2s}
			.ab-acceso__boton:hover,.ab-acceso__boton:focus{background:#12276c;color:#fff !important}
			.ab-acceso__boton span{transition:transform .2s}
			.ab-acceso__boton:hover span{transform:translateX(3px)}
			@media (max-width:600px){
				.ab-acceso{flex-wrap:wrap;margin:8px 0 0}
				.ab-acceso__boton{width:100%;justify-content:center}
			}
		</style>';
	}

	return $estilos . sprintf(
		'<div class="ab-acceso"><div class="ab-acceso__icono"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">%s</svg></div>'
		. '<div class="ab-acceso__texto"><span class="ab-acceso__titulo">%s</span><span class="ab-acceso__sub">%s</span></div>'
		. '<a class="ab-acceso__boton" href="%s">%s <span aria-hidden="true">→</span></a></div>',
		$iconos[ $icono ],
		esc_html( $titulo ),
		esc_html( $texto ),
		esc_url( $url ),
		esc_html( $boton )
	);
}

// Sin sesión: aviso para el personal y botón de acceso. Administradores: botón a las citas.
add_shortcode( 'abanilla_boton_citas', function ( $atts ) {
	$atts = shortcode_atts( array( 'url' => ABANILLA_CITAS_URL, 'texto' => 'Ver citas' ), $atts );

	if ( ! is_user_logged_in() ) {
		return abanilla_citas_tarjeta( 'candado', '¿Es personal del Ayuntamiento?', 'Inicie sesión para ver y gestionar las citas.', wp_login_url(), 'Iniciar sesión' );
	}
	if ( ! abanilla_citas_puede_ver() ) {
		return '';
	}

	return abanilla_citas_tarjeta( 'calendario', 'Panel de citas', 'Todas las solicitudes y reservas, con filtros por fecha, estado y localidad.', $atts['url'], $atts['texto'] );
} );

// Menú: el enlace de acceso se llama «Iniciar sesión» y los administradores ven además «Citas».
add_filter( 'wp_nav_menu_objects', function ( $items ) {
	foreach ( $items as $item ) {
		if ( ! is_user_logged_in() && false !== strpos( $item->url, 'wp-login.php' ) && false === strpos( $item->url, 'action=logout' ) ) {
			$item->title = 'Iniciar sesión';
		}
	}
	return $items;
} );

add_filter( 'wp_nav_menu_items', function ( $html ) {
	if ( ! abanilla_citas_puede_ver() || false === strpos( $html, 'wp-login.php' ) ) {
		return $html; // Solo en el menú que tiene el enlace de acceso/salida.
	}
	$li = '<li class="menu-item abanilla-menu-citas"><a href="' . esc_url( home_url( ABANILLA_CITAS_URL ) ) . '"><span>Citas</span></a></li>';

	// Justo antes del enlace de salir; si no se encuentra, al final.
	$pos = strrpos( substr( $html, 0, (int) strpos( $html, 'wp-login.php' ) ), '<li' );
	return false === $pos ? $html . $li : substr_replace( $html, $li, $pos, 0 );
} );

// Aviso en la pantalla de acceso.
add_filter( 'login_message', function ( $message ) {
	if ( isset( $_GET['action'] ) && 'login' !== $_GET['action'] ) {
		return $message; // No en «Contraseña perdida», registro, etc.
	}
	return $message . '<p class="message">Personal del Ayuntamiento: inicie sesión para ver y gestionar las citas.<br>Vecinos: con su cuenta accederá a «Mis Reservas».</p>';
} );

// Tras iniciar sesión, cada uno a su sitio (si no venía de una página concreta).
add_filter( 'login_redirect', function ( $redirect_to, $requested, $user ) {
	if ( ! $user instanceof WP_User || ( $requested && admin_url() !== $requested ) ) {
		return $redirect_to;
	}
	if ( user_can( $user, ABANILLA_CITAS_CAPACIDAD ) ) {
		return home_url( ABANILLA_CITAS_URL );
	}

	global $wpdb;
	$es_conserje = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}bookly_staff WHERE wp_user_id = %d LIMIT 1", $user->ID ) );
	if ( $es_conserje ) {
		return admin_url( 'admin.php?page=bookly-calendar' );
	}

	return home_url( '/mis-reservas/' );
}, 10, 3 );

// Listado de citas.
add_shortcode( 'abanilla_citas', function () {
	if ( ! abanilla_citas_puede_ver() ) {
		return '<p>Esta página solo está disponible para administradores. <a href="' . esc_url( wp_login_url( get_permalink() ) ) . '">Acceder</a></p>';
	}

	global $wpdb;
	$p = $wpdb->prefix . 'bookly_';

	$estados = array(
		'pending'   => 'Pendiente',
		'approved'  => 'Aprobada',
		'rejected'  => 'Rechazada',
		'cancelled' => 'Cancelada',
		'done'      => 'Realizada',
	);
	$colores = array(
		'pending'   => '#1e73be',
		'approved'  => '#2e8b3a',
		'rejected'  => '#dd3333',
		'cancelled' => '#a08900',
		'done'      => '#666',
	);

	// Filtros (por GET, con valores por defecto: de hoy a 30 días).
	$hoy       = wp_date( 'Y-m-d' );
	$desde     = isset( $_GET['desde'] ) && preg_match( '/^\d{4}-\d{2}-\d{2}$/', $_GET['desde'] ) ? $_GET['desde'] : $hoy;
	$hasta     = isset( $_GET['hasta'] ) && preg_match( '/^\d{4}-\d{2}-\d{2}$/', $_GET['hasta'] ) ? $_GET['hasta'] : wp_date( 'Y-m-d', strtotime( '+30 days' ) );
	$estado    = isset( $_GET['estado'] ) && isset( $estados[ $_GET['estado'] ] ) ? $_GET['estado'] : '';
	$ubicacion = isset( $_GET['ubicacion'] ) ? absint( $_GET['ubicacion'] ) : 0;

	$ubicaciones = $wpdb->get_results( "SELECT id, name FROM {$p}locations ORDER BY position, name" );

	// Bookly guarda las fechas en la hora local del sitio.
	$where  = array( 'a.start_date >= %s', 'a.start_date < %s' );
	$params = array( $desde . ' 00:00:00', wp_date( 'Y-m-d', strtotime( $hasta . ' +1 day' ) ) . ' 00:00:00' );
	if ( $estado ) {
		$where[]  = 'ca.status = %s';
		$params[] = $estado;
	}
	if ( $ubicacion ) {
		$where[]  = 'a.location_id = %d';
		$params[] = $ubicacion;
	}

	$sql = "SELECT a.id, a.start_date, a.end_date, a.internal_note,
			s.title AS instalacion, l.name AS localidad,
			ca.status, ca.notes, c.full_name, c.phone, c.email
		FROM {$p}appointments a
		LEFT JOIN {$p}customer_appointments ca ON ca.appointment_id = a.id
		LEFT JOIN {$p}customers c ON c.id = ca.customer_id
		LEFT JOIN {$p}services s ON s.id = a.service_id
		LEFT JOIN {$p}locations l ON l.id = a.location_id
		WHERE " . implode( ' AND ', $where ) . '
		ORDER BY a.start_date, l.name, s.title
		LIMIT ' . ABANILLA_CITAS_MAX;

	$citas = $wpdb->get_results( $wpdb->prepare( $sql, $params ) );

	ob_start();
	?>
	<style>
		.ab-citas-filtros{display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;margin-bottom:20px}
		.ab-citas-filtros label{display:flex;flex-direction:column;font-size:14px;gap:4px}
		.ab-citas-filtros input,.ab-citas-filtros select{padding:6px 8px;min-width:150px}
		.ab-citas-filtros button{padding:8px 18px;background:#12276c;color:#fff;border:0;border-radius:3px;cursor:pointer}
		.ab-citas{width:100%;border-collapse:collapse;font-size:14px}
		.ab-citas th,.ab-citas td{padding:8px;border-bottom:1px solid #ddd;text-align:left;vertical-align:top}
		.ab-citas th{background:#f2f4f8}
		.ab-estado{display:inline-block;padding:2px 8px;border-radius:10px;color:#fff;font-size:12px;white-space:nowrap}
		@media (max-width:768px){
			.ab-citas thead{display:none}
			.ab-citas tr{display:block;border:1px solid #ddd;border-radius:4px;margin-bottom:12px;padding:6px}
			.ab-citas td{display:flex;gap:8px;border:0;padding:4px 6px}
			.ab-citas td::before{content:attr(data-label);font-weight:600;min-width:110px}
		}
	</style>

	<form class="ab-citas-filtros" method="get">
		<label>Desde <input type="date" name="desde" value="<?php echo esc_attr( $desde ); ?>"></label>
		<label>Hasta <input type="date" name="hasta" value="<?php echo esc_attr( $hasta ); ?>"></label>
		<label>Estado
			<select name="estado">
				<option value="">Todos</option>
				<?php foreach ( $estados as $clave => $nombre ) : ?>
					<option value="<?php echo esc_attr( $clave ); ?>" <?php selected( $estado, $clave ); ?>><?php echo esc_html( $nombre ); ?></option>
				<?php endforeach; ?>
			</select>
		</label>
		<label>Localidad
			<select name="ubicacion">
				<option value="0">Todas</option>
				<?php foreach ( $ubicaciones as $u ) : ?>
					<option value="<?php echo (int) $u->id; ?>" <?php selected( $ubicacion, (int) $u->id ); ?>><?php echo esc_html( $u->name ); ?></option>
				<?php endforeach; ?>
			</select>
		</label>
		<button type="submit">Filtrar</button>
	</form>

	<p><?php echo count( $citas ); ?> cita(s). <a href="<?php echo esc_url( admin_url( 'admin.php?page=bookly-appointments' ) ); ?>">Abrir en Bookly</a> para aprobar, rechazar o editar.</p>

	<?php if ( ! $citas ) : ?>
		<p>No hay citas con estos filtros.</p>
	<?php else : ?>
		<table class="ab-citas">
			<thead>
				<tr>
					<th>Fecha</th><th>Hora</th><th>Instalación</th><th>Localidad</th><th>Solicitante</th><th>Contacto</th><th>Estado</th><th>Notas</th><th>Nota interna</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ( $citas as $c ) :
				$inicio = strtotime( $c->start_date );
				$fin    = strtotime( $c->end_date );
				// Una cita sin cliente es un bloqueo hecho desde el panel.
				$estado_txt = $c->status ? ( $estados[ $c->status ] ?? $c->status ) : 'Bloqueo';
				$color      = $c->status ? ( $colores[ $c->status ] ?? '#666' ) : '#444';
				?>
				<tr>
					<td data-label="Fecha"><?php echo esc_html( date_i18n( 'D d/m/Y', $inicio ) ); ?></td>
					<td data-label="Hora"><?php echo esc_html( date( 'H:i', $inicio ) . ' – ' . date( 'H:i', $fin ) ); ?></td>
					<td data-label="Instalación"><?php echo esc_html( $c->instalacion ); ?></td>
					<td data-label="Localidad"><?php echo esc_html( $c->localidad ); ?></td>
					<td data-label="Solicitante"><?php echo esc_html( $c->full_name ?: '—' ); ?></td>
					<td data-label="Contacto">
						<?php if ( $c->phone ) : ?><a href="tel:<?php echo esc_attr( $c->phone ); ?>"><?php echo esc_html( $c->phone ); ?></a><br><?php endif; ?>
						<?php if ( $c->email ) : ?><a href="mailto:<?php echo esc_attr( $c->email ); ?>"><?php echo esc_html( $c->email ); ?></a><?php endif; ?>
					</td>
					<td data-label="Estado"><span class="ab-estado" style="background:<?php echo esc_attr( $color ); ?>"><?php echo esc_html( $estado_txt ); ?></span></td>
					<td data-label="Notas"><?php echo esc_html( $c->notes ); ?></td>
					<td data-label="Nota interna"><?php echo esc_html( $c->internal_note ); ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
	<?php
	return ob_get_clean();
} );
