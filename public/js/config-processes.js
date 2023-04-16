const changeState = (checkbox, p) => {
    let state = document.getElementById(p);
    if (checkbox.checked) {
        state.innerText = "Aberto";
    } else {
        state.innerText = "Fechado";
    }
}