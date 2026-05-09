function checkEmailAvailability(){
    let email = document.getElementById('email').value;
    let msg = document.getElementById('email_msg');

    if(email == ""){
        msg.innerHTML = "";
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
