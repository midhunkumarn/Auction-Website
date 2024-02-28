function previewBeforeUpload(id){
    document.querySelector("#"+id).addEventListener("change",function(e){
      if(e.target.files.length == 0){
        return;
      }
      let file = e.target.files[0];
      let url = URL.createObjectURL(file);
      document.querySelector("#"+id+"-preview div").innerText = file.name;
      document.querySelector("#"+id+"-preview img").src = url;
    });
  }
  
  previewBeforeUpload("profile_pic");
  previewBeforeUpload("qrcode");
//   previewBeforeUpload("file-3");

//image upload validation
const form=document.querySelector('.image-upload-form');
btn=form.querySelector('.btn');
errortxt=form.querySelector('.error-text');

form.onsubmit = (e) => {
    e.preventDefault();
}

btn.onclick = () =>{
    //start ajax

    let xhr= new XMLHttpRequest();//creating xhr object 

    xhr.open("POST","db_operations/image_uploads.php",true);
    xhr.onload = () => {
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status == 200){
                let data=xhr.response;

                if(data == "image already uploaded"){
                    location.replace("index.php");
                }
                else if(data == "image uploaded"){
                    location.href="index.php";
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