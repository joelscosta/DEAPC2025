
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('formLogin');

  form.addEventListener('submit', function(event) {
    event.preventDefault();

    let valido = true;

    const username = form.username;
    if (username.value.trim() === '') {
      valido = false;
      username.classList.add('erro');
      username.setCustomValidity('Por favor, preencha o nome de usuário.');
      username.reportValidity();
    } else {
      username.classList.remove('erro');
      username.setCustomValidity('');
    }

    const password = form.password;
    if (password.value.trim() === '') {
      valido = false;
      password.classList.add('erro');
      password.setCustomValidity('Por favor, preencha a senha.');
      password.reportValidity();
    } else {
      password.classList.remove('erro');
      password.setCustomValidity('');
    }

    if (valido) {
      form.submit();
    }
  });
});
