window.onpopstate = function(event){
  const view = event.state?.view || 'home';
  loadView(view,false);
};

document.addEventListener('DOMContentLoaded', () => {
    registerFormValidation();
    loginFormValidation();
    messageHidden("flash-message");
});

//CONTENT LOADING

function loadView(viewName,push=true)
{
  fetch('/'+ viewName, {
    method: 'GET',
  })
  .then(res => {
    if(!res.ok) throw new Error(`HTTP error! Status: ${res.status}`);
    return res.text()})
  .then(html => {
    document.getElementById('app').innerHTML = html;
    if(push)
    {
      history.pushState({view: viewName},'','/'+viewName);
    }
    registerFormValidation();
    loginFormValidation();   
  }).catch(error => {
    console.error('Chyba při načítání:',error);
  });
}

//REGISTER FORM VALIDATION

function registerFormValidation(){
  const form = document.getElementById('register-form');
  if(!form) return;
  form.addEventListener("submit", async function(e) {
    e.preventDefault();
    const emailInput = form.querySelector('#email');
    const password = form.querySelector('#password').value;
    const errors = await registerValidation(emailInput,password);
    if(errors.length > 0){
      form.querySelector('#password').value = '';
      displayErrors(errors);
      return;
    }else{
      form.submit();
    }
  });
}

async function registerValidation(emailInput,password)
{
  const email = emailInput.value;
  const errors = [];
  if (!email) {
      errors.push("Email is required.");
  }else{
  if(email.length > 255) errors.push("Email is too long.");
  if(!emailInput.checkValidity() || !/^[^\s@]+@[^\s@]+\.[a-zA-Z0-9]{2,}$/.test(email)) errors.push("Email format is invalid.");
  if((await emailExistsRegisterFetch(email))) errors.push("Email has already used.");
  if(!(await domainExistsFetch(email))) errors.push("Domain does not exist.");
  }

  if(!password){
    errors.push("Password is required.");
  }else{
    if (password.length < 8) errors.push("Password must be at least 8 characters.");
    if (password.length > 72) errors.push("Password is too long.");
    if (!/[A-Z]/.test(password)) errors.push("Password must contain at least one uppercase letter.");
    if (!/[a-z]/.test(password)) errors.push("Password must contain at least one lowercase letter.");
    if (!/[0-9]/.test(password)) errors.push("Password must contain at least one number.");
  }  
  return errors;
}

async function emailExistsRegisterFetch(email)
{
  const res = await fetch('/register?action=emailExistsEndpoint',{
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({email: email})
  });
  const data = await res.json();
  return data.exists ?? false;
}

async function domainExistsFetch(email)
{
  const res = await fetch('/register?action=emailDomainValidationEndpoint',{
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({email: email})
  });
  const data = await res.json();
  console.log(data.domainExists);
  return data.domainExists ?? false;
}

//LOGIN FORM VALIDATION

function loginFormValidation(){
  const form = document.getElementById('login-form');
  if(!form) return;
  form.addEventListener("submit", async function(e) {
    e.preventDefault();
    const email = form.querySelector('#email').value;
    const password = form.querySelector('#password').value;
    if(await userExistsLoginFetch(email) && await passwordVerifyLoginFetch(password,email))
    {
      form.submit();
    }else
    {
      form.querySelector('#password').value = '';
      displayErrors(["User or password was wrong."]);
      return;
    }
  });
}

async function passwordVerifyLoginFetch(password,email)
{
  const res = await fetch('/login?action=passwordVerifyEndpoint',{
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({email: email,password: password})
  });
  const data = await res.json();
  return data.isVerify ?? false;
}

async function userExistsLoginFetch(email)
{
  const res = await fetch('/login?action=userExistsEndpoint',{
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({email: email})
  });
  const data = await res.json();
  return data.exists ?? false;
}

//ERRORS AND MESSAGES VALIDATITION 

function displayErrors(errors)
{
  const loginCon = document.getElementById('error-container');
  loginCon.innerHTML = '';
  loginCon.style.opacity = 1;
  if (errors.length > 0){
    const ul = document.createElement('ul');
    ul.id = 'error-list';

    errors.forEach(error => {
      const li = document.createElement('li');
      li.textContent = error;
      ul.appendChild(li);
    });
    loginCon.appendChild(ul);
    messageHidden("error-list");
  }
}

function messageHidden(id)
{
  const flash = document.getElementById(id);
  if(flash){
  setTimeout(()=> {
    flash.style.transition = "opacity 1s";
    flash.style.opacity = 0;
    setTimeout(() => flash.remove(), 1000);
  },3000);
  }
}

