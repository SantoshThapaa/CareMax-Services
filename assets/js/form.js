document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("myForm");

    form.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent actual form submission
        // You can handle the form data here if needed

        // Clear the form
        form.reset();
    });
});