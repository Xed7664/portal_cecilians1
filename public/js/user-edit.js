(function () {
    "use strict";

    // Wait for the DOM to be ready
    $(function () {

        // General
        $('.admin_user_edit #home-justified #saveChangesBtn').on('click', function (event) {
            event.preventDefault();

            // Get user information from input fields
            var inputSchoolID = $('#home-justified #inputSchoolID').val();
            var inputFullname = $('#home-justified #inputFullname').val();
            var inputEmail = $('#home-justified #inputEmail').val();
            var inputRole = $('#home-justified #inputRole').val();
            var inputType = $('#home-justified #inputType').val();

            var data = {
                inputTab: 'general',
                inputSchoolID: inputSchoolID,
                inputFullname: inputFullname,
                inputEmail: inputEmail,
                inputRole: inputRole,
                inputType: inputType
            };

            // Make AJAX request
            $.ajax({
                url: './../../../ajax/admin/user_edit',
                type: 'POST',
                data: data,
                success: function (response) {
                    if (response.result) {
                        createToast("Done", response.message, "success");
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    } else {
                        createToast("Done", response.message, "warning");
                    }
                },
                error: function (response) {
                    // Handle error response
                    createToast("Error", response.responseJSON.message, "danger");
                }
            });
        });

        $('.admin_user_edit #home-justified #send-changepass').on('click', function (event) {
            event.preventDefault();

            var inputSchoolID = $('#home-justified #inputSchoolID').val();
            var inputFullname = $('#home-justified #inputFullname').val();

            var data = {
                process: 'changepass',
                inputSchoolID: inputSchoolID,
                inputFullname: inputFullname,

            };

            // Make AJAX request
            $.ajax({
                url: './../../../ajax/admin/user_email',
                type: 'POST',
                data: data,
                success: function (response) {
                    if (response.result) {
                        createToast("Done", response.message, "success");
                    } else {
                        createToast("Done", response.message, "warning");
                    }
                },
                error: function (response) {
                    // Handle error response
                    createToast("Error", response.responseJSON.message, "danger");
                }
            });

        });

        $('.admin_user_edit #profile-justified #unlinkGoogle').on('click', function (event) {
            event.preventDefault();

            var inputSchoolID = $('#home-justified #inputSchoolID').val();
            var inputFullname = $('#home-justified #inputFullname').val();

            var data = {
                process: 'google',
                inputSchoolID: inputSchoolID,
                inputFullname: inputFullname,

            };

            // Make AJAX request
            $.ajax({
                url: './../../../ajax/admin/user_unlink',
                type: 'POST',
                data: data,
                success: function (response) {
                    if (response.result) {
                        createToast("Done", response.message, "success");
                    } else {
                        createToast("Done", response.message, "warning");
                    }
                },
                error: function (response) {
                    // Handle error response
                    createToast("Error", response.responseJSON.message, "danger");
                }
            });

        });

        $('.admin_user_edit #home-justified #send-verification').on('click', function (event) {
            event.preventDefault();

            var inputSchoolID = $('#home-justified #inputSchoolID').val();
            var inputFullname = $('#home-justified #inputFullname').val();

            var data = {
                process: 'verification',
                inputSchoolID: inputSchoolID,
                inputFullname: inputFullname,

            };

            // Make AJAX request
            $.ajax({
                url: './../../../ajax/admin/user_email',
                type: 'POST',
                data: data,
                success: function (response) {
                    if (response.result) {
                        createToast("Done", response.message, "success");
                    } else {
                        createToast("Done", response.message, "warning");
                    }
                },
                error: function (response) {
                    // Handle error response
                    createToast("Error", response.responseJSON.message, "danger");
                }
            });

        });

        /*$('.admin_user_edit #profile-justified #saveChangesBtn').on('click', function (event) {
            event.preventDefault();

            // Get user information from input fields
            var inputSchoolID = $('#home-justified #inputSchoolID').val();
            var inputFullname = $('#home-justified #inputFullname').val();
            var inputBio = $('#profile-justified #inputBio').val();
            var inputFacebook = $('#profile-justified #inputFacebook').val();
            var inputInstagram = $('#profile-justified #inputInstagram').val();
            var inputTiktok = $('#profile-justified #inputTiktok').val();
            var inputYoutube = $('#profile-justified #inputYoutube').val();

            // Get profile information from form
            var profileData = new FormData($('#profileForm')[0]);

            profileData.append('inputTab', 'profile');
            profileData.append('inputSchoolID', inputSchoolID);
            profileData.append('inputFullname', inputFullname);
            profileData.append('inputBio', inputBio);
            profileData.append('inputFacebook', inputFacebook);
            profileData.append('inputInstagram', inputInstagram);
            profileData.append('inputTiktok', inputTiktok);
            profileData.append('inputYoutube', inputYoutube);

            // Make AJAX request
            $.ajax({
                url: './../../../ajax/admin/user_edit',
                type: 'POST',
                data: profileData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.result) {
                        createToast("Done", response.message, "success");
                    } else {
                        createToast("Done", response.message, "warning");
                    }
                },
                error: function (response) {
                    // Handle error response
                    createToast("Error", response.responseJSON.message, "danger");
                }
            });
        });
        */

        $('.admin_user_edit #profile-justified #saveChangesBtn').on('click', function (event) {
            event.preventDefault();

            var profileData = new FormData($('#profileForm')[0]);

            // Iterate through input fields outside profileForm
            $('#inputBio, #inputFacebook, #inputInstagram, #inputTiktok, #inputYoutube').each(function () {
                var field = $(this);
                var fieldName = field.attr('id');
                var currentValue = field.val();
                var originalValue = field.data('original-value');

                // Check if the current value is different from the original value, or if the original value is empty
                if (currentValue !== originalValue || (currentValue && !originalValue)) {
                    profileData.append(fieldName, currentValue);
                }
            });

            // Other fields like inputSchoolID and inputFullname
            profileData.append('inputTab', 'profile');
            profileData.append('inputSchoolID', $('#home-justified #inputSchoolID').val());
            profileData.append('inputFullname', $('#home-justified #inputFullname').val());

            // Make AJAX request
            $.ajax({
                url: './../../../ajax/admin/user_edit',
                type: 'POST',
                data: profileData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.result) {
                        createToast("Done", response.message, "success");
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    } else {
                        createToast("Done", response.message, "warning");
                    }
                },
                error: function (response) {
                    // Handle error response
                    createToast("Error", response.responseJSON.message, "danger");
                }
            });
        });

        $('.admin_user_edit #permission-justified #saveChangesBtn').on('click', function (event) {
            event.preventDefault();

            // Get user information from input fields
            var inputSchoolID = $('#home-justified #inputSchoolID').val();
            var inputFullname = $('#home-justified #inputFullname').val();

            // Get turned-on permission numbers
            var permissionNumbers = [];
            $('.permission-checkbox:checked').each(function () {
                var permissionNumber = this.id.replace('permission-', '');
                permissionNumbers.push(permissionNumber);
            });

            // Convert permission numbers array to a comma-separated string
            var permissions = permissionNumbers.join(',');

            var formData = new FormData();
            formData.append('inputTab', 'permission');
            formData.append('inputSchoolID', inputSchoolID);
            formData.append('inputFullname', inputFullname);
            formData.append('inputPermissions', permissions);

            // Loop through dynamic permission inputs and collect their values
            $('[id^="permission-"][id$="-data"]').each(function () {
                var permissionId = this.id.split('-')[1];
                var permissionData = $(this).val();
                formData.append('permission-' + permissionId + '-data', permissionData);
            });

            $('[id^="permission-"][id$="-id"]').each(function () {
                var permissionId = this.id.split('-')[1];
                var permissionIdData = $(this).val();
                formData.append('permission-' + permissionId + '-id', permissionIdData);
            });

            // Make AJAX request
            $.ajax({
                url: './../../../ajax/admin/user_edit',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.result) {
                        createToast("Done", response.message, "success");
                    } else {
                        createToast("Done", response.message, "warning");
                    }
                },
                error: function (response) {
                    // Handle error response
                    createToast("Error", response.responseJSON.message, "danger");
                }
            });
        });
    });
})();