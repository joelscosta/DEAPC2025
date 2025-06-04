document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('formLogin');

  form.addEventListener('submit', (event) => {
    event.preventDefault();

    let valido = true;

    // Limpa os estilos de erro anteriores
    form.querySelectorAll('.erro').forEach(el => el.classList.remove('erro'));

    const username = form.username;
    const password = form.password;

    if (!username.value.trim()) {
      username.classList.add('erro');
      alert('Por favor, preencha o nome de usuário.');
      username.focus();
      valido = false;
      return; // para não mostrar múltiplos alertas ao mesmo tempo
    }

    if (!password.value.trim()) {
      password.classList.add('erro');
      alert('Por favor, preencha a senha.');
      password.focus();
      valido = false;
      return;
    }

    if (valido) {
      form.submit();
    }
  });
});
