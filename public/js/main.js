function isValidEmail(email){
    email = email.trim();

    if(email == ""){
        return false;
    }

    if(email.indexOf(" ") != -1){
        return false;
    }

    let atPos = email.indexOf("@");
    let lastAtPos = email.lastIndexOf("@");

    if(atPos <= 0 || atPos != lastAtPos){
        return false;
    }

    let dotPos = email.lastIndexOf(".");

    if(dotPos <= atPos + 1){
        return false;
    }

    if(dotPos >= email.length - 1){
        return false;
    }

    return true;
}

function checkEmailAvailability(){
    let emailInput = document.getElementById('email');
    let msg = document.getElementById('email_msg');

    if(!emailInput || !msg){
        return;
    }

    let email = emailInput.value.trim();

    if(email == ""){
        msg.innerHTML = "";
        return;
    }

    if(!isValidEmail(email)){
        msg.innerHTML = "invalid email format";
        msg.style.color = "red";
        return;
    }

    let xhttp = new XMLHttpRequest();
    xhttp.open('get', '../api/checkEmail.php?email=' + encodeURIComponent(email), true);
    xhttp.onreadystatechange = function (){
        if(this.readyState == 4 && this.status == 200){
            let res = JSON.parse(this.responseText);
            msg.innerHTML = res.message;
            msg.style.color = (res.available === true) ? "green" : "red";
        }
    }
    xhttp.send();
}

function validateSignup(){
    let name = document.getElementById('name').value.trim();
    let email = document.getElementById('email').value.trim();
    let password = document.getElementById('password').value;
    let confirm = document.getElementById('confirm_password').value;
    let msg = document.getElementById('form_msg');

    if(name == "" || email == "" || password == "" || confirm == ""){
        msg.innerHTML = "please fill all fields";
        return false;
    }

    if(!isValidEmail(email)){
        msg.innerHTML = "invalid email format";
        return false;
    }

    if(password.length < 8){
        msg.innerHTML = "password must be at least 8 characters";
        return false;
    }

    if(password != confirm){
        msg.innerHTML = "password mismatch";
        return false;
    }

    msg.innerHTML = "";
    return true;
}

function validateProfileUpdate(){
    let name = document.getElementById('profile_name').value.trim();
    let email = document.getElementById('profile_email').value.trim();
    let msg = document.getElementById('profile_msg');

    if(name == "" || email == ""){
        msg.innerHTML = "please type name and email";
        return false;
    }

    if(!isValidEmail(email)){
        msg.innerHTML = "invalid email format";
        return false;
    }

    msg.innerHTML = "";
    return true;
}

function validatePasswordChange(){
    let current_password = document.getElementById('current_password').value;
    let new_password = document.getElementById('new_password').value;
    let confirm_password = document.getElementById('confirm_password_change').value;
    let msg = document.getElementById('password_msg');

    if(current_password == "" || new_password == "" || confirm_password == ""){
        msg.innerHTML = "please type all password fields";
        return false;
    }

    if(new_password.length < 8){
        msg.innerHTML = "new password must be at least 8 characters";
        return false;
    }

    if(new_password != confirm_password){
        msg.innerHTML = "new and confirm password mismatch";
        return false;
    }

    msg.innerHTML = "";
    return true;
}
