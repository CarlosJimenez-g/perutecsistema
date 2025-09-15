document.addEventListener("DOMContentLoaded", () => {
    const checkboxes = document.querySelectorAll(".chk-producto");
    const cantidades = document.querySelectorAll(".cantidad");
    const totalDisplay = document.getElementById("total");

    function calcularTotal() {
        let total = 0;
        checkboxes.forEach(chk => {
            const id = chk.value;
            const cantidadInput = document.querySelector(`.cantidad[data-id='${id}']`);
            const precio = parseFloat(document.querySelector(`.precio[data-id='${id}']`).textContent);
            const cantidad = parseInt(cantidadInput.value) || 0;

            let subtotal = 0;
            if (chk.checked && cantidad > 0) {
                subtotal = cantidad * precio;
            }

            document.getElementById(`subtotal-${id}`).textContent = subtotal.toFixed(2);
            total += subtotal;
        });

        totalDisplay.textContent = total.toFixed(2);
    }

    checkboxes.forEach(chk => chk.addEventListener("change", calcularTotal));
    cantidades.forEach(input => input.addEventListener("input", calcularTotal));
});
