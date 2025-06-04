document.getElementById('loginForm').addEventListener('submit', function(e){
  e.preventDefault();

  const user = document.getElementById('username').value.trim();
  const pass = document.getElementById('password').value.trim();
  const msg = document.getElementById('message');

  if(user === '' || pass === ''){
    msg.textContent = 'Por favor, preencha todos os campos.';
    msg.style.color = 'red';
    return;
  }

  // Pega usuários cadastrados do localStorage
  let users = JSON.parse(localStorage.getItem('users')) || {};

  if(users[user]){
    // Usuário existe - tenta login
    if(users[user] === pass){
      msg.textContent = 'Login efetuado com sucesso!';
      msg.style.color = 'green';
      // Salva usuário logado
      localStorage.setItem('loggedUser', user);
      // Redireciona para página reservas
      setTimeout(() => { window.location.href = 'reservas.html'; }, 1000);
    } else {
      msg.textContent = 'Senha incorreta.';
      msg.style.color = 'red';
    }
  } else {
    // Registra novo usuário
    users[user] = pass;
    localStorage.setItem('users', JSON.stringify(users));
    msg.textContent = 'Usuário registrado com sucesso! Faça login novamente.';
    msg.style.color = 'green';
  }
});
