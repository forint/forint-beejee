(function() {
    'use strict';
    window.addEventListener('load', function() {

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');

        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

/** Add task form validation */
function takeData() {

    let inputs = document.querySelectorAll(".needs-validation input, .needs-validation textarea");

    /* Break, if not pass validation */
    for (let i = 0; i < inputs.length; i++) {
        if (!inputs[i].validity.valid){
            return false;
        }
    }

    $('#form-task-add').removeAttr('onsubmit');
    $('#form-task-add').submit();

}