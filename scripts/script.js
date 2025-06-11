// script.js

const API_BASE_URL = 'http://localhost:8000/scripts/';
const messageDiv = document.getElementById('message');
const destinationsContainer = document.getElementById('destinationsContainer');
const authFormsDiv = document.getElementById('authForms');
const loggedInUserSpan = document.getElementById('loggedInUser');
const loginRegisterBtn = document.getElementById('loginRegisterBtn');
const customerAreaBtn = document.getElementById('customerAreaBtn');
const logoutBtn = document.getElementById('logoutBtn');
const customerAreaDiv = document.getElementById('customerArea');
const futureBookingsListDiv = document.getElementById('futureBookingsList');
const pastBookingsListDiv = document.getElementById('pastBookingsList');
const customerAreaTitle = document.getElementById('customerAreaTitle');

const bookingModal = document.getElementById('bookingModal');
const modalDestinationName = document.getElementById('modalDestinationName');
const modalTravelDate = document.getElementById('modalTravelDate');
const modalNumTravelers = document.getElementById('modalNumTravelers');
const modalFullName = document.getElementById('modalFullName');
const modalEmail = document.getElementById('modalEmail');
const modalPhone = document.getElementById('modalPhone');
const confirmBookingBtn = document.getElementById('confirmBookingBtn');

// Campos do perfil
const profileFullName = document.getElementById('profileFullName');
const profileEmail = document.getElementById('profileEmail');
const profilePhone = document.getElementById('profilePhone');

let currentUserId = null;
let currentUsername = null;
let currentDestinationId = null;

// --- Funções de Autenticação ---

async function registerUser() {
    const username = document.getElementById('regUsername').value;
    const password = document.getElementById('regPassword').value;

    if (!username || !password) {
        showMessage('Por favor, preencha todos os campos.', 'error');
        return;
    }

    try {
        const response = await fetch(`${API_BASE_URL}registo.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username, password })
        });
        const data = await response.json();
        showMessage(data.message, data.status);
    } catch (error) {
        console.error('Erro durante o registo:', error);
        showMessage('Ocorreu um erro ao tentar registar. Verifique a console.', 'error');
    }
}

async function loginUser() {
    const username = document.getElementById('loginUsername').value;
    const password = document.getElementById('loginPassword').value;

    if (!username || !password) {
        showMessage('Por favor, preencha todos os campos.', 'error');
        return;
    }

    try {
        const response = await fetch(`${API_BASE_URL}login.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username, password })
        });
        const data = await response.json();

        if (data.status === 'success') {
            showMessage(data.message, data.status);
            currentUserId = data.user_id;
            currentUsername = data.username;
            updateUIForLogin();
            await loadProfileData();
            loadBookings();
        } else {
            showMessage(data.message, data.status);
        }
    } catch (error) {
        console.error('Erro durante o login:', error);
        showMessage('Ocorreu um erro ao tentar fazer login. Verifique a console.', 'error');
    }
}

async function logoutUser() {
    try {
        const response = await fetch(`${API_BASE_URL}logout.php`);
        const data = await response.json();
        showMessage(data.message, data.status);
        currentUserId = null;
        currentUsername = null;
        updateUIForLogout();
    } catch (error) {
        console.error('Erro durante o logout:', error);
        showMessage('Ocorreu um erro ao tentar fazer logout. Verifique a console.', 'error');
    }
}

function updateUIForLogin() {
    authFormsDiv.style.display = 'none';
    loginRegisterBtn.style.display = 'none';
    loggedInUserSpan.textContent = `Bem-vindo, ${currentUsername}!`;
    customerAreaBtn.style.display = 'inline-block';
    logoutBtn.style.display = 'inline-block';
    customerAreaTitle.textContent = `Área do Cliente de ${currentUsername}`;
}

function updateUIForLogout() {
    authFormsDiv.style.display = 'block';
    loginRegisterBtn.style.display = 'inline-block';
    loggedInUserSpan.textContent = '';
    customerAreaBtn.style.display = 'none';
    logoutBtn.style.display = 'none';
    customerAreaDiv.style.display = 'none';
    futureBookingsListDiv.innerHTML = '<p>Nenhuma viagem futura encontrada.</p>';
    pastBookingsListDiv.innerHTML = '<p>Nenhuma viagem anterior encontrada.</p>';
    profileFullName.value = '';
    profileEmail.value = '';
    profilePhone.value = '';
}

function toggleAuthForms() {
    authFormsDiv.style.display = authFormsDiv.style.display === 'none' ? 'block' : 'none';
}

function showCustomerArea() {
    customerAreaDiv.style.display = 'block';
    customerAreaDiv.scrollIntoView({ behavior: 'smooth' });
    loadProfileData();
    loadBookings();
}

// --- Funções de Perfil ---

// Dentro de script.js
async function loadProfileData() {
    try {
        const response = await fetch(API_BASE_URL + 'obterprefil.php', {
            method: 'GET', 
            headers: {
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();

        if (data.status === 'success') {
            // ... preencher os campos do perfil ...
            profileFullName.value = data.data.full_name || '';
            profileEmail.value = data.data.email || '';
            profilePhone.value = data.data.phone || '';
            customerAreaTitle.textContent = `Bem-vindo, ${data.data.username}!`; // Atualiza título
        } else {
            showMessage(data.message, 'error');
            // Se o erro for "Acesso negado", esconda a área do cliente
            if (data.message.includes('Acesso negado')) {
                updateUIForLogout();
            }
        }
    } catch (error) {
        console.error('Erro ao carregar dados do perfil:', error);
        showMessage('Erro ao carregar dados do perfil.', 'error');
    }
}

async function updateProfile() {
    if (!currentUserId) {
        showMessage('Por favor, faça login para atualizar o perfil.', 'error');
        return;
    }

    const fullName = profileFullName.value;
    const email = profileEmail.value;
    const phone = profilePhone.value;

    try {
        const response = await fetch(`${API_BASE_URL}obterprefil.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ full_name: fullName, email: email, phone: phone })
        });
        const data = await response.json();
        showMessage(data.message, data.status);
        if (data.status === 'success') {
            modalFullName.value = fullName;
            modalEmail.value = email;
            modalPhone.value = phone;
        }
    } catch (error) {
        console.error('Erro ao atualizar perfil:', error);
        showMessage('Ocorreu um erro ao atualizar o perfil. Verifique a console.', 'error');
    }
}

// --- Funções de Destinos ---

async function loadDestinations() {
    destinationsContainer.innerHTML = '<p>A carregar destinos...</p>';

    try {
        const response = await fetch(`${API_BASE_URL}obterdestinos.php`);
        const data = await response.json();

        if (data.status === 'success') {
            destinationsContainer.innerHTML = '';

            if (data.data && data.data.length > 0) {
                data.data.forEach(destination => {
                    const card = document.createElement('div');
                    card.classList.add('destination-card');
                    card.innerHTML = `
                        <img src="${destination.image_url}" alt="${destination.name}">
                        <h3>${destination.name}</h3>
                        <p>${destination.description}</p>
                        <div class="card-actions">
                            <button onclick="openBookingModal(${destination.id}, '${destination.name}')">Reservar</button>
                        </div>
                    `;
                    destinationsContainer.appendChild(card);
                });
            } else {
                destinationsContainer.innerHTML = '<p>Nenhum destino encontrado ainda.</p>';
            }

        } else {
            showMessage(`Erro: ${data.message}`, 'error');
            destinationsContainer.innerHTML = `<p>Falha ao carregar destinos: ${data.message}</p>`;
        }
    } catch (error) {
        console.error('Erro ao buscar destinos:', error);
        showMessage('Ocorreu um erro ao carregar destinos. Verifique a console.', 'error');
        destinationsContainer.innerHTML = '<p>Ocorreu um erro ao carregar destinos.</p>';
    }
}

// --- Funções do Modal de Reserva ---

function openBookingModal(destinationId, destinationName) {
    if (!currentUserId) {
        showMessage('Por favor, faça login para reservar.', 'error');
        authFormsDiv.scrollIntoView({ behavior: 'smooth' });
        return;
    }

    currentDestinationId = destinationId;
    modalDestinationName.textContent = `Reservar Viagem para ${destinationName}`;
    modalTravelDate.value = new Date().toISOString().slice(0, 10);
    modalNumTravelers.value = 1;

    bookingModal.style.display = 'block';
}

function closeBookingModal() {
    bookingModal.style.display = 'none';
    currentDestinationId = null;
}

confirmBookingBtn.onclick = async () => {
    const travelDate = modalTravelDate.value;
    const numTravelers = modalNumTravelers.value;

    if (!travelDate || !numTravelers) {
        showMessage('Por favor, preencha a data e o número de viajantes para a reserva.', 'error');
        return;
    }

    try {
        const response = await fetch(`${API_BASE_URL}reserva.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                destination_id: currentDestinationId,
                travel_date: travelDate,
                num_travelers: parseInt(numTravelers)
            })
        });
        const data = await response.json();
        showMessage(data.message, data.status);

        if (data.status === 'success') {
            closeBookingModal();
            loadBookings();
        }
    } catch (error) {
        console.error('Erro ao criar reserva:', error);
        showMessage('Ocorreu um erro ao criar a reserva. Verifique a console.', 'error');
    }
};

// --- Funções da Área do Cliente (Reservas) ---

async function loadBookings() {
    futureBookingsListDiv.innerHTML = '<p>A carregar as suas próximas viagens...</p>';
    pastBookingsListDiv.innerHTML = '<p>A carregar as suas viagens anteriores...</p>';

    try {
        const response = await fetch(`${API_BASE_URL}obterviagens.php`);
        const data = await response.json();

        if (data.status === 'success') {
            renderBookings(data.future_bookings, futureBookingsListDiv, 'Nenhuma viagem futura encontrada.');
            renderBookings(data.past_bookings, pastBookingsListDiv, 'Nenhuma viagem anterior encontrada.');
        } else {
            showMessage(`Erro ao carregar reservas: ${data.message}`, 'error');
            futureBookingsListDiv.innerHTML = `<p>${data.message}</p>`;
            pastBookingsListDiv.innerHTML = `<p>${data.message}</p>`;
        }
    } catch (error) {
        console.error('Erro ao buscar reservas:', error);
        showMessage('Ocorreu um erro ao carregar as suas reservas. Verifique a console.', 'error');
        futureBookingsListDiv.innerHTML = '<p>Ocorreu um erro ao carregar as suas reservas.</p>';
        pastBookingsListDiv.innerHTML = '<p>Ocorreu um erro ao carregar as suas reservas.</p>';
    }
}

function renderBookings(bookings, containerDiv, emptyMessage) {
    containerDiv.innerHTML = '';
    if (bookings && bookings.length > 0) {
        bookings.forEach(booking => {
            const card = document.createElement('div');
            card.classList.add('booking-card');
            card.innerHTML = `
                <h4>${booking.destination_name}</h4>
                <p><strong>Data da Viagem:</strong> ${new Date(booking.travel_date).toLocaleDateString('pt-PT')}</p>
                <p><strong>Número de Viajantes:</strong> ${booking.num_travelers}</p>
                <p><strong>Data da Reserva:</strong> ${new Date(booking.booking_date).toLocaleDateString('pt-PT')}</p>
                <p><strong>Estado:</strong> <span class="status ${booking.status}">${booking.status}</span></p>
                <p><em>${booking.destination_description}</em></p>
            `;
            containerDiv.appendChild(card);
        });
    } else {
        containerDiv.innerHTML = `<p>${emptyMessage}</p>`;
    }
}

function showMessage(msg, type) {
    messageDiv.textContent = msg;
    messageDiv.className = '';
    messageDiv.classList.add(type);
    messageDiv.style.display = 'block';
    setTimeout(() => {
        messageDiv.style.display = 'none';
    }, 5000);
}

// --- Inicialização ---
window.onload = async () => {
    await loadDestinations();
    modalTravelDate.value = new Date().toISOString().slice(0, 10);

    window.onclick = function(event) {
        if (event.target == bookingModal) {
            closeBookingModal();
        }
    };
};