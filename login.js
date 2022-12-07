window.onload = function() {
    var httpRequest;
    var email = document.getElementById("email");
    var password = document.getElementById("password");
    var loginBtn = document.getElementById("loginBtn");

    loginBtn.onclick = login;
    
    function login() {
        event.preventDefault();

        if(email.value === "" || password.value === "") {
            alert("Please fill in all fields before submission!");
            return;
        }

        httpRequest = new XMLHttpRequest();
        var url = "js/login_user.php";
        httpRequest.onreadystatechange = pLogin;
        httpRequest.open('POST', url);
        httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        httpRequest.send('email=' + encodeURIComponent(email.value) + "&password=" + encodeURIComponent(password.value));
    }

    function pLogin() {
        if (httpRequest.readyState === XMLHttpRequest.DONE) {
            if (httpRequest.status === 200) {
                var response = httpRequest.responseText;
                if(response === "true") {
                    alert("Login successful");
                } else {
                    alert("Invalid Credentials!");
                }
            } else {
                alert('There was a problem with the request.');
            }
        }
    }
}
