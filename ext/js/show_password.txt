// let signuppass=document.forms.password.value;
// let confirmsignuppass=document.forms.confirmpass.value;

pwshowhide=document.querySelectorAll('.showhidepw');
pwshowhide1=document.querySelectorAll('.showhidepw1');
pwshowhide2=document.querySelectorAll('.showhidepw2');
pwfields=document.querySelectorAll('.password');
pwfields1=document.querySelectorAll('.password1');
pwconfirmfield=document.querySelectorAll('.confirmpass');
// eyeIcon=document.querySelectorAll('.fa-eye-slash');

pwshowhide.forEach(eyeIcon => {
    eyeIcon.addEventListener("click",()=>{
        pwfields.forEach(pwField => {
            if(pwField.type === "password"){
                pwField.type="text";
                // pwField.type="text";

                pwshowhide.forEach(icon=>{
                    icon.classList.replace("fa-eye-slash","fa-eye");
                })
            }else{
                pwField.type="password";

                pwshowhide.forEach(icon => {
                    icon.classList.replace("fa-eye","fa-eye-slash");
                })
            }
        })
    })
});

pwshowhide2.forEach(eyeIcons => {
    eyeIcons.addEventListener("click",()=>{
        pwconfirmfield.forEach(pwField2 => {
            if(pwField2.type === "password"){
                pwField2.type="text";

                pwshowhide2.forEach(eyeicon=>{
                    eyeicon.classList.replace("fa-eye-slash","fa-eye");
                })
            }else{
                pwField2.type="password";

                pwshowhide2.forEach(eyeicon => {
                    eyeicon.classList.replace("fa-eye","fa-eye-slash");
                })
            }
        })
    })
});

pwshowhide1.forEach(eyeIcons => {
    eyeIcons.addEventListener("click",()=>{
        pwfields1.forEach(pwField3 => {
            if(pwField3.type === "password"){
                pwField3.type="text";

                pwshowhide1.forEach(eyeicon=>{
                    eyeicon.classList.replace("fa-eye-slash","fa-eye");
                })
            }else{
                pwField3.type="password";

                pwshowhide1.forEach(eyeicon => {
                    eyeicon.classList.replace("fa-eye","fa-eye-slash");
                })
            }
        })
    })
});