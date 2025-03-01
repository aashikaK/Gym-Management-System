document.querySelectorAll(".icon-a").forEach(eyeIcon => {
    eyeIcon.addEventListener("click", function () {
        let pwField = this.previousElementSibling; // Get the input field

        if (pwField && pwField.type === "password") {
            pwField.type = "text";
            this.classList.replace("bx-hide", "bx-show");
        } else {
            pwField.type = "password";
            this.classList.replace("bx-show", "bx-hide");
        }
    });
});
