<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Pedidos</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <h2>Sistema de Pedidos</h2>
    <hr>
    <h3>Clientes</h3>

    <button id="btnListarClientes">Listar Todos</button>

    | Buscar por cédula:
    <input type="text" id="txtcedulaBuscar" placeholder="Ingrese la cédula">
    <button id="btnBuscarCliente">Buscar</button>

    | <button id="btnVerPedidos">Ver Pedidos del Cliente</button>

    <br><br>
    <div id="resultadoClientes">
        <p>Presione "Listar Todos" o busque por cédula.</p>
    </div>

    <hr>
    <h3>Registrar Nuevo Pedido</h3>

    Cédula del cliente: <input type="text" id="txtcedulaPedido" placeholder="Ej: 1850972280">
    <br><br>

    Producto:
    <select id="selectProducto">
        <option value="">-- Seleccione --</option>
    </select>
    <br><br>

    <button id="btnRegistrarPedido">Registrar Pedido</button>

    <p id="mensajePedido"></p>


    <script>

        const API_CLIENTES  = "http://localhost:8000/api/clientes";
        const API_PRODUCTOS = "http://localhost:8000/api/productos";
        const API_PEDIDOS   = "http://localhost:8000/api/pedidos";

        let clienteSeleccionado = null;

        function formatearFecha(fecha) {
            const date = new Date(fecha);
            const dia = String(date.getDate()).padStart(2, '0');
            const mes = String(date.getMonth() + 1).padStart(2, '0');
            const año = date.getFullYear();
            const hora = String(date.getHours()).padStart(2, '0');
            const minuto = String(date.getMinutes()).padStart(2, '0');
            const segundo = String(date.getSeconds()).padStart(2, '0');
            return `${dia}/${mes}/${año} ${hora}:${minuto}:${segundo}`;
        }
        function renderTablaClientes(lista) {
            if (!lista.length) {
                $("#resultadoClientes").html("<p>No se encontraron clientes.</p>");
                return;
            }

            let tabla = `
                <table border="1" cellpadding="5">
                    <thead>
                        <tr>
                            <th>Cédula</th>
                            <th>Apellido</th>
                            <th>Nombre</th>
                        </tr>
                    </thead>
                    <tbody>`;

            lista.forEach(c => {
                tabla += `
                    <tr data-cedula="${c.cedula}" data-nombre="${c.nombre}" data-apellido="${c.apellido}">
                        <td>${c.cedula}</td>
                        <td>${c.apellido}</td>
                        <td>${c.nombre}</td>
                    </tr>`;
            });

            tabla += `</tbody></table>`;
            $("#resultadoClientes").html(tabla);
        }

        // GET /api/clientes
        $("#btnListarClientes").click(function () {
            $.ajax({
                url: API_CLIENTES,
                type: "GET",
                dataType: "json",
                success: function (response) {
                    renderTablaClientes(response);
                },
                error: function (xhr, status, error) {
                    alert("Error al cargar clientes");
                }
            });
        });

        // GET /api/clientes/buscar/{cedula}
        $("#btnBuscarCliente").click(function () {
            const cedula = $("#txtcedulaBuscar").val().trim();

            if (!cedula) {
                alert("Ingrese una cédula para buscar.");
                return;
            }

            $.ajax({
                url: API_CLIENTES + "/buscar/" + cedula,
                type: "GET",
                dataType: "json",
                success: function (response) {
                    if (!response || response.message) {
                        alert(response?.message || "Cliente no encontrado.");
                        $("#resultadoClientes").html("<p>No se encontró ningún cliente.</p>");
                        return;
                    }
                    renderTablaClientes([response]);
                },
                error: function (xhr, status, error) {
                    const msg = xhr.responseJSON?.message || "Cliente no encontrado.";
                    alert(msg);
                    $("#resultadoClientes").html("<p>No se encontró ningún cliente.</p>");
                }
            });
        });

        // GET /api/clientes/{cedula}/pedidos
        $("#btnVerPedidos").click(function () {
            const cedula = $("#txtcedulaBuscar").val().trim();

            if (!cedula) {
                alert("Ingrese una cédula para ver los pedidos.");
                return;
            }

            $.ajax({
                url: API_CLIENTES + "/" + cedula + "/pedidos",
                type: "GET",
                dataType: "json",
                success: function (response) {
                    if (!response.pedidos.length) {
                        $("#resultadoClientes").html("<p>" + response.cliente + " no tiene pedidos.</p>");
                        return;
                    }

                    let tabla = "<p><b>Pedidos de: " + response.cliente + "</b></p>";
                    tabla += `
                        <table border="1" cellpadding="5">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Producto</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>`;

                    response.pedidos.forEach((p, i) => {
                        tabla += `
                            <tr>
                                <td>${i + 1}</td>
                                <td>${p.producto.nombre}</td>
                                <td>${formatearFecha(p.created_at)}</td>
                            </tr>`;
                    });

                    tabla += `</tbody></table>`;
                    $("#resultadoClientes").html(tabla);
                },
                error: function (xhr, status, error) {
                    const msg = xhr.responseJSON?.message || "Error al cargar los pedidos.";
                    alert(msg);
                }
            });
        });

        $("#txtcedulaBuscar").keypress(function (e) {
            if (e.which === 13) $("#btnBuscarCliente").click();
        });

        $.ajax({
            url: API_PRODUCTOS,
            type: "GET",
            dataType: "json",
            success: function (response) {
                let opts = '<option value="">-- Seleccione --</option>';
                response.forEach(p => {
                    opts += `<option value="${p.nombre}">${p.nombre}</option>`;
                });
                $("#selectProducto").html(opts);
            },
            error: function (xhr, status, error) {
                console.error("Error al cargar productos");
            }
        });

        $("#btnRegistrarPedido").click(function () {
            const cedula          = $("#txtcedulaPedido").val().trim();
            const nombre_producto = $("#selectProducto").val();

            if (!cedula || !nombre_producto) {
                alert("Ingrese la cédula y seleccione un producto.");
                return;
            }

            $.ajax({
                url: API_PEDIDOS,
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify({
                    cedula:          cedula,
                    nombre_producto: nombre_producto,
                }),
                dataType: "json",
                success: function (response) {
                    $("#mensajePedido").text("✅ Pedido registrado: " + response.cliente.apellido + " " + response.cliente.nombre + " — " + response.producto.nombre);
                    $("#txtcedulaPedido").val("");
                    $("#selectProducto").val("");
                },
                error: function (xhr, status, error) {
                    const msg = xhr.responseJSON?.message || "No se pudo registrar el pedido.";
                    $("#mensajePedido").text("❌ Error: " + msg);
                }
            });
        });

    </script>
</body>
</html>