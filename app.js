// Simple client-side demo app (no backend) — uses localStorage for users and session
(() => {
  // Sample barangay officials data
  const officials = [
    { name: "Punong Barangay: Sergio Tesorio", position: "Punong Barangay" }, 
    { name: "Kagawad: Junior Hora", position: "Kagawad - Peace & Order" },
    { name: "Kagawad: Allan Bolivar", position: "Kagawad - Health" },
    { name: "Kagawad: Eleazar Dano", position: "Kagawad - Infrastructure" },
    { name: "Secretary: Liza Ra", position: "Secretary" },
    { name: "Treasurer: Jocelyn Langbid", position: "Treasurer" },
  ];

  // DOM
  const authSection = document.getElementById('auth');
  const dashboard = document.getElementById('dashboard');

  const showLoginBtn = document.getElementById('show-login');
  const showRegisterBtn = document.getElementById('show-register');
  const loginForm = document.getElementById('login-form');
  const registerForm = document.getElementById('register-form');
  const goRegister = document.getElementById('go-register');
  const goLogin = document.getElementById('go-login');

  const loginBtn = document.getElementById('login-btn');
  const registerBtn = document.getElementById('register-btn');

  const logoutBtn = document.getElementById('logout-btn');

  const hamburger = document.getElementById('hamburger');
  const burgerMenu = document.getElementById('burger-menu');
  const panelTitle = document.getElementById('panel-title');
  const panelBody = document.getElementById('panel-body');

  const officialsList = document.getElementById('officials-list');

  // helpers
  const usersKey = 'barangay_demo_users';
  const sessionKey = 'barangay_demo_session';

  function loadUsers(){
    try {
      return JSON.parse(localStorage.getItem(usersKey) || '[]');
    } catch(e){
      return [];
    }
  }
  function saveUsers(users){
    localStorage.setItem(usersKey, JSON.stringify(users));
  }
  function setSession(user){
    localStorage.setItem(sessionKey, JSON.stringify(user));
  }
  function clearSession(){
    localStorage.removeItem(sessionKey);
  }
  function getSession(){
    try { return JSON.parse(localStorage.getItem(sessionKey)); } catch { return null; }
  }

  // UI toggles
  function showLogin(){
    showLoginBtn.classList.add('active');
    showRegisterBtn.classList.remove('active');
    loginForm.classList.remove('hidden');
    registerForm.classList.add('hidden');
  }
  function showRegister(){
    showRegisterBtn.classList.add('active');
    showLoginBtn.classList.remove('active');
    registerForm.classList.remove('hidden');
    loginForm.classList.add('hidden');
  }

  showLoginBtn.addEventListener('click', showLogin);
  showRegisterBtn.addEventListener('click', showRegister);
  goRegister.addEventListener('click', (e)=>{ e.preventDefault(); showRegister(); });
  goLogin.addEventListener('click', (e)=>{ e.preventDefault(); showLogin(); });

  // register
  registerBtn.addEventListener('click', () => {
    const name = document.getElementById('reg-name').value.trim();
    const email = document.getElementById('reg-email').value.trim().toLowerCase();
    const password = document.getElementById('reg-password').value;

    if(!name || !email || !password){
      alert('Please fill all fields.');
      return;
    }
    const users = loadUsers();
    if(users.find(u => u.email === email)){
      alert('Email already registered. Please login.');
      return;
    }
    // Simple user object; DO NOT use plain password in production
    users.push({ name, email, password });
    saveUsers(users);
    alert('Registered successfully. You can login now.');
    // auto-switch to login
    showLogin();
  });

  // login
  loginBtn.addEventListener('click', () => {
    const email = document.getElementById('login-email').value.trim().toLowerCase();
    const password = document.getElementById('login-password').value;
    if(!email || !password){
      alert('Please fill email and password.');
      return;
    }
    const users = loadUsers();
    const user = users.find(u => u.email === email && u.password === password);
    if(!user){
      alert('Invalid credentials.');
      return;
    }
    setSession({ email: user.email, name: user.name });
    renderApp();
  });

  // Logout
  logoutBtn.addEventListener('click', () => {
    clearSession();
    renderApp();
  });

  // Render officials
  function renderOfficials(){
    officialsList.innerHTML = '';
    officials.forEach(o => {
      const el = document.createElement('div');
      el.className = 'official';
      el.innerHTML = `
        <div class="avatar">${initials(o.name)}</div>
        <div class="info">
          <h3>${o.name}</h3>
          <p>${o.position}</p>
        </div>
      `;
      officialsList.appendChild(el);
    });
  }

  function initials(name){
    return name.split(' ').slice(0,2).map(s => s[0]).join('').toUpperCase();
  }

  // Burger menu toggles and view switching
  hamburger.addEventListener('click', () => {
    burgerMenu.classList.toggle('hidden');
  });

  burgerMenu.addEventListener('click', (e) => {
    const li = e.target.closest('li');
    if(!li) return;
    const view = li.dataset.view;
    showView(view);
    burgerMenu.classList.add('hidden');
  });

  function showView(view){
    switch(view){
      case 'updates':
        panelTitle.textContent = 'Accomplishments';
        panelBody.innerHTML = `<p>To be Announced</p>`;
        break;
      case 'ongoing':
        panelTitle.textContent = 'Ongoing Projects';
        panelBody.innerHTML = `
          <p>To be Announced</p>
          <ul>
            <li>Road improvement - Phase 2</li>
            <li>Barangay health center rehabilitation</li>
            <li>Drainage clearing and cleanup</li>
          </ul>
        `;
        break;
      case 'permit':
        panelTitle.textContent = 'Permit Cost';
        panelBody.innerHTML = `
          <p>Permit Rates (placeholder)</p>
          <table style="width:100%;border-collapse:collapse">
            <tr><th style="text-align:left">Name</th><th style="text-align:left">Permit</th><th>Amount</th></tr>
            <tr><td>Grocery Store</td><td>Business Permit</td><td>₱1,200</td></tr>
            <tr><td>Ticketing Business</td><td>Business Permit</td><td>₱500</td></tr>
          </table>
        `;
        break;
      case 'clearance':
        panelTitle.textContent = 'Barangay Clearance';
        panelBody.innerHTML = `<p>Generate and Manage Barangay Clearance.</p>`;
        break;
      case 'certificate':
        panelTitle.textContent = 'Barangay Certificate';
        panelBody.innerHTML = `<p>Generate and manage barangay certificates.</p>`;
        break;
        case 'Cedula':
        panelTitle.textContent = 'Cedula';
        panelBody.innerHTML = `<p>Generate and manage barangay certificates.</p>`;
        break;
        case 'certificate':
        panelTitle.textContent = 'Certificate of Indigency';
        panelBody.innerHTML = `<p>Generate and manage barangay certificates.</p>`;
        break;
      default:
        panelTitle.textContent = 'Welcome';
        panelBody.innerHTML = `<p>Select a menu item from the top-right menu.</p>`;
    }
  }

  // Main render
  function renderApp(){
    const session = getSession();
    if(session && session.email){
      authSection.classList.add('hidden');
      dashboard.classList.remove('hidden');
      renderOfficials();
      panelTitle.textContent = `Welcome, ${session.name}`;
      panelBody.innerHTML = `<p>You are logged in as <strong>${session.email}</strong>. Use the menu to explore.</p>`;
    } else {
      authSection.classList.remove('hidden');
      dashboard.classList.add('hidden');
    }
  }

  // init
  renderApp();
})();
