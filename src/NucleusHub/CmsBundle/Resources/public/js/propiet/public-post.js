var publicPost = function () {

    function init() {
        initialize();
    }

    function initialize() {
        var $agm = $('#agm'),
            $inputName = $('#name'),
            $inputEmail = $('#email'),
            $inputTel = $('#phone'),
            $inputMessage = $('#message'),
            $blockAgency = $('#agency-block'),
            $blockName = $('#name-block'),
            $submit = $('#btn-submit'),
            $form = $("#form-contact");

        var parsley_config = {
            successClass: 'has-success',
            errorClass: 'has-error',
            classHandler: function (el) {
                return $(el.$element).closest('.form-group');
            },
            errorsWrapper: '<span class=\"help-block\"></span>',
            errorElem: '<span></span>'
        }

        if ($('#form-contact').length > 0) {
            var $formParsley = $form.parsley(parsley_config);
            $('#btn-submit').on('click', function (e) {

                var isValid = $formParsley.validate();
                if (isValid) {
                    var $data = $("form#form-contact").serializeArray();
                    var jqxhr = $.post("/api/v1/send/message/agent", { form: $data }, function (data) {
                        $('.alert-success').removeClass('hide');
                        $('#form-contact').reset();
                    })
                        .fail(function () {
                            $('.alert-danger').removeClass('hide');
                        });

                }
                e.preventDefault();
                return false;
            });
        }
    }

    function formDataParse(form) {
        var form = JSON.parse(form);
        $generalFeatures = $('#generalFeatures');
        features = "";

        $.each(form, function (key, value) {
            $.each(value, function (key, value) {
                $.each(value, function (key, value) {
                    var isSelect = $(value.field).is('select');
                    if (isSelect) {
                        var text = $(value.field).children(":selected").text();
                        features += '<li>' + value.label + ':' + text + '</li>';
                    } else {
                        console.log(value.field);
                        if (value.label == "Antiguedad" && value.value == 0)
                            var text = 'A Estrenar';
                        else {
                            var text = $(value.field).val() ? $(value.field).val() : 'No Especificado';
                        }
                        features += '<li>' + value.label + ':' + text + '</li>';
                    }
                });
            });
        });
        $generalFeatures.append(features);
    }


    return {
        init: init,
        formDataParse: formDataParse
    };

}();