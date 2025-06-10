const user = localStorage.getItem('loggedUser');
if(!user){
  alert('Você não está logado.');
  window.location.href = 'login.html';
}

// Exemplo: reservas salvas no localStorage como {usuario: [{viagem, data}, ...]}
let reservas = JSON.parse(localStorage.getItem('reservas')) || {};
let userReservas = reservas[user] || [];

const hoje = new Date();

function separarReservas(){
  const passadas = [];
  const presentes = [];
  const futuras = [];

  userReservas.forEach(r => {
    const dataReserva = new Date(r.data);
    if(dataReserva < hoje.setHours(0,0,0,0)) passadas.push(r);
    else if(dataReserva.toDateString() === hoje.toDateString()) presentes.push(r);
    else futuras.push(r);
  });

  return {passadas, presentes, futuras};
}

function mostrarReservas(){
  const {passadas, presentes, futuras} = separarReservas();

  const passadasUL = document.getElementById('passadas');
  const presentesUL = document.getElementById('presentes');
  const futurasUL = document.getElementById('futuras');

  passadasUL.innerHTML = passadas.map(r => `<li>${r.viagem} - ${r.data}</li>`).join('') || '<li>Nenhuma reserva passada</li>';
  presentesUL.innerHTML = presentes.map(r => `<li>${r.viagem} - ${r.data}</li>`).join('') || '<li>Nenhuma reserva para hoje</li>';
  futurasUL.innerHTML = futuras.map(r => `<li>${r.viagem} - ${r.data}</li>`).join('') || '<li>Nenhuma reserva futura</li>';
}

document.getElementById('logoutBtn').addEventListener('click', () => {
  localStorage.removeItem('loggedUser');
  window.location.href = 'login.html';
});

mostrarReservas();
