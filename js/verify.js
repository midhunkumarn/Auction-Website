const otp=document.querySelectorAll('.otp_field');

//focus on first input
otp[0].focus();

otp.forEach((field, index) =>{

    field.addEventListener('keydown', (e) =>{
        
        if(e.key >=0 && e.key <=9){
            otp[index].value="";
            setTimeout(()=>{
                otp[index+1].focus(this);
            },4);
        }
        else if(e.key === 'Backspace'){
            setTimeout(() => {
                otp[index-1].focus(this);
            }, 4);
        }

    });
});

const otpform=document.querySelector('.otp-form');
otpverifybtn=otpform.querySelector('.verifybtn');
errortxt=otpform.querySelector('.error-text ');

otpform.onsubmit = (e) => {
    e.preventDefault();
}

otpverifybtn.onclick = () =>{
    //start ajax

    let xml= new XMLHttpRequest();//creating xml object 
    xml.open("POST","db_operations/otp.php",true);
    xml.onload = () => {
        if(xml.readyState === XMLHttpRequest.DONE){
            if(xml.status == 200){
                let data=xml.response;

                if(data == "session not started"){
                    location.replace="index.php";
                }
                else if(data == "Verified"){
                    location.href="user/index.php";
                }
                else if(data == "success"){
                    location.href="index.php";
                }
                else{
                    errortxt.textContent=data;
                    errortxt.style.display = "block";
                }
            }
        }
    }

    //send data through ajax to php
    let formData = new FormData(otpform);//creating new object from otpform data
    xml.send(formData);
}