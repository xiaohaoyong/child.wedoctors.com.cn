var FormWizard = function () {
    return {
        init: function () {
            if (!jQuery().bootstrapWizard) {
                return;
            }
            var form = $('#fileupload');
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);
            form.validate({
                doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
                errorElement: 'div', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: true, //
                validClass: 'Article[content]',
                 //ignore:,
                rules: {
                    'Article[title]': {
                        required: true,
                        minlength: 2,
                        maxlength: 30
                    },
                    'Article[mediaid]': {
                        required: true
                    },

                    'Article[catid]': {
                        required: true
                    },
                    'Article[tag]': {
                        required: true
                    },
                    cont: {
                        required: {
                            depends:function(){
                                return ($("#article-content").val()=='');
                            }
                        }
                    }
                },
                messages: {
                    'Article[title]': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    },
                    'Article[catid]': {
                        required: '文章分类为必选'
                    },
                    'Article[mediaid]': {
                        required: '媒体号为必选'
                    },
                    'Article[tag]': {
                        required: '文章标签为必填'
                    },
                    cont: {
                        required: '文章内容为必填'
                    }
                },
                errorPlacement: function (error, element) { // render error placement for each input type
                    if (element.parent(".input-group").size() > 0) {
                        error.insertAfter(element.parent(".input-group"));
                    } else if (element.attr("data-error-container")) {
                        error.appendTo(element.attr("data-error-container"));
                    } else if (element.parents('.radio-list').size() > 0) {
                        error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                    } else if (element.parents('.radio-inline').size() > 0) {
                        error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                    } else if (element.parents('.checkbox-list').size() > 0) {
                        error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                    } else if (element.parents('.checkbox-inline').size() > 0) {
                        error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                    } else {
                        error.insertAfter(element); // for other inputs, just perform default behavior
                    }
                },

                //验证
                invalidHandler: function (event, validator) { //display error alert on form submit
                    success.hide();
                    error.show();
                    Metronic.scrollTo(error, -200);
                },

                highlight: function (element) { // hightlight error inputs
                    $(element)
                        .closest('.form-group').removeClass('has-success').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    if (label.attr("for") == "gender" || label.attr("for") == "payment[]") { // for checkboxes and radio buttons, no need to show OK icon
                        label
                            .closest('.form-group').removeClass('has-error').addClass('has-success');
                        label.remove(); // remove error label here
                    } else { // display success icon for other inputs
                        label
                            .addClass('valid') // mark the current input as valid and display OK icon
                            .closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
                    }
                },

                submitHandler: function (form) {
                    // success.show();
                    error.hide();
                    form.submit();
                    //add here some ajax code to submit your form or just call form.submit() if you want to submit the form without ajax
                }

            });


            var displayConfirm = function () {
                $('.tabs .form-control-static', form).each(function () {
                    var input = $('[name="' + $(this).attr("data-display") + '"]', form);
                    var length=$('#new_imgage  tr:eq(0) th').length;
                    if(length) {
                        html = '<table ><tr>';
                        for (var i = 1; i < length + 1; i++) {
                            img = $('#img_src' + i + '> img').attr('src');
                            des = $('#description' + i).val();
                            html += "<th><div style='width:100px;height:80px'><img  style='width:80px;height:60px' src=" + img + "></div><th>";
                        }
                        html += '</table><tr>';
                        $('#image-Article').html(html);
                    }
                    if (input.is(":radio")) {
                        input = $('[name="' + $(this).attr("data-display") + '"]:checked', form);
                    }
                    if (input.is(":text") || input.is("textarea")) {
                        $(this).html(input.val());
                    } else if (input.is("select")) {
                        $(this).html(input.find('option:selected').text());
                    } else if (input.is(":radio") && input.is(":checked")) {
                        $(this).html(input.attr("data-title"));
                    } else if ($(this).attr("data-display") == 'payment[]') {
                        var payment = [];
                        $('[name="payment[]"]:checked', form).each(function () {
                            payment.push($(this).attr('data-title'));
                        });
                        $(this).html(payment.join("<br>"));
                    }
                });
            }


            var handleTitle = function (tab, navigation, index) {
                var total = navigation.find('li').length;
                var current = index + 1;
                $('.step-title', $('#form_wizard_1')).text('Step ' + (index + 1) + ' of ' + total);
                // set wizard title
                // set done steps
                jQuery('li', $('#form_wizard_1')).removeClass("done");
                var li_list = navigation.find('li');
                for (var i = 0; i < index; i++) {
                    jQuery(li_list[i]).addClass("done");
                }
                if (current == 1) {
                    $('#form_wizard_1').find('.button-previous').hide();
                } else {
                    $('#form_wizard_1').find('.button-previous').show();
                }

                if (current >= total) {
                    $('#form_wizard_1').find('.button-next').hide();
                    $('#form_wizard_1').find('#next').hide();
                    $('#form_wizard_1').find('.button-submit').show();
                    displayConfirm();
                } else {
                    $('#form_wizard_1').find('.button-next').show();
                    $('#form_wizard_1').find('#next').show();
                    $('#form_wizard_1').find('.button-submit').hide();
                }
                Metronic.scrollTo($('.page-title'));
            }

            // default form wizard
            $('#form_wizard_1').bootstrapWizard({
                'nextSelector': '.button-next',
                'previousSelector': '.button-previous',
                onTabClick: function (tab, navigation, index, clickedIndex) {

                    return false;
                   // success.hide();
                    error.hide();
                    if (form.valid() == false) {
                        return false;
                    }
                    handleTitle(tab, navigation, clickedIndex);
                },
                onNext: function (tab, navigation, index) {
                    success.hide();
                    error.hide();

                    if (form.valid() == false) {
                        return false;
                    }

                    handleTitle(tab, navigation, index);
                },
                onPrevious: function (tab, navigation, index) {
                    success.hide();
                    error.hide();

                    handleTitle(tab, navigation, index);
                },
                onTabShow: function (tab, navigation, index) {
                    if (index == 3) {
                        displayConfirm();
                        handleTitle(tab, navigation, index);
                    }
                    var total = navigation.find('li').length;
                    var current = index + 1;
                    var $percent = (current / total) * 100;
                    $('#form_wizard_1').find('.progress-bar').css({
                        width: $percent + '%'
                    });
                }
            });
            $('#form_wizard_1').find('.button-previous').hide();
            $('#form_wizard_1 .button-submit').hide();
            //apply validation on select2 dropdown value change, this only needed for chosen dropdown integration.
            $('#country_list', form).change(function () {
                form.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
            });
        }
    };
}();