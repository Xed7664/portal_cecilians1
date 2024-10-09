(function () {
    "use strict";

    // Wait for the DOM to be ready
    $(function () {

        // Event Name
        $('input[name="inputTitle"]').on('input', function () {
            var newTitle = $(this).val();
            $('.preview #name').text(newTitle);
        });

        // Event Location
        $('input[name="inputLocation"]').on('input', function () {
            var newLoc = $(this).val();
            if (newLoc.length <= 0) {
                $('.preview #location').text('');
            } else {
                $('.preview #location').text('- (' + newLoc + ')');
            }
        });

        $('#beforePrev').on('click', function () {
            var badgeText = '';
            var badgeClass = '';

            badgeText = 'Later';
            badgeClass = 'badge bg-info';

            $('.preview #badge').text(badgeText).removeClass().addClass(badgeClass);
        });

        $('#todayPrev').on('click', function () {
            var badgeText = '';
            var badgeClass = '';

            badgeText = 'Ongoing';
            badgeClass = 'badge bg-success';

            $('.preview #badge').text(badgeText).removeClass().addClass(badgeClass);
        });

        $('#afterPrev').on('click', function () {
            var badgeText = '';
            var badgeClass = '';

            badgeText = 'Ended';
            badgeClass = 'badge bg-danger';

            $('.preview #badge').text(badgeText).removeClass().addClass(badgeClass);
        });

        $('#beforePrev, #todayPrev, #afterPrev').click(function () {
            $('#beforePrev, #todayPrev, #afterPrev').removeClass('active');

            $(this).addClass('active');
        });

        $('input[name="inputStartDate"], input[name="inputEndDate"], input[name="inputStartTime"], input[name="inputEndTime"]').on('change', function () {
            var startDate = new Date($('input[name="inputStartDate"]').val());
            var startTime = $('input[name="inputStartTime"]').val();
            var endDate = new Date($('input[name="inputEndDate"]').val());
            var endTime = $('input[name="inputEndTime"]').val();

            var startDateTime = new Date(startDate.toDateString() + ' ' + startTime);
            var endDateTime = new Date(endDate.toDateString() + ' ' + endTime);

            var duration = Math.abs(endDateTime - startDateTime) / 36e5; // Calculate duration in hours

            if (duration > 1) {
                $('#duration').text(duration + ' hours');
            } else {
                $('#duration').text(duration + ' hour');
            }
        });

        $('select[name="inputType"]').on('change', function () {
            var eventType = $(this).val();
            var badgeText = '';
            var badgeClass = '';
            var imagePath = '';
            console.log(eventType);

            switch (eventType) {
                case 'general':
                    badgeText = 'Ongoing';
                    badgeClass = 'badge bg-success';
                    imagePath = '';

                    $('#beforePrev').prop('disabled', false);
                    $('#todayPrev').prop('disabled', false);
                    $('#afterPrev').prop('disabled', false);

                    $('#todayPrev').addClass('active');
                    break;
                case 'seminar':
                    badgeText = 'Ongoing';
                    badgeClass = 'badge bg-success';
                    imagePath = '';

                    $('#beforePrev').prop('disabled', false);
                    $('#todayPrev').prop('disabled', false);
                    $('#afterPrev').prop('disabled', false);

                    $('#todayPrev').addClass('active');
                    break;
                case 'exam':
                    badgeText = 'Ongoing';
                    badgeClass = 'badge bg-success';
                    imagePath = '';

                    $('#beforePrev').prop('disabled', false);
                    $('#todayPrev').prop('disabled', false);
                    $('#afterPrev').prop('disabled', false);

                    $('#todayPrev').addClass('active');
                    break;
                case 'holiday':
                    badgeText = 'No Class';
                    badgeClass = 'badge bg-danger';
                    imagePath = '';

                    $('#beforePrev').prop('disabled', true);
                    $('#todayPrev').prop('disabled', true);
                    $('#afterPrev').prop('disabled', true);
                    break;
                case 'mass':
                    badgeText = 'Ongoing';
                    badgeClass = 'badge bg-success';
                    imagePath = './../../assets/img/calendar/mass.jpg';

                    $('#beforePrev').prop('disabled', false);
                    $('#todayPrev').prop('disabled', false);
                    $('#afterPrev').prop('disabled', false);

                    $('#todayPrev').addClass('active');
                    break;
                case 'suspension':
                    badgeText = 'No Class';
                    badgeClass = 'badge bg-danger';
                    imagePath = '';

                    $('#beforePrev').prop('disabled', true);
                    $('#todayPrev').prop('disabled', true);
                    $('#afterPrev').prop('disabled', true);
                    break;
                case 'meeting':
                    badgeText = 'Ongoing';
                    badgeClass = 'badge bg-success';
                    imagePath = '';

                    $('#beforePrev').prop('disabled', false);
                    $('#todayPrev').prop('disabled', false);
                    $('#afterPrev').prop('disabled', false);

                    $('#todayPrev').addClass('active');
                    break;
                default:
                    badgeText = '';
                    badgeClass = 'badge';
                    imagePath = '';

                    $('#beforePrev').prop('disabled', true);
                    $('#todayPrev').prop('disabled', true);
                    $('#afterPrev').prop('disabled', true);
                    break;
            }

            $('.preview #badge').text(badgeText).removeClass().addClass(badgeClass);

            if (imagePath) {
                $('.preview img').attr('src', imagePath);
                $('.preview img').show();
                $('.preview #details').removeClass().addClass("card-img-overlay");
            } else {
                $('.preview img').attr('src', '');
                $('.preview img').hide();
                $('.preview #details').removeClass().addClass("card-body");
            }
        });

        $("#eventForm").submit(function (event) {
            // Prevent the default form submission
            event.preventDefault();

            // Serialize the form data into a JSON object
            var formData = new FormData(this);

            // Send the AJAX request
            $.ajax({
                type: "POST",
                url: "./../../ajax/admin/event_add",
                data: formData, 
                processData: false, 
                contentType: false, 
                success: function (response) {
                    if (response.result) {
                        const eventId = response.data.id;
                        // Handle success response
                        createToast("Done", response.message, "success");

                        setTimeout(() => {
                            window.location.href = `./../../../admin/events/${eventId}`;
                        }, 2000);
                    } else {
                        createToast("Done", response.message, "warning");
                    }
                },
                error: function (response) {
                    createToast("Error", response.responseJSON.message, "danger");
                }
            });
        });


    });
})();