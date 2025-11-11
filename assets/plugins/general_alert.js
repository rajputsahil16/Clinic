// Generalized alert handler using SweetAlert
// Usage:
// showAlert('success');
// showAlert('error', 'Custom Title', 'Custom message', 2000);
// showAlert('warning');
// showAlert('info', 'Info Title', 'Info message');

function showAlert(type = 'info', title, message, timer = 1500) {
    const defaults = {
        success: {
            title: 'Success!',
            message: 'Operation completed successfully.',
            icon: 'success'
        },
        error: {
            title: 'Error!',
            message: 'Something went wrong.',
            icon: 'error'
        },
        warning: {
            title: 'Warning!',
            message: 'Please check your input.',
            icon: 'warning'
        },
        info: {
            title: 'Info',
            message: 'Here is some information.',
            icon: 'info'
        }
    };

    const alertType = defaults[type] ? type : 'info';
    const alertTitle = title || defaults[alertType].title;
    const alertMessage = message || defaults[alertType].message;
    const alertIcon = defaults[alertType].icon;

    Swal.fire({
        title: alertTitle,
        text: alertMessage,
        icon: alertIcon,
        timer: timer,
        showConfirmButton: false
    });
}

// Example usage:
// showAlert('success');
// showAlert('error', 'Custom Error Title', 'Custom error message');
// showAlert('warning');
