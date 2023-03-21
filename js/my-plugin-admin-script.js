const buttons = document.querySelectorAll("#manual_currency_field_container button")
buttons.forEach((button) => {
  button.addEventListener('click', event => event.preventDefault());
});
