document.querySelector('#showform').addEventListener("click",function (){
    document.querySelector(".popup-form").classList.add("active");
    document.querySelector(".popup-form").style.display="block";
})

document.querySelector('.popup-form .closebtn').addEventListener("click",function (){
    document.querySelector(".popup-form").classList.remove("active");
})