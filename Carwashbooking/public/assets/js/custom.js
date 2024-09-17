var base_url = $('#mainurl').val();

$(document).ready(function ()
{
    $(function () {
        $(".Preloader").fadeOut(1000, function () {
            $(".for-loader").fadeIn(400);
        });
    });

    $(".select2").select2();

    $(".flatpickr").flatpickr({
        minDate: "today"
    });

    Dropzone.options.dropzone =
    {
        maxFilesize: 12,
        renameFile: function(file) {
            var dt = new Date();
            var time = dt.getTime();
            return time+file.name;
        },
        acceptedFiles: ".jpeg,.jpg,.png,.gif",
        timeout: 5000,
        success: function(file, response)
        {
            console.log(response);
        },
        error: function(file, response)
        {
            return false;
        }
    }

    if (window.location.origin + window.location.pathname == $('#mainurl').val() + '/admin/home')
    {
        $.ajax({
            headers:
            {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: base_url + '/admin/appointmentChart',
            success: function (result)
            {
                console.log('result',result);
                initChart(result);
            },
            error: function (err) {
                console.log('err ', err)
            }
        });

        $.ajax({
            headers:
            {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: base_url + '/admin/userChart',
            success: function (result)
            {
                console.log('result',result);
                userChart(result);
            },
            error: function (err) {
                console.log('err ', err)
            }
        });
    }

    $('.textarea_editor').wysihtml5({
        "stylesheets": false,
        "size": 'small',
        "html": true,
        "format-code": true,
        "image": false,
    });

    $('#appointment_calender').change(function()
    {
        $.ajax({
            headers:
            {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",

            url: base_url + '/admin/timeslots',
            data:
            {
                date: this.value,
                coworker_id: $('#appointment_coworker_id').select2().val()
            },
            success: function (result) {
                console.log('result', result);
                if (result.success == true) {
                    $('#timeslot').html('');
                    result.data.forEach(element => {
                        $('#timeslot').append('<option value="' + element.start_time + '" selected>' + element.start_time + '</option>');
                    });
                }
            },
            error: function (err) {
                console.log('err ', err)
            }
        });
    })

    $('#appointment_coworker_id').change(function ()
    {
        var category_id = $('#category_id').select2().val();

        $.ajax({
            headers:
            {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: base_url + '/admin/appointment_service',
            data: {
                coworker_id : this.value,
                category_id : category_id
            },
            method: 'post',
            success: function (result)
            {
                console.log(result);
                if (result.success == true)
                {
                    $('#service').html('');
                    result.data.forEach(element =>
                    {
                        console.log(element);
                        $('#service').append('<option value="' + element.id + '">' + element.service_name + '</option>');
                    });
                }
                if(result.success == false)
                {
                    $('#service').html('');
                }
            },
            error: function (err)
            {
                console.log('err ', err)
            }
        })
    });

    $('#sms_verification').change(function ()
    {
        $('.alert').hide();
        var user_verification = $('#user_verification').is(":checked");
        var mail_verification = $('#mail_verification').is(":checked");
        if(user_verification == true)
        {
            if(this.checked == false &&  mail_verification == false)
            {
                $('.alert').show();
            }
        }
    });

    $('#mail_verification').change(function ()
    {
        $('.alert').hide();
        var user_verification = $('#user_verification').is(":checked");
        var sms_verification = $('#sms_verification').is(":checked");
        if(user_verification == true)
        {
            if(this.checked == false &&  sms_verification == false)
            {
                $('.alert').show();
            }
        }
    });

    $("#offer_category").change(function () {
        var category_id = $(this).val();
        $.ajax({
            headers:
            {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",

            url: base_url + '/admin/offer_category',
            data:
            {
                category_id: category_id,
            },
            success: function (result) {
                console.log('result', result);
                if (result.success == true) {
                    $('#service_id').html('');
                    result.data.forEach(element => {
                        $('#service_id').append('<option value="' + element.id + '" selected>' + element.service_name + '</option>');
                    });
                }
            },
            error: function (err) {
                console.log('err ', err)
            }
        });
    });

    $("#update_offer_category").change(function ()
    {
        var category_id = $(this).val();
        $.ajax({
            headers:
            {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",

            url: base_url + '/admin/update_offer_category',
            data:
            {
                category_id: category_id,
            },
            success: function (result) {
                console.log('result', result);
                if (result.success == true) {
                    $('#update_service_id').html('');
                    result.data.forEach(element => {
                        $('#update_service_id').append('<option value="' + element.id + '" selected>' + element.service_name + '</option>');
                    });
                }
            },
            error: function (err) {
                console.log('err ', err)
            }
        });
    });

});

var DatatableBasic = (function() {

    // Variables
    var $dtBasic = $('#datatable-basic');

    // Methods

    function init($this) {

        var options = {
            keys: !0,
            select: {
                style: "multi"
            },
            language: {
                paginate: {
                    previous: "<i class='fas fa-angle-left'>",
                    next: "<i class='fas fa-angle-right'>"
                }
            },
        };

        // Init the datatable

        var table = $this.on( 'init.dt', function () {
            $('div.dataTables_length select').removeClass('custom-select custom-select-sm');

        }).DataTable(options);
    }

    // Events

    if ($dtBasic.length) {
        init($dtBasic);
    }

})();

var DatatableBasic = (function() {

    // Variables
    var $dtBasic = $('.datatable');

    // Methods
    function init($this) {

        var options = {
            keys: !0,
            select: {
                style: "multi"
            },
            language: {
                paginate: {
                    previous: "<i class='fas fa-angle-left'>",
                    next: "<i class='fas fa-angle-right'>"
                }
            },
        };

        // Init the datatable

        var table = $this.on( 'init.dt', function () {
            $('div.dataTables_length select').removeClass('custom-select custom-select-sm');

        }).DataTable(options);
    }

    // Events

    if ($dtBasic.length) {
        init($dtBasic);
    }

})();


function eventClicked(e)
{
    $.ajax({
        url: base_url + '/admin/calendarData/'+e.id,
        method: 'get',
        success: function (result)
        {
            $('#show_modal').modal();
            $('#show_modal').addClass('show');
            $('#user_image').attr('src', base_url + '/images/upload/' + result.data.user.image);
            $('#appointment_id').html(result.data.appointment_id);
            $('#user_name').html(result.data.user.name);
            $('#service_at').html(result.data.service_type);
            $('#date').html(result.data.date);
            $('#start_time').html(result.data.start_time);
            $('#end_time').html(result.data.end_time);
            $('#duration').html(result.data.duration + ' Min');
            $('#amount').html(result.data.amount);
            $('#Coworker_name').html(result.data.coworker.name);

            $('#service_name').html('');
            result.data.service.forEach(element =>
            {
                $('#service_name').append(element.service_name + ' ');
            });

            if(result.data.appointment_status == 'PENDING')
            {
                $('#appointment_status').css('color','#2236a8');
                $('#appointment_status').html(result.data.appointment_status);
            }

            if(result.data.appointment_status == 'CANCEL')
            {
                $('#appointment_status').css('color','#b3092b');
                $('#appointment_status').html(result.data.appointment_status);
            }

            if(result.data.appointment_status == 'REJECT')
            {
                $('#appointment_status').css('color','#b3092b');
                $('#appointment_status').html(result.data.appointment_status);
            }

            if(result.data.appointment_status == 'APPROVE')
            {
                $('#appointment_status').css('color','#05879e');
                $('#appointment_status').html(result.data.appointment_status);
            }

            if(result.data.appointment_status == 'COMPLETE')
            {
                $('#appointment_status').css('color','#1a8a59');
                $('#appointment_status').html(result.data.appointment_status);
            }

            if(result.data.payment_status == "0")
            {
                $('#payment_status').css('color', 'red');
                $('#payment_status').html('Payment Not complete' );
            }
            else
            {
                $('#payment_status').css('color', 'green');
                $('#payment_status').html('Payment complete');
            }
            if (result.data.service_type == 'HOME')
            {
                $('.address').html('');
                $('.table').append('<tr class="address"><td>Address</td><td>' + result.data.address + '</td></tr>');
            }
            else
            {
                $('.address').html('');
            }
        },
        error: function (err)
        {
            console.log('err ', err)
        }
    })
}

function add_btn()
{
    $('.form-control').removeClass('is-invalid')
    $(".invalid-feedback").html('');
}

function initChart(data) {

    var ctx = document.getElementById("chart-orders");
    var ordersChart = new Chart(ctx, {
        type: 'bar',
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        callback: function(value) {
                            if (!(value % 10)) {
                                //return '$' + value + 'k'
                                return value
                            }
                        }
                    }
                }]
            },
            tooltips: {
                callbacks: {
                    label: function(item, data) {
                        var label = data.datasets[item.datasetIndex].label || '';
                        var yLabel = item.yLabel;
                        var content = '';

                        if (data.datasets.length > 1) {
                            content += '<span class="popover-body-label mr-auto">' + label + '</span>';
                        }

                        content += '<span class="popover-body-value">' + yLabel + '</span>';

                        return content;
                    }
                }
            }
        },
        data: {
            labels: data.label,
            datasets: [{
                label: 'Appointment',
                data: data.data
            }]
        }
    });
}

function userChart(data) {
    var ctx = document.getElementById("chart-sales");
    var salesChart = new Chart(ctx, {
        type: 'line',
        options: {
            scales: {
                yAxes: [{
                    gridLines: {
                        color: Charts.colors.gray[900],
                        zeroLineColor: Charts.colors.gray[900]
                    },
                    ticks: {
                        callback: function(value) {
                            if (!(value % 10)) {
                                return value;
                            }
                        }
                    }
                }]
            },
            tooltips: {
                callbacks: {
                    label: function(item, data) {
                        var label = data.datasets[item.datasetIndex].label || '';
                        var yLabel = item.yLabel;
                        var content = '';

                        if (data.datasets.length > 1) {
                            content += '<span class="popover-body-label mr-auto">' + label + '</span>';
                        }

                        content += '<span class="popover-body-value">' + yLabel + 'user</span>';
                        return content;
                    }
                }
            }
        },
        data: {
            labels: data.label,
            datasets: [{
                label: 'Performance',
                data: data.data
            }]
        }
    });
};

function create(url,formData)
{
    var formData = new FormData($('#'+formData)[0]);
    console.log(formData);
    $.ajax({
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        data:formData,
        cache:false,
        contentType: false,
        processData: false,
        url: base_url + '/' + url,
        success: function (result)
        {
            if (result.success == true)
            {

            }
        },
        error: function (err) {
            console.log('err ', err)
            for (let v1 of Object.keys( err.responseJSON.errors))
            {
                var element = document.getElementsByName(v1)[0];
                element.classList.add("is-invalid");
                $(".form-group ."+v1).html(Object.values(err.responseJSON.errors[v1]));
            }
        }
    });
}

function notificationTemplateEdit(id) {
    $.ajax({
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: base_url + '/admin/notification_template/' + id + '/edit/',
        data:
        {
            id: id,
        },
        success: function (result) {
            console.log('result', result);
            if (result.success == true) {
                $('#notification_content').val(result.data.notification_content);
                $("h2").html(result.data.title);
                $('#subject').val(result.data.subject);
                $('#title').val(result.data.title);
                $('.textarea_editor').data("wysihtml5").editor.setValue(result.data.mail_content);
                $('.edit_notification_template_form').attr("action", base_url + "/admin/notification_template/" + result.data.id);
            }
        },
        error: function (err) {
            console.log('err ', err)
        }
    });
}

function category_edit(id)
{
    $.ajax({
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",

        url: base_url + '/admin/category/' + id + '/edit',
        data:
        {
            value: this.value,
        },
        success: function (result) {
            // console.log(this);
            console.log('result', result);
            if (result.success == true) {
                $('.edit_category').attr("action", base_url + "/admin/category/" + result.data.id);
                $('#update_image').attr('src', base_url + '/images/upload/' + result.data.image);
                $('#category_name').val(result.data.category_name);
                $(this, '#update_status').prop('checked') == true ? 1 : 0;
                // $('#update_status').val(result.data.status);
                // $('input[name="status"][value="1"]').prop('checked', true);
            }
        },
        error: function (err) {
            console.log('err ', err)
        }
    });
}

function faq_edit(id)
{
    $.ajax({
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",

        url: base_url + '/admin/faq/' + id + '/edit',
        success: function (result) {
            console.log('result', result);
            if (result.success == true) {
                $('.edit_faq').attr("action", base_url + "/admin/faq/" + result.data.id);
                $('#question').val(result.data.question)
                $('#answer').val(result.data.answer)
                $('#for').val(result.data.for)
            }
        },
        error: function (err) {
            console.log('err ', err)
        }
    });
}

function serivce_edit(id)
{
    $.ajax({
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",

        url: base_url + '/admin/service/' + id + '/edit',
        success: function (result)
        {
            console.log('result', result);
            if (result.success == true) {
                $('.edit_service').attr("action", base_url + "/admin/service/" + result.data.id);
                $('#update_image').attr('src', base_url + '/images/upload/' + result.data.image);
                $('#service_name').val(result.data.service_name)
                $('#price').val(result.data.price)
                $('#description').val(result.data.description);
                $('#phone').val(result.data.phone);
                $('#duration').val(result.data.duration);

                $('select[name="coworker_id"] option').attr("selected", false);
                $('select[name="coworker_id"] option[value="' + result.data.coworker_id + '"]').attr("selected", true);
                $('select[name="coworker_id"] option[value="' + result.data.coworker_id + '"]').trigger('change');

                $('#category_id').val(result.data.category_id);
                $('#category_id').trigger('change');
            }
        },
        error: function (err) {
            console.log('err ', err)
        }
    });
}

function service_show(id)
{
    $.ajax({
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",

        url: base_url + '/admin/service/' + id,
        success: function (result) {
            console.log('result', result);
            if (result.success == true)
            {
                $('#show_image').attr('src', base_url + '/images/upload/' + result.data.image);
                $('#show_service_name').html(result.data.service_name)
                $('#show_price').html(result.data.price)
                $('#show_description').html(result.data.description);
                $('#show_phone').html(result.data.phone);
                $('#show_duration').html(result.data.duration);
                $('#show_category').html('');
                result.data.category.forEach(element =>
                {
                    $('#show_category').append(element.category_name + ' ');
                });
                $('#show_coworker').html(result.data.co_worker.name);
            }
        },
        error: function (err) {
            console.log('err ', err)
        }
    });
}

function appoinment_show(id) {
    $.ajax({
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: base_url + '/admin/appointment/' + id,
        success: function (result)
        {
            console.log('result', result);
            if (result.success == true)
            {
                $('#user_image').attr('src', base_url + '/images/upload/' + result.data.user.image);
                $('#show_appointment').html(result.data.appointment_id)
                $('#show_user').html(result.data.user.name)
                $('#show_duration').html(result.data.duration);
                $('#show_amount').html(result.data.amount);
                $('#show_duration').html(result.data.duration);
                $('#show_date').html(result.data.date);
                $('#show_start_time').html(result.data.start_time);
                $('#show_end_time').html(result.data.end_time);
                $('#show_service_at').html(result.data.service_type);
                $('#show_appointment_status').html(result.data.appointment_status);
                $('#show_payment_type').html(result.data.payment_type);
                $('#show_service').html('');
                $('#show_address').html(result.data.address);
                $('#show_payment_type').html(result.data.payment_type);
                result.data.service.forEach(element =>
                {
                    $('#show_service').append(element.service_name + ' ');
                });
                $('#show_coworker').html(result.data.co_worker.name);

                if (result.data.appointment_status == 'PENDING') {
                    $('#show_appointment_status').css('color', '#2236a8');
                    $('#show_appointment_status').html(result.data.appointment_status);
                }

                if (result.data.appointment_status == 'CANCEL') {
                    $('#show_appointment_status').css('color', '#b3092b');
                    $('#show_appointment_status').html(result.data.appointment_status);
                }

                if (result.data.appointment_status == 'REJECT') {
                    $('#show_appointment_status').css('color', '#b3092b');
                    $('#show_appointment_status').html(result.data.appointment_status);
                }

                if (result.data.appointment_status == 'APPROVE') {
                    $('#show_appointment_status').css('color', '#05879e');
                    $('#show_appointment_status').html(result.data.appointment_status);
                }

                if (result.data.appointment_status == 'COMPLETE') {
                    $('#show_appointment_status').css('color', '#1a8a59');
                    $('#show_appointment_status').html(result.data.appointment_status);
                }

                if (result.data.payment_status == "0") {
                    $('#show_payment_status').css('color', 'red');
                    $('#show_payment_status').html('Payment Not complete');
                }
                else {
                    $('#show_payment_status').css('color', 'green');
                    $('#show_payment_status').html('Payment complete');
                }
                if (result.data.service_type == 'HOME') {
                    $('.address').html('');
                    $('.table').append('<tr class="address"><td>Address</td><td>' + result.data.address + '</td></tr>');
                }
                else {
                    $('.address').html('');
                }
            }
        },
        error: function (err) {
            console.log('err ', err)
        }
    });
}

function coworker_edit(id)
{
    $.ajax({
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",

        url: base_url + '/admin/coworkers/' + id + '/edit',
        data:
        {
            value: this.value,
        },
        success: function (result) {
            console.log('result', result);
            if (result.success == true)
            {
                $('.edit_coworker').attr("action", base_url + "/admin/coworkers/" + result.data.id);
                $('#update_image').attr('src', base_url + '/images/upload/' + result.data.image);
                $('#name').val(result.data.name)
                $('#email').val(result.data.email)
                $('#description').val(result.data.description);
                $('#experience').val(result.data.experience);
                $('#phone').val(result.data.phone);
                $('select[name="start_time"] option').attr("selected", false);
                $('select[name="start_time"] option[value="' + result.data.start_time + '"]').attr("selected", true);
                $('select[name="start_time"] option[value="' + result.data.start_time + '"]').trigger('change');
                $('select[name="end_time"] option').attr("selected", false);

                $('select[name="end_time"] option[value="' + result.data.end_time + '"]').attr("selected", true);
                $('select[name="end_time"] option[value="' + result.data.end_time + '"]').trigger('change');
            }
        },
        error: function (err) {
            console.log('err ', err)
        }
    });
}

function coworker_show(id)
{
    $.ajax({
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",

        url: base_url + '/admin/coworkers/' + id,
        data:
        {
            value: this.value,
        },
        success: function (result) {
            console.log('result', result);
            if (result.success == true)
            {
                $('#show_image').attr('src', base_url + '/images/upload/' + result.data.image);
                $('#show_worker_name').html(result.data.name);
                $('#show_start_time').html(result.data.start_time);
                $('#show_end_time').html(result.data.end_time);
                $('#show_description').html(result.data.description);
                $('#show_experience').html(result.data.experience);
                $('#show_phone_no').html(result.data.phone);
                $('#show_email').html(result.data.email);
            }
        },
        error: function (err) {
            console.log('err ', err)
        }
    });
}

function offer_edit(id)
{
    $.ajax({
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",

        url: base_url + '/admin/offer/' + id + '/edit',
        data:
        {
            value: this.value,
        },
        success: function (result)
        {
            console.log('result', result.data.cat_id);
            if (result.success == true)
            {
                $('.edit_offer').attr("action", base_url + "/admin/offer/" + result.data.id);
                $('#update_image').attr('src', base_url + '/images/upload/' + result.data.image);
                $('#code').val(result.data.code);
                $('#start_date').val(result.data.start_date);
                $('#end_date').val(result.data.end_date);
                $('#discount').val(result.data.discount);
                $('#description').val(result.data.description);

                $('#update_type').val(result.data.type);
                $('#update_type').trigger('change');

                $('#update_offer_category').val(result.data.cat_id);
                $('#update_offer_category').trigger('change');

                $('#update_service_id').val(result.data.serv_id);
                $('#update_service_id').trigger('change');
            }
        },
        error: function (err)
        {
            console.log('err ', err)
        }
    });
}

function offer_show(id)
{
    $.ajax({
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",

        url: base_url + '/admin/offer/' + id,
        data:
        {
            value: this.value,
        },
        success: function (result)
        {
            console.log('result', result);
            if (result.success == true)
            {
                $('#show_image').attr('src', base_url + '/images/upload/' + result.data.image);
                $('#show_code').html(result.data.code);
                $('#show_discount').html(result.data.discount);
                $('#show_start_date').html(result.data.start_date);
                $('#show_end_date').html(result.data.end_date);
                $('#show_description').html(result.data.description);
                $('#show_service').html('');
                $('#show_category').html('');
                // result.data.service.forEach(element =>
                // {
                    $('#show_service').append(result.data.ser_name);
                // });
                result.data.category.forEach(element =>
                {
                    $('#show_category').append(element.category_name + '  ');
                });
            }
        },
        error: function (err) {
            console.log('err ', err)
        }
    });
}

function user_edit(id)
{
    $.ajax({
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",

        url: base_url + '/admin/user/' + id + '/edit',
        data:
        {
            value: this.value,
        },
        success: function (result)
        {
            console.log('result', result.data.cat_id);
            if (result.success == true)
            {
                $('.edit_user').attr("action", base_url + "/admin/user/" + result.data.id);
                $('#update_image').attr('src', base_url + '/images/upload/' + result.data.image);
                $('#name').val(result.data.name);
                $('#phone').val(result.data.phone);
                $('#email').val(result.data.email);
            }
        },
        error: function (err) {
            console.log('err ', err)
        }
    });
}

function language_edit(id)
{
    $.ajax({
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",

        url: base_url + '/admin/language/' + id + '/edit',
        data:
        {
            value: this.value,
        },
        success: function (result)
        {
            if (result.success == true)
            {
                $('.edit_language').attr("action", base_url + "/admin/language/" + result.data.id);
                $('#update_image').attr('src', base_url + '/images/upload/' + result.data.image);
                $('#name').val(result.data.name);
                $('#direction').val(result.data.direction);
                $('#direction').trigger('change');
            }
        },
        error: function (err) {
            console.log('err ', err)
        }
    });
}

function change_status(url, id)
{
    $.ajax({
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: base_url + '/' + url + '/change_status',
        data:
        {
            id: id,
        },
        success: function (result) {
            console.log('result', result);
            if (result.success == true) {
                location.reload();
            }
        },
        error: function (err) {
            console.log('err ', err)
        }
    });
}

function role_edit(id)
{
    $.ajax({
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",

        url: base_url + '/admin/role/' + id + '/edit',
        data:
        {
            value: this.value,
        },
        success: function (result)
        {
            if (result.success == true)
            {
                $('.edit_role').attr("action", base_url + "/admin/role/" + result.data.id);
                $('#update_name').val(result.data.name);

                var perm = new Array();
                for (let p = 0; p <  result.data.permissions.length; p++)
                {
                    perm.push(result.data.permissions[p].id);
                }
                $('#update_permission').val(perm);
                $('#update_permission').trigger('change');
                if (result.data.name == 'admin') {
                    $('#update_permission').prop('disabled',true);
                }
                else
                {
                    $('#update_permission').prop('disabled',false);
                }
            }
        },
        error: function (err) {
            console.log('err ', err)
        }
    });
}

function appointment_status(id)
{
    var value = $('#appointment_status'+id).val();
    $.ajax({
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: base_url + '/admin/appointment_status',
        data:
        {
            appointment_status: value,
            id: id,
        },
        success: function (result)
        {
            if (result.success == true)
            {
                location.reload();
            }
        },
        error: function (err) {
            console.log('err ', err)
        }
    });
}

function deleteData(url, id)
{
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't to delete this record!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value)
        {
            console.log(base_url + '/' + url + '/' + id);
            $.ajax({
                headers:
                {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "DELETE",
                dataType: "JSON",
                url: base_url + '/' + url + '/' + id,
                success: function (result)
                {
                    console.log(result);
                    if (result.success == true) {
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                        console.log('result ', result)
                        Swal.fire({
                            type: 'success',
                            title: 'Deleted!',
                            text: 'Record is deleted successfully.'
                        })
                    }
                    else {
                        console.log(result);
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: result.data
                        })
                    }
                },
                error: function (err) {
                    console.log('err ', err)
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'This record is conntect with another data!'
                    })
                }
            });
        }
    });
}

