
const forms=document.getElementsByClassName(".forms");
const pwShowHide = document.querySelectorAll(".icon-a");

pwShowHide.forEach(eyeIcon => {
    eyeIcon.addEventListener("click", () => {
        let pwField = eyeIcon.parentElement.querySelector("input[type='password'], input[type='text']");
        
        if (pwField.type === "password") {
            pwField.type = "text";
            eyeIcon.classList.replace("bx-hide", "bx-show");
        } else {
            pwField.type = "password";
            eyeIcon.classList.replace("bx-show", "bx-hide");
        }
    });
});