const form=document.querySelector('.bid-form');
btn=form.querySelector('.first-btn');
errortxt=form.querySelector('.error-text');

form.onsubmit = (e) => {
    e.preventDefault();
}

btn.onclick = () =>{
    //start ajax

    let xhr= new XMLHttpRequest();//creating xhr object 

    xhr.open("POST","db_operations/increase_bid.php",true);
    xhr.onload = () => {
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status == 200){
                let data=xhr.response;

                if(data == "success"){
                    location.reload("bids.php");
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