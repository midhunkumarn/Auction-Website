const form=document.querySelector('.sign-up-form');
signupbtn=form.querySelector('.btn');
errortxt1=form.querySelector('.error-text1');

form.onsubmit = (e) => {
    e.preventDefault();
}

signupbtn.onclick = () =>{
    //start ajax

    let xhr= new XMLHttpRequest();//creating xhr object 
    xhr.open("POST","db_operations/signup.php",true);
    xhr.onload = () => {
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status == 200){
                let data=xhr.response;

                if(data == "success"){
                    location.href="verify.php";
                }else{
                    errortxt1.textContent=data;
                    errortxt1.style.display = "block";
                }
            }
        }
    }

    //send data through ajax to php
    let formData = new FormData(form);//creating new object from form data
    xhr.send(formData);
}