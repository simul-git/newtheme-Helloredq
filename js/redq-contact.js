jQuery(document).ready(function($) {
    $('#redq-contact-form').on('submit', function(e) {
        e.preventDefault(); // Prevent form submission

        var name = $('#name').val();
        var email = $('#email').val();

        // Validate form fields (optional)
        if (!name || !email) {
            alert('Please fill out both fields.');
            return;
        }

        // Send AJAX request
        $.ajax({
            url: redq_contact_params.ajax_url,
            type: 'POST',
            data: {
                action: 'redq_contact_form',
                name: name,
                email: email,
                nonce: redq_contact_params.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('#form-message').show(); // Show success message
                    $('#redq-contact-form')[0].reset(); // Reset form fields
                } else {
                    alert('Form submission failed. Try again.');
                }
            },
            error: function() {
                alert('There was an error processing the request.');
            }
        });
    });
});
