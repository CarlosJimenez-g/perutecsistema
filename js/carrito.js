let carrito = [];
let descuento = 0;
let costoAdicional = 0;

function agregarCarrito(codigo, nombre, precio) {
    let inputCantidad = document.getElementById("cant_" + codigo);
    let cantidad = parseInt(inputCantidad.value);
    let maxStock = parseInt(inputCantidad.getAttribute("max")) || 0;

    if (maxStock <= 0) {
        alert("Este producto no tiene stock disponible.");
        return;
    }

    if (cantidad <= 0 || cantidad > maxStock) {
        alert("Cantidad inválida. Stock disponible: " + maxStock);
        return;
    }

    let existe = carrito.find(p => p.codigo === codigo);
    if (existe) {
        // 🔹 Usamos el stock guardado en el carrito
        if (existe.cantidad + cantidad > existe.maxStock) {
            alert("⚠️ Solo hay " + existe.maxStock + " unidades disponibles en stock.");
            return;
        }
        existe.cantidad += cantidad;
    } else {
        carrito.push({ 
            codigo, 
            nombre, 
            cantidad, 
            precio_unitario: precio,
            maxStock: maxStock   // 🔹 Guardamos el stock máximo
        });
    }
    renderCarrito();
}

function quitarCarrito(codigo) {
    carrito = carrito.filter(p => p.codigo !== codigo);
    renderCarrito();
}

function aplicarDescuento() {
    descuento = parseFloat(document.getElementById("descuento").value) || 0;
    renderCarrito();
}

function aplicarCostoAdicional() {
    costoAdicional = parseFloat(document.getElementById("costo_adicional").value) || 0;
    renderCarrito();
}

function renderCarrito() {
    let tbody = document.getElementById("carrito");
    tbody.innerHTML = "";
    let total = 0;

    carrito.forEach(p => {
        let subtotal = p.cantidad * p.precio_unitario;
        total += subtotal;

        let row = `<tr>
            <td>${p.codigo}</td>
            <td>${p.nombre}</td>
            <td>${p.cantidad}</td>
            <td>${p.precio_unitario.toFixed(2)}</td>
            <td>${subtotal.toFixed(2)}</td>
            <td><button type="button" onclick="quitarCarrito('${p.codigo}')">Quitar</button></td>
        </tr>`;
        tbody.innerHTML += row;
    });

    total = total - descuento + costoAdicional;
    if (total < 0) total = 0;

    document.getElementById("total").textContent = total.toFixed(2);
    document.getElementById("descuento_valor").value = descuento;
    document.getElementById("costo_adicional_valor").value = costoAdicional;
}

function prepararDatos() {
    document.getElementById("carrito_data").value = JSON.stringify(carrito);
    return true;
}

function filtrarProductos() {
    let filtro = document.getElementById("buscar").value.toLowerCase();
    let filas = document.querySelectorAll("#listaProductos tr");
    filas.forEach(fila => {
        let texto = fila.textContent.toLowerCase();
        fila.style.display = texto.includes(filtro) ? "" : "none";
    });
}
