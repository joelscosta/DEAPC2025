document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('bookingForm');

  form.addEventListener('submit', (event) => {
    event.preventDefault();

    let valido = true;

    // Função auxiliar para marcar erro
    function marcarErro(campo, mensagem) {
      campo.classList.add('erro');
      campo.focus();
      alert(mensagem);
      valido = false;
    }

    // Limpar estilos de erro anteriores
    form.querySelectorAll('.erro').forEach(el => el.classList.remove('erro'));

    // Valida Nome
    const nome = form.nome;
    if (!nome.value.trim()) {
      marcarErro(nome, 'Por favor, preencha o nome.');
    }

    // Valida Email
    const email = form.email;
    if (!email.value.trim()) {
      marcarErro(email, 'Por favor, preencha o email.');
    } else {
      // valida formato do email
      const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!regexEmail.test(email.value.trim())) {
        marcarErro(email, 'Por favor, insira um email válido.');
      }
    }

    // Telefone não obrigatório, mas se preenchido, valida formato básico
    const telefone = form.telefone;
    if (telefone.value.trim()) {
      // exemplo de validação simples de telefone: só números e 8 a 15 dígitos
      const regexTel = /^[0-9]{8,15}$/;
      if (!regexTel.test(telefone.value.trim())) {
        marcarErro(telefone, 'Telefone inválido. Use apenas números (8 a 15 dígitos).');
      }
    }

    // Data da reserva - obrigatória
    const data = form.data;
    if (!data.value) {
      marcarErro(data, 'Por favor, selecione a data da reserva.');
    }

    // Hora - obrigatória
    const hora = form.hora;
    if (!hora.value) {
      marcarErro(hora, 'Por favor, selecione a hora da reserva.');
    }

    // Serviço - select, assume que sempre tem valor selecionado (pode validar se quiser)

    // Cartão de Crédito - obrigatório, 16 dígitos
    const cartao = form.cartao;
    if (!cartao.value.trim()) {
      marcarErro(cartao, 'Por favor, preencha o número do cartão de crédito.');
    } else {
      const regexCartao = /^\d{16}$/;
      if (!regexCartao.test(cartao.value.trim())) {
        marcarErro(cartao, 'Número do cartão deve conter exatamente 16 dígitos.');
      }
    }

    // Código de Segurança - obrigatório, 3 ou 4 dígitos
    const codigo = form.codigo;
    if (!codigo.value.trim()) {
      marcarErro(codigo, 'Por favor, preencha o código de segurança.');
    } else {
      const regexCodigo = /^\d{3,4}$/;
      if (!regexCodigo.test(codigo.value.trim())) {
        marcarErro(codigo, 'Código de segurança deve conter 3 ou 4 dígitos.');
      }
    }

    if (valido) {
      alert('Reserva enviada com sucesso!');
      form.submit();
    }
  });
});
