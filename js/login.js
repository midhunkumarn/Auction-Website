const loginform=document.querySelector('.sign-in-form');
signinbtn=loginform.querySelector('.signin');
errortxt=loginform.querySelector('.error-text ');

loginform.onsubmit = (e) => {
    e.preventDefault();
}

signinbtn.onclick = () =>{
    //start ajax

    let xhr= new XMLHttpRequest();//creating xhr object 
    xhr.open("POST","db_operations/login.php",true);
    xhr.onload = () => {
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status == 200){

                let data=xhr.response;

                if(data == "success"){
                    location.href="user/index.php";
                }
                else if(data == "admin"){
                    location.href="admin/index.php";
                }
                // else if(data == ""){
                //     location.href="verify.php";
                // }
                else{
                    errortxt.textContent=data;
                    errortxt.style.display = "block";
                }

            }
        }
    }

    //send data through ajax to php
    let formData = new FormData(loginform);//creating new object from loginform data
    xhr.send(formData);
}