function checkLogin(){
    var username = document.getElementById("email").value;
    var password = document.getElementById("password").value;;
    var remember = document.getElementById("remember").checked;
    
    if (username=="admin@gmail.com" && password=="admin"){
        alert("selamat datang admin");
    }
    else{
        alert("selamat datang user");
    }
    
}

