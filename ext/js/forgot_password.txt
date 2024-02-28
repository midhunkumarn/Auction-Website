const form=document.querySelector('.forgot-password-form');
forgotbtn=form.querySelector('.forgot-pass');
errortxt=form.querySelector('.error-text ');

form.onsubmit = (e) => {
    e.preventDefault();
}

forgotbtn.onclick = () =>{
    //start ajax

    let xhr= new XMLHttpRequest();//creating xhr object 
    xhr.open("POST","db_operations/forgot_password.php",true);
    xhr.onload = () => {
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status == 200){
                let data=xhr.response;

                if(data == "change password"){
                    location.href="verify.php";
                }else{
                    errortxt.textContent=data;
                    errortxt.style.display = "block";
                }
            }
        }
    }

    //send data through ajax to php
    let formData = new FormData(form);//creating new object from form data
    xhr.send(formData);
}