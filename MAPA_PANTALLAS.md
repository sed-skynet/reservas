# Mapa de pantallas de reservas.abanilla.es

Revisión de la web pública y del panel de WordPress: 24 de septiembre de 2026. Este mapa sirve para localizar dónde editar cada elemento. No se han realizado cambios en la web.

## Regla rápida

| Elemento visible | Dónde se cambia |
|---|---|
| Texto, imágenes, botones y tarjetas de la portada | **Páginas → Inicio → Editar con Elementor** |
| Formulario incrustado en una página de reserva | **Páginas → página correspondiente → Editar con Elementor → widget de Bookly** |
| Nombre, duración, precio y disponibilidad de «Campo de fútbol» | **Bookly → Servicios**; horarios del personal en **Bookly → Empleados** |
| Nombres de las seis localidades | **Bookly → Ubicaciones** |
| Pasos, textos y aspecto interno del formulario | **Bookly → Apariencia** y **Bookly → Configuración** |
| Imagen de fondo detrás del título de una localidad | **Páginas → localidad → Editar** en WordPress → opciones de **Betheme / Subheader image**. Está fuera del lienzo de Elementor |
| Título y ruta de navegación sobre las páginas | Título/jerarquía de la página en WordPress y opciones de subcabecera de **Betheme** |
| Logotipo, colores y estructura de la cabecera y el pie | **Betheme → Theme Options** (`/wp-admin/admin.php?page=be-options`) |
| Enlaces «Inicio», «¿Dónde reservar?», «Acceder», etc. | **Apariencia → Menús** (`/wp-admin/nav-menus.php`) |
| Tienda, producto, carrito, compra y cuenta de WooCommerce | **WooCommerce** y sus páginas/bloques; no son diseños de Elementor |
| Aviso legal y políticas | **Páginas → Editar** con el editor de WordPress |

La plantilla de Elementor titulada **«Cabecera»** (ID 108) existe, pero la cabecera pública inspeccionada la genera **Betheme**; editar esa plantilla no parece cambiar la cabecera actual.

## Páginas publicadas

Hay 16 páginas publicadas. Los enlaces de «Editar» requieren iniciar sesión en el panel.

| Pantalla | Dirección pública | Edición principal | Qué contiene |
|---|---|---|---|
| Inicio | `/` | [Elementor, página 2](https://reservas.abanilla.es/wp-admin/post.php?post=2&action=elementor) | Hero, texto, botón «Haz tu reserva» y seis tarjetas de localidades. Las tarjetas usan widgets Promo Box de Royal Elementor Addons. |
| Reservar, formulario general | `/reservar/` | [Elementor, página 36](https://reservas.abanilla.es/wp-admin/post.php?post=36&action=elementor) | Widget de Bookly sin localidad fija; el visitante la elige. |
| Reservar Abanilla | `/reservar/reservar-abanilla/` | [Elementor, página 44](https://reservas.abanilla.es/wp-admin/post.php?post=44&action=elementor) | Widget de Bookly con ubicación **1**. |
| Reservar Mahoya | `/reservar/reservar-mahoya/` | [Elementor, página 52](https://reservas.abanilla.es/wp-admin/post.php?post=52&action=elementor) | Widget de Bookly con ubicación **2**. |
| Reservar Barinas | `/reservar/reservar-barinas/` | [Elementor, página 64](https://reservas.abanilla.es/wp-admin/post.php?post=64&action=elementor) | Widget de Bookly con ubicación **3**. |
| Reservar Macisvenda | `/reservar/reservar-macisvenda/` | [Elementor, página 70](https://reservas.abanilla.es/wp-admin/post.php?post=70&action=elementor) | Widget de Bookly con ubicación **4**. |
| Reservar El Cantón | `/reservar/reservar-el-canton/` | [Elementor, página 72](https://reservas.abanilla.es/wp-admin/post.php?post=72&action=elementor) | Widget de Bookly con ubicación **5**. |
| Reservar Cañada de la Leña | `/reservar/reservar-canada-de-la-lena/` | [Elementor, página 77](https://reservas.abanilla.es/wp-admin/post.php?post=77&action=elementor) | Widget de Bookly con ubicación **6**. |
| Mis Reservas | `/mis-reservas/` | [WordPress, página 105](https://reservas.abanilla.es/wp-admin/post.php?post=105&action=edit) + Bookly Customer Cabinet | Acceso y gestión de citas del cliente. El contenido es un shortcode de Bookly. |
| Tienda | `/tienda/` | [WordPress, página 12](https://reservas.abanilla.es/wp-admin/post.php?post=12&action=edit) + WooCommerce | Catálogo con el producto «Reservas». |
| Carrito | `/carrito/` | [WordPress, página 13](https://reservas.abanilla.es/wp-admin/post.php?post=13&action=edit) + WooCommerce | Bloque de carrito. |
| Finalizar compra | `/finalizar-compra/` | [WordPress, página 14](https://reservas.abanilla.es/wp-admin/post.php?post=14&action=edit) + WooCommerce | Bloque de checkout. Con el carrito vacío redirige a `/carrito/`. |
| Mi cuenta | `/mi-cuenta/` | [WordPress, página 15](https://reservas.abanilla.es/wp-admin/post.php?post=15&action=edit) + WooCommerce | Acceso, registro y área de pedidos de WooCommerce. Es distinta de «Mis Reservas» de Bookly. |
| Política de privacidad | `/politica-de-privacidad/` | [WordPress, página 3](https://reservas.abanilla.es/wp-admin/post.php?post=3&action=edit) | Texto legal. |
| Política de cookies | `/politica-de-cookies/` | [WordPress, página 150](https://reservas.abanilla.es/wp-admin/post.php?post=150&action=edit) | Texto legal. |
| Aviso legal | `/aviso-legal/` | [WordPress, página 152](https://reservas.abanilla.es/wp-admin/post.php?post=152&action=edit) | Texto legal. |

También existe la pantalla pública del [producto «Reservas»](https://reservas.abanilla.es/producto/reservas/), editable en [WooCommerce, producto 176](https://reservas.abanilla.es/wp-admin/post.php?post=176&action=edit). Se muestra en la tienda a 0 € y permite «Añadir al carrito» directamente.

## Recorrido de una reserva

1. **Servicio:** se elige la localidad y «Campo de fútbol». Las páginas locales muestran ya seleccionada su localidad. La página general deja elegirla.
2. **Hora:** Bookly presenta calendario y huecos disponibles de 15 minutos.
3. **Detalles:** pide nombre, apellidos, teléfono, correo y confirmación, dirección, notas y aceptación de términos. Ofrece iniciar sesión.
4. **Pago / Hecho:** el indicador inicial puede mostrar «Pago», pero para la reserva gratuita observada pasa de «Detalles» a «Hecho». Bookly está integrado con WooCommerce, incluso para pedidos de importe cero.

El servicio, su personal y sus horarios se editan en Bookly. Cambiar el diseño de una página con Elementor no cambia las reglas de disponibilidad. El perfil de cliente de Bookly no es la misma pantalla que la cuenta de WooCommerce. Un usuario de WordPress sin ficha de cliente asociada recibe un aviso en «Mis Reservas».

## Puntos detectados para futuras ediciones

- **Barinas funciona con la ubicación 3 en la web pública.** El widget de Elementor guarda `location_id=3`. Hay un shortcode antiguo en el contenido clásico de la página que dice `location_id=1`; no es el formulario que se está mostrando. Si se reemplaza el diseño de Elementor por el editor clásico, habría que corregir ese shortcode.
- **Monedas distintas:** Bookly tiene USD configurado y el paso de detalles muestra «$0,00»; WooCommerce usa EUR y la tienda muestra «0,00€». El ajuste de Bookly está en **Bookly → Configuración → Pagos**.
- **Producto accesible fuera de la reserva:** «Reservas» aparece en la tienda y puede añadirse al carrito directamente. Si se quiere obligar a iniciar siempre desde Bookly, revisar la visibilidad del producto en WooCommerce.
- **Un solo empleado para las seis ubicaciones:** todas las citas usan «Ayuntamiento de Abanilla». Antes de permitir reservas simultáneas en distintos lugares, conviene comprobar las reglas de disponibilidad de Bookly.
- **Cabecera y pie compartidos:** un cambio en Betheme o en el menú afecta a todas las páginas. La imagen de subcabecera es individual para cada página local.

La inspección se hizo sin crear reservas ni modificar el sitio.
