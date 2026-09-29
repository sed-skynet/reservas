# Estado del proyecto — reservas.abanilla.es

Última actualización: 29/09/2026.
Material de referencia en esta carpeta: `MAPA_PANTALLAS.md` (dónde se edita cada pantalla), el volcado `6833647_wp_nrrvo.sql` (instantánea del 24/09/2026) y `buzonesExcel_6841129.xls` (buzones de abanilla.es).

---

## 1. Objetivo (ticket de Ángel Blasco, Jefe de Gabinete)

Simplificar la reserva de instalaciones antes de que se incorporen los conserjes:

- 10 instalaciones reservables, **de hora en hora**.
- Mecánica: **solicitud → llega al conserje → aprueba o deniega con observaciones**.
- Correo al conserje y al peticionario («su SOLICITUD será atendida en breve; mientras no reciba la aceptación NO se considera reservado»).
- Antelación mínima: **5 h** (de 06:00 a 22:00) y **8 h** (si se reserva después de las 22:00).
- Bloquear las horas que indiquen los conserjes (actividades municipales y de clubes).
- Formulario simplificado; banners por localidad que aterrizan en su localidad pero dejan cambiarla.
- Quitar referencias al pago, con la opción de reactivarlo.
- Arreglar «recordar contraseña» y que la cuenta dé acceso a «MIS RESERVAS».
- Prueba con el terminal de Ginés Moisés.

---

## 2. Resumen del estado

| Punto del ticket | Estado |
|---|---|
| Reservas de hora en hora | ✅ Hecho y comprobado |
| Formulario simplificado (solo Ubicación + Instalación) | ✅ Hecho y comprobado |
| Banners por localidad que dejan cambiar la localidad | ✅ Hecho (Abanilla, Mahoya, Barinas, Macisvenda) |
| Quitar referencias al pago | ✅ Hecho y comprobado |
| Antelación de 5 h | ✅ Hecho y comprobado de día |
| Antelación de 8 h después de las 22:00 | ✅ Instalado; falta comprobarlo de noche |
| Las reservas entran como solicitud pendiente | ✅ Hecho y comprobado |
| Correo al peticionario («NO se considera reservado») | ✅ Hecho y comprobado |
| Correo al conserje con Aprobar / Rechazar | ✅ Hecho y comprobado |
| Aprobar con observaciones | ✅ Hecho y comprobado |
| Denegar con motivo | 🟡 Configurado; falta la prueba |
| Que los correos lleguen (no a spam) | ✅ Resuelto (remitente @reservas.abanilla.es) |
| 10 instalaciones creadas, cada una con su calendario | ✅ Creadas con horario genérico; filtro por localidad comprobado |
| Reservar dos instalaciones distintas a la misma hora | 🟡 Falta la prueba |
| Página y banner de Piscina | ⏳ Pendiente |
| «Recordar contraseña» | ⏳ Falta probar (probablemente resuelto con el nuevo correo) |
| Crear cuenta → «MIS RESERVAS» | ⏳ Pendiente |
| Horarios reales, localidad del Campo de fútbol, conserjes | ⏳ Pendiente de la respuesta de Ángel |
| Bloqueos de horas ocupadas | ⏳ Pendiente de la lista de los conserjes |
| Prueba con Ginés Moisés | ⏳ Pendiente de su correo |
| Retirar El Cantón y Cañada de la Leña | ⏳ Pendiente de confirmación |

---

## 3. Cambios hechos, por pantalla

### 3.1 Bookly → Configuración

| Pantalla | Ajuste | Antes | Ahora |
|---|---|---|---|
| Pagos | Moneda | USD | **EUR** |
| Pagos | Servicio pagado localmente | Activado | Activado (necesario aunque el precio sea 0) |
| General | Longitud del intervalo de tiempo | 15 min | **1 h** |
| General | Longitud de la franja = duración del servicio | Desactivado | Desactivado |
| General | Tiempo mínimo antes de la reserva | Desactivado | **5 h** |
| WooCommerce | Integración con WooCommerce | Activado | **Desactivado** (se puede reactivar si algún día se cobra) |
| Citas | Estado por defecto de la cita | Aprobado | **Pendiente** |
| Citas | Estado de la cita pago correcto | — | **Pendiente** (evita aprobaciones automáticas) |
| Ubicaciones | Configuraciones personalizadas para la ubicación | Desactivado | Se activó durante las pruebas; **debe quedar en Desactivado** (comprobar) |

### 3.2 Bookly → Apariencia → Formulario paso a paso

- Mostrar barra de progreso: **desactivado** (quita «4. Pago» de la vista).
- Hacer obligatorio seleccionar trabajador: **no**.
- Mostrar el precio junto al nombre del empleado: **no**.
- Hacer que la selección de ubicación sea requerida: **sí**.
- Textos ya adaptados: «Instalación», «Por favor seleccione una instalación para reservar».

### 3.3 Bookly → Servicios (10 instalaciones)

Todos: duración **1 h**, intervalo **Predeterminado**, márgenes **apagados**, precio **0 €**. Visibilidad actual: **Público** (decidir si pasan a Privado hasta tener horarios reales).

| Servicio | Recurso (Bookly → Empleados) | Ubicación |
|---|---|---|
| Campo de fútbol | Ayuntamiento de Abanilla | Las 6 ubicaciones (pendiente de saber su localidad) |
| Pabellón Abanilla | Pabellón Abanilla | Abanilla |
| Pabellón Macisvenda | Pabellón Macisvenda | Macisvenda |
| Pabellón de Barinas | Pabellón de Barinas | Barinas |
| Pabellón de Mahoya | Pabellón de Mahoya | Mahoya |
| Pista Polideportiva Piscina | Pista Polideportiva Piscina | Piscina |
| Pista Frontenis Piscina | Pista Frontenis Piscina | Piscina |
| Pista Pádel Abanilla | Pista Pádel Abanilla | Abanilla |
| Pista de Pádel Barinas | Pista de Pádel Barinas | Barinas |
| Pista de Pádel Macisvenda | Pista de Pádel Macisvenda | Macisvenda |

**Por qué un «empleado» por instalación:** en Bookly, cada reserva necesita un empleado y un empleado no puede tener dos citas a la misma hora. Con un recurso por instalación:
- cada pista tiene su propio calendario, y una reserva no bloquea las demás;
- el aviso llega al correo del recurso, es decir, al conserje de esa instalación;
- los bloqueos se hacen en el calendario de cada recurso.

**Configuración de cada recurso:**
- Detalles → Ubicación: la suya.
- Servicios: solo su servicio. Al recurso «Ayuntamiento de Abanilla» se le dejó solo «Campo de fútbol»; tenía marcados los demás y por eso no filtraba por localidad.
- Programar: horario genérico, **lunes a viernes de 08:00 a 18:00**.
- Email: `suhaileddahmany2@gmail.com` (correo de prueba; se cambiará por el del conserje).

### 3.4 Bookly → Ubicaciones

Abanilla, Mahoya, Barinas, Macisvenda, El Cantón, Cañada de la Leña y **Piscina** (nueva). El Cantón y Cañada de la Leña siguen existiendo hasta que se confirme su retirada.

Comprobado en la web: al elegir una ubicación, solo salen sus instalaciones (más el Campo de fútbol, mientras siga en todas).

### 3.5 Bookly → Notificaciones por correo electrónico

| Notificación | Tipo | Estado de la cita | Destinatarios |
|---|---|---|---|
| Cliente – solicitud recibida (pendiente) | Nueva reserva | Pendiente | Cliente |
| Conserje – nueva solicitud (pendiente) | Nueva reserva | Pendiente | Personal (+ Personalizado durante las pruebas) |
| Cliente – reserva ACEPTADA | Cambio de estado | Aprobado | Cliente |
| Notificación a los clientes acerca de la designación rechazada (editada) | Cambio de estado | Rechazado | Cliente |

Las notificaciones antiguas de «cita aprobada» para nuevas reservas siguen activas. Ya no se disparan con las reservas web, que entran pendientes, pero sirven si se crea una cita aprobada desde el panel.

Códigos útiles: `{approve_appointment_url}`, `{reject_appointment_url}`, `{internal_note}` (observaciones del conserje), `{cancellation_reason}`, `{location_name}`.

<details>
<summary>Textos de los correos</summary>

**Cliente – solicitud recibida**. Asunto: `Solicitud de reserva recibida – {service_name} {appointment_date}`

```
Estimado/a {client_name}:

Hemos recibido su SOLICITUD de reserva:

Instalación: {service_name}
Localidad: {location_name}
Fecha: {appointment_date}
Hora: {appointment_time}

Su solicitud será atendida en breve por el personal de la instalación.

IMPORTANTE: mientras no reciba un correo de ACEPTACIÓN, esta solicitud NO se considera una reserva confirmada.

Si la solicitud es denegada, recibirá un correo con el motivo.

Ayuntamiento de Abanilla – Reservas
```

**Conserje – nueva solicitud**. Asunto: `NUEVA SOLICITUD – {service_name} {appointment_date} {appointment_time}`

```
<p>Nueva solicitud de reserva pendiente de validar:</p>
<p>Instalación: <strong>{service_name}</strong><br>
Localidad: {location_name}<br>
Fecha: <strong>{appointment_date}</strong><br>
Hora: <strong>{appointment_time} – {appointment_end_time}</strong></p>
<p>Solicitante: {client_name}<br>
Teléfono: {client_phone}<br>
Email: {client_email}<br>
Notas del solicitante: {client_note}</p>
<p><a href="{approve_appointment_url}"><strong>✅ APROBAR</strong></a> | <a href="{reject_appointment_url}"><strong>❌ RECHAZAR</strong></a></p>
<p>Si necesita indicar condiciones o el motivo del rechazo, abra la cita en Bookly → Citas, escriba el texto en «Nota interna» y cambie ahí el estado. El solicitante recibirá esa nota en su correo.</p>
```

**Cliente – reserva ACEPTADA**. Asunto: `Reserva ACEPTADA – {service_name} {appointment_date} {appointment_time}`

```
<p>Estimado/a {client_name}:</p>
<p>Su solicitud ha sido <strong>ACEPTADA</strong>. Su reserva queda confirmada:</p>
<p>Instalación: <strong>{service_name}</strong><br>
Localidad: {location_name}<br>
Fecha: <strong>{appointment_date}</strong><br>
Hora: <strong>{appointment_time} – {appointment_end_time}</strong></p>
<p>Observaciones / condiciones: {internal_note}</p>
<p>Si no puede asistir, le rogamos que cancele la reserva para dejar la instalación libre a otros vecinos.</p>
<p>Ayuntamiento de Abanilla – Reservas</p>
```

**Cliente – DENEGADA**. Asunto: `Solicitud de reserva DENEGADA – {service_name} {appointment_date}`

```
<p>Estimado/a {client_name}:</p>
<p>Lamentamos comunicarle que su solicitud de reserva ha sido <strong>DENEGADA</strong>:</p>
<p>Instalación: <strong>{service_name}</strong><br>
Localidad: {location_name}<br>
Fecha: {appointment_date}<br>
Hora: {appointment_time} – {appointment_end_time}</p>
<p>Motivo: <strong>{internal_note} {cancellation_reason}</strong></p>
<p>Puede realizar una nueva solicitud para otra fecha u hora en la web de reservas.</p>
<p>Ayuntamiento de Abanilla – Reservas</p>
```

</details>

### 3.6 Elementor: páginas de reserva

- `/reservar/`: formulario general, ya simplificado.
- Reservar Abanilla (44), Mahoya (52), Barinas (64) y Macisvenda (70): en el widget de Bookly, Ubicación preseleccionada **sin ocultar** y Servicio visible. Ocultos: Categoría, Personal, Duración, Fecha, Días de semana e Intervalo de tiempo.
  - Código resultante: `[bookly-form location_id="N" hide="categories,service_duration,staff_members,date,week_days,time_range"]`
- El Cantón (72) y Cañada de la Leña (77): sin tocar, pendientes de retirar.

### 3.7 WooCommerce

- Producto «Reservas» (176): **Visibilidad del catálogo = Oculto**. Sigue publicado y en «Pública»; no borrar, porque hace falta si se reactiva el pago.

### 3.8 Plugins

| Plugin | Estado | Para qué |
|---|---|---|
| **Abanilla – Antelación nocturna de reservas** (propio) | Activo | De 22:00 a 06:00 (hora de Madrid) sustituye el tiempo mínimo de Bookly por **8 h**. El resto del día manda el ajuste de Bookly (5 h). Código en `mu-plugins/abanilla-antelacion-nocturna.php`; paquete instalable `abanilla-antelacion.zip`. Para quitarlo: Plugins → Desactivar |
| **WP Mail SMTP** | Activo | Envío de todos los correos de la web por SMTP autenticado |
| FluentSMTP | Borrar si sigue instalado | Se probó y no hace falta |

### 3.9 Correo de la web

**Problema:** los correos no llegaban o iban a spam en Gmail. Salían sin autenticar desde `mpdc@skynet-sys.es` (correo de WordPress, sin SMTP) y, después, con un remitente de un dominio distinto del de la web.

**Solución aplicada:** el remitente es del **mismo dominio que la web**.

| Elemento | Valor |
|---|---|
| Remitente | `reservas@reservas.abanilla.es` («Reservas – Ayuntamiento de Abanilla») |
| Servidor SMTP | `smtp.dominioabsoluto.net`, puerto 587, TLS automático, con autenticación |
| Usuario SMTP | `reservas@reservas.abanilla.es` (buzón en Acens) |
| DNS de `reservas.abanilla.es` | Delegado a Acens (`ns10-12.servicio-online.net`), gestionado desde el panel de Acens |
| MX | `mx.reservas.abanilla.es` (Acens) |
| SPF | `v=spf1 redirect=spf.dominioabsoluto.net` |
| DKIM | Selector `domabs`, activado en Acens → Gestión de Correo → reservas.abanilla.es → DKIM |
| DMARC | `_dmarc.reservas.abanilla.es` → `v=DMARC1; p=none; rua=mailto:reservas@reservas.abanilla.es` (añadido; comprobar que ya se publica) |

Comprobado: mail-tester 10/10 y los correos llegan a la bandeja de entrada de Gmail.

**Datos de contexto, sin tocar:**
- `abanilla.es` (dominio principal) tiene el DNS en **Occentus** y el correo en **Microsoft 365**. Su SPF solo autoriza a Microsoft (`-all`) y no tiene DKIM ni DMARC. No se debe enviar como `@abanilla.es` desde la web sin que la informática del Ayuntamiento lo configure.
- En `skynet-sys.es` también se activó el DKIM de Acens (selector `domabs`).

---

## 4. Pruebas realizadas

| Prueba | Resultado |
|---|---|
| Huecos de hora en hora en el formulario | ✅ |
| Antelación de 5 h (hoy no deja reservar a menos de 5 h) | ✅ |
| Formulario simplificado en `/reservar/` y en la página de Abanilla | ✅ |
| Barra de pasos y «Pago» ocultos | ✅ |
| Reserva → estado Pendiente | ✅ |
| Correos de solicitud al cliente y al conserje | ✅ |
| Aprobar con Nota interna → correo «Reserva ACEPTADA» con observaciones | ✅ |
| Filtro por localidad (Abanilla, Mahoya, Piscina) | ✅ |
| Entrega de correo (mail-tester 10/10 y bandeja de entrada de Gmail) | ✅ |
| Rechazar (desde el enlace y desde el panel con motivo) | ✅ (confirmado por Suhail) |
| Dos instalaciones distintas a la misma hora | ✅ (confirmado por Suhail) |
| Antelación de 8 h después de las 22:00 | ✅ (confirmado por Suhail) |
| Revisión externa de la web (29/09): formularios, menú, tarjetas, tienda vacía, DMARC publicado | ✅ |
| Filtro en Barinas y Macisvenda | ⏳ |

---

## 5. Pendiente que se puede hacer ya

1. **Prueba de reservas a la misma hora:** Pabellón Abanilla y Pista Pádel Abanilla mañana a las 10:00 (debe dejar); repetir el Pabellón (no debe dejar). Borrar las citas de prueba.
2. **Prueba de rechazo:** desde el enlace ❌ del correo y desde el panel con Nota interna. Revisar qué sale en «Motivo».
3. **Comprobar** que en Bookly → Configuración → Ubicaciones, «Configuraciones personalizadas para la ubicación» ha vuelto a **Desactivado**.
4. **Página de Piscina:** duplicar «Reservar Abanilla» en Elementor, poner la ubicación Piscina y añadir su tarjeta en Inicio y su enlace en el menú.
5. **«Recordar contraseña»:** probar en `/wp-login.php?action=lostpassword`.
6. **«Mis Reservas»** (en curso). Hallazgos del 29/09:
   - Sin sesión, la página muestra un formulario de acceso **sin** enlace de «¿Has olvidado tu contraseña?» ni de registro.
   - El registro de WordPress está desactivado; las cuentas se crean al hacer la primera reserva (Bookly) o desde Mi cuenta de WooCommerce, que no se vincula a Bookly.
   - El área de cliente mostraba precio, empleado, categoría y duración, y permitía reprogramar sin pasar por el conserje.

   Cambio propuesto en **Páginas → Mis Reservas → Editar → pestaña Texto** (sustituye todo el contenido):
   ```
   [bookly-customer-cabinet tabs="appointments,profile" appointments="date,location,service,status,cancel,reason" profile="name,email,phone,wp_password"]

   <p style="text-align:center;"><a href="/wp-login.php?action=lostpassword">¿Has olvidado tu contraseña?</a></p>

   <p style="text-align:center;">¿Aún no tienes cuenta? Se crea automáticamente al hacer tu primera reserva y recibirás los datos de acceso por correo. <a href="/reservar/">Hacer una reserva</a></p>
   ```
   Contenido anterior, por si hay que volver atrás: `[bookly-customer-cabinet tabs="appointments,profile" appointments="filters,date,timezone,location,category,service,staff,duration,price,status,cancel,reason,reschedule" profile="name,email,phone,birthday,address,full_address,wp_password,delete"]`

   Después: probar la recuperación de contraseña con el correo de la reserva de prueba, entrar y comprobar que Mis Reservas lista las reservas con su estado.
7. **Formulario de datos:** quitar la dirección y la fecha de nacimiento, ahora obligatorias (Bookly → Configuración → Clientes y Apariencia → 3. Detalles).
8. **Remates:**
   - Bookly → Notificaciones → Configuración: remitente `reservas@reservas.abanilla.es`.
   - Añadir la `}` al asunto del correo del conserje si aún falta.
   - Borrar FluentSMTP.
9. **Visibilidad:** decidir si las instalaciones siguen públicas con el horario genérico o pasan a Privado hasta tener los horarios reales.
10. **Guía para conserjes** (una página): cómo aprobar o denegar desde el móvil. Recordar que hay que marcar «Enviar notificaciones» al cambiar el estado desde el panel.

---

## 6. Pendiente de la respuesta de Ángel

Correo enviado con estas preguntas:

1. Horario de cada instalación (días, horas y si cambia entre verano e invierno).
2. Localidad del Campo de fútbol; confirmar que las pistas de la Piscina están en Abanilla.
3. Conserje responsable de cada instalación y su correo.
4. Correo de Ginés Moisés y qué instalaciones gestionará en la prueba.
5. Horas ocupadas por actividades y clubes: cuáles son semanales y cuáles sueltas; quién las introduce.
6. Antelación: si vale 5 h + 8 h nocturnas y cuál es el «primer horario elegible» de cada instalación.
7. Duración: si siempre es 1 h o se permiten varias horas seguidas; límites por persona.
8. Qué pasa si el conserje no contesta a tiempo; si hay suplente o buzón de copia.
9. Textos de condiciones y normas de uso para los correos.
10. Cancelación por el usuario: si se permite y hasta cuándo.
11. Datos que se piden al solicitante (DNI, club, número de personas…) y si hace falta cuenta para reservar.
12. Iluminación: si se pide aparte.
13. Hasta cuándo se puede reservar (ahora 365 días; propuesta: 15 o 30).
14. Confirmar que todo es gratuito y que se retiran El Cantón y Cañada de la Leña.
15. Fechas: incorporación de conserjes, prueba con Ginés y apertura al público.

**Cuando llegue la respuesta:**
- Programar el horario real en cada recurso.
- Poner el correo del conserje en cada recurso.
- Dejar el Campo de fútbol solo en su localidad.
- Introducir los bloqueos: citas internas para bloqueos puntuales, descansos en el horario para los semanales y días libres para los cierres.
- Retirar El Cantón y Cañada de la Leña (ubicaciones, páginas, menú y tarjetas de Inicio).
- Quitar la copia «Personalizado» del correo del conserje.

---

## 7. Cómo se bloquean las horas ocupadas (para cuando lleguen)

| Caso | Dónde |
|---|---|
| Bloqueo puntual (un día y unas horas concretas) | Bookly → Calendario → cita en el recurso de la instalación (o Bookly → Citas → Nueva cita), sin cliente o con un cliente interno «Bloqueo», estado Aprobado y **sin** enviar notificaciones |
| Bloqueo semanal (todos los martes de 18 a 20) | Bookly → Empleados → recurso → Programar → «Añadir descanso» en ese día |
| Cierre de días completos | Bookly → Empleados → recurso → Días libres |

---

## 8. Incidencias y notas

- La cita 2 del volcado (Mahoya, 24/09/2026 17:00, nota «Quiero Luces») ya no aparece en Bookly → Citas; alguien la borró después del volcado.
- Una reserva real pedía iluminación («Quiero Luces»): conviene saber si se gestiona aparte.
- El plugin UnderConstruction aparece en la barra de administración; revisar su estado antes de abrir al público.
- No se incluyen contraseñas en este documento.
