document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('signupForm');
    const inputs = form.querySelectorAll('.form-control');
    
    // Real-time validation
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            clearError(this);
        });
    });
    
    // Form submission validation
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        inputs.forEach(input => {
            if (!validateField(input)) {
                isValid = false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
        }
    });
    
    function validateField(field) {
        const value = field.value.trim();
        const fieldName = field.name;
        let isValid = true;
        
        // Clear previous errors
        clearError(field);
        
        switch(fieldName) {
            case 'first_name':
            case 'last_name':
                if (value.length < 2) {
                    showError(field, `${fieldName.replace('_', ' ')} must be at least 2 characters`);
                    isValid = false;
                }
                break;
                
            case 'username':
                if (value.length < 3) {
                    showError(field, 'Username must be at least 3 characters');
                    isValid = false;
                } else if (!/^[a-zA-Z0-9_]+$/.test(value)) {
                    showError(field, 'Username can only contain letters, numbers, and underscores');
                    isValid = false;
                }
                break;
                
            case 'email':
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                    showError(field, 'Please enter a valid email address');
                    isValid = false;
                }
                break;
                
            case 'password':
                if (value.length < 8) {
                    showError(field, 'Password must be at least 8 characters');
                    isValid = false;
                } else if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(value)) {
                    showError(field, 'Password must contain at least one uppercase letter, one lowercase letter, and one number');
                    isValid = false;
                }
                break;
                
            case 'confirm_password':
                const password = document.getElementById('password').value;
                if (value !== password) {
                    showError(field, 'Passwords do not match');
                    isValid = false;
                }
                break;
        }
        
        return isValid;
    }
    
    function showError(field, message) {
        field.classList.add('error');
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }
    
    function clearError(field) {
        field.classList.remove('error');
        const errorMessage = field.parentNode.querySelector('.error-message');
        if (errorMessage) {
            errorMessage.remove();
        }
    }
});