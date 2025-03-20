<script>
document.querySelector(".modern-form").addEventListener("submit", function(event) {
    let password = document.querySelector("input[name='userPassword']").value;
    let confirmPassword = document.querySelector("input[name='confirmPassword']").value;
    let phone = document.querySelector("input[name='userPhone']").value;
    let email = document.querySelector("input[name='userEmail']").value;

    let errorMessage = "";

    // Validate Password Match
    if (password !== confirmPassword) {
        errorMessage += "⚠ Passwords do not match.<br>";
    }

    // Validate Phone Number (Only digits, at least 10)

    
    
    let phonePattern = /^[0-9]{10,}$/;
    if (!phonePattern.test(phone)) {
        errorMessage += "⚠ Phone number must be at least 10 digits long.<br>";
    }

    // Validate Email Format
    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        errorMessage += "⚠ Enter a valid email address.<br>";
    }

    // Show Error Message Below Form (Instead of Alert)
    let errorContainer = document.getElementById("error-message");
    errorContainer.innerHTML = errorMessage;
    errorContainer.style.display = errorMessage ? "block" : "none";

    // Prevent Form Submission If There Are Errors
    if (errorMessage) {
        event.preventDefault();
    }
});
</script>
