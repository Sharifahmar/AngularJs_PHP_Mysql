/**/

courseRemoved = typeof courseRemoved == 'undefined' ? false : courseRemoved;

if (courseRemoved) {
    triggerDialog('Course deleted successfully!', BootstrapDialog.TYPE_SUCCESS, 'course-listing.php');
}

/**/
$('.switch').bootstrapSwitch();

/**/
$('.switch').on('switchChange.bootstrapSwitch', function (e, state) {

    var _this = e.currentTarget;

    var $par = $(_this).parents('.course-spec-holder');

    var $courseSpecs = $(_this).parents('.course-spec-holder').find('.course-more');

    if (state) {

        $courseSpecs.slideDown(function () {

            $par.find('textarea').attr('required', 'required');

            $par.find('.dt-tm').datetimepicker({
                format: 'YYYY-MM-DD HH:mm:ss',
                minDate: new Date(),
                useCurrent: false,
                showClear: true,
                widgetPositioning: {
                    horizontal: 'auto',
                    vertical: 'bottom'
                },
            });

        });

    } else {

        $par.find('.dt-tm').data("DateTimePicker").destroy();
        $par.find('.dt-tm').val('');
        $par.find('.cr-tm').val('');
        $par.find('textarea').removeAttr('required');
        $courseSpecs.slideUp();
    }

});

/**/
$('form.form-course').on('submit', function () {

    buttonState('#saveCourse', 'Please wait', false);

    updateTextArea('id');

    var data = new FormData(this);

    var Course = new request(true);

    Course.defaultError = function (r) {

        return function (jqXHR, s, q, d) {

            buttonState('#saveCourse', 'Save', true);

            var _res = jqXHR.responseJSON;

            triggerDialog(typeof _res == 'undefined' ? 'Something went wrong. Try again later' : _res.error, BootstrapDialog.TYPE_DANGER);
        };
    };

    Course.callback = function (res) {

        buttonState('#saveCourse', 'Save', true);

        if (res.success) {
            return triggerDialog(res.message, BootstrapDialog.TYPE_SUCCESS, 'course-listing.php');
        }
        return triggerDialog(res.message, BootstrapDialog.TYPE_WARNING)
    };

    var cid = $("#cId").val();

    Course.post(cid == 0 ? 'courses/add' : 'courses/update', data, null, true);
});


/**/
/* #################################################################################### */
/* ########################## Course listing page ###################################### */
/* #################################################################################### */

/**/
var removeModule = '<div class="rmv"><span class="fa fa-remove"></span></div>';
var moduleHtml = $('.module').html();
var $moduleTemplate = '<div class="module">' + removeModule + moduleHtml + '</div>';

/**/
var removeFeature = '<div class="rmv"><span class="fa fa-remove"></span></div>';
var featureHtml = $('.feature').html();
var $featureTemplate = '<div class="feature">' + removeModule + featureHtml + '</div>';

/**/
var faqHtml = $('.faq').html();
var $faqTemplate = '<div class="faq">' + removeModule + faqHtml + '</div>';

/**/
var reviewHtml = $('.review').html();
var $reviewTemplate = '<div class="review">' + removeModule + reviewHtml + '</div>';
var $clientReviewTemplate = '<div class="review">' + reviewHtml + '</div>';
/**/
var t = 1;

var currCourse = '';
/**/
function validPreviousModule() {

    $('body').find('.error').html('');
    $module = $('.add-module').prev('.module');
    updateTextArea('id');
    if ($module.find('input').val() && $module.find('textarea').val())
        return true;
    return false;
}

/**/
function validPreviousFeature() {

    $('body').find('.error').html('');

    $module = $('.add-feature').prev('.feature');

    if ($module.find('.fTitle').val())
        return true;
    return false;
}

/**/
function validPreviousFaq() {

    $('body').find('.error').html('');

    $faq = $('.add-faq').prev('.faq');

    if ($faq.find('.fQuestion').val() && $faq.find('.fAnswer').val())
        return true;
    return false;
}

/**/
function validPreviousReview() {

    return($('.review-form')[0].checkValidity());

}

/**/
function addNewModule(e) {

    if (validPreviousModule()) {

        var html = ($.parseHTML($moduleTemplate));
        var thisId = window.btoa('mDesc' + (t++));
        $(html).find('textarea').attr('id', thisId)

        $('.add-module').before(html);
        return setTimeout(attachEditor(thisId), 100);
    }

    $('.add-module button').prev('span').html('Please fill the empty fields to add next module.');
}

/**/
function addNewFeature(e) {

    if (validPreviousFeature()) {

        var html = ($.parseHTML($featureTemplate));

        var thisId = window.btoa('fImage' + (t++));

        var prevId = window.btoa('f_prev' + (t++));

        $(html).find('input[type=file]').attr('id', thisId)

        $(html).find('input[type=file]').attr('data-prev', prevId);

        $(html).find('.f-preview').attr('id', prevId);

        $('.add-feature').before(html);
    }

    $('.add-feature button').prev('span').html('Please fill the empty fields to add next feature.');
}

/**/
function addNewFaq(e) {

    if (validPreviousFaq()) {

        var html = ($.parseHTML($faqTemplate));

        $('.add-faq').before(html);

        return;
    }

    $('.add-faq button').prev('span').html('Please fill the empty fields to add next faq.');
}

/**/
function addNewReview(e) {

    if (validPreviousReview()) {

        var html = ($.parseHTML($reviewTemplate));

        $('.add-review').before(html);

        return;
    }

    $('.add-review button').prev('span').html('Please fill the empty fields to add next review.');
}

/**/
function attachEditor(id) {

    CKEDITOR.replace(id, {
        filebrowserUploadUrl: 'requestHandler.php?action=ckFileUpload',
        filebrowserWindowWidth: '640',
        filebrowserWindowHeight: '480'
    });
}

/**/
function moduleTrayReset() {

    var $modulesExist = $('#modules-modal .module').length > 0 ? true : false;
    if (!$modulesExist) {

        var html = ($.parseHTML('<div class="module">' + moduleHtml + '</div>'));
        var thisId = window.btoa('mDesc' + (t++));
        $(html).find('textarea').attr('id', thisId)

        $('.add-module').before(html);
        return setTimeout(attachEditor(thisId), 100);
    }
}

/**/
function featureTrayReset() {

    var $featuresExist = $('#feature-modal .feature').length > 0 ? true : false;

    if (!$featuresExist) {

        var html = ($.parseHTML('<div class="feature">' + featureHtml + '</div>'));

        var thisId = window.btoa('mDesc' + (t++));

        var thisId = window.btoa('fImage' + (t++));

        var prevId = window.btoa('f_prev' + (t++));

        $(html).find('input[type=file]').attr('id', thisId)

        $(html).find('input[type=file]').attr('data-prev', prevId);

        $(html).find('.f-preview').attr('id', prevId);

        $('.add-feature').before(html);

        return;
    }
}

/**/
function faqTrayReset() {

    var $faqExist = $('#faq-modal .faq').length > 0 ? true : false;

    if (!$faqExist) {

        var html = ($.parseHTML('<div class="faq">' + faqHtml + '</div>'));

        $('.add-faq').before(html);

        return;
    }
}

/**/
function reviewTrayReset() {

    var $reviewExist = $('#review-modal .review').length > 0 ? true : false;

    if (!$reviewExist) {

        var html = ($.parseHTML('<div class="review">' + reviewHtml + '</div>'));

        $('.add-review').before(html);

        return;
    }
}

/**/
$('.switch').bootstrapSwitch();

/**/
$('table').on('click', '.delete', function () {

    var cid = $(this).data('cid');

    BootstrapDialog.confirm({
        title: 'WARNING',
        message: 'Are you sure about this ? This will delete  the course permenantly.',
        type: BootstrapDialog.TYPE_WARNING,
        btnCancelLabel: 'Don\'t Delete!',
        btnOKLabel: ' Delete!',
        btnOKClass: 'btn-danger',
        btnCancelClass: 'btn-success',
        callback: function (result) {
            if (result) {
                window.location.href = 'courses.php?action=deleteCourse&cid=' + cid;
            }
        }
    });
});

/**/
$('.add-module').on('click', addNewModule);

/**/
$('.add-feature').on('click', addNewFeature);

/**/
$('.add-faq').on('click', addNewFaq);

/**/
$('.add-review').on('click', addNewReview);

/**/
$('.module-form').on('submit', function () {

    if (validPreviousModule()) {

        updateTextArea('id');

        var data = new FormData(this);

        var Module = new request(true);

        Module.defaultError = function (r) {

            return function (jqXHR, s, q, d) {

                buttonState('#saveModules', 'Save', true);
                var _res = jqXHR.responseJSON;
                triggerDialog(typeof _res == 'undefined' ? 'Something went wrong. Try again later' : _res.error, BootstrapDialog.TYPE_DANGER);
            };
        };

        Module.callback = function (res) {

            buttonState('#saveModules', 'Save', true);
            if (res.success) {

                $('#modules-modal').modal('hide');
                return triggerDialog(res.message, BootstrapDialog.TYPE_SUCCESS);
            }
            return triggerDialog(res.message, BootstrapDialog.TYPE_WARNING)
        };

        var cid = $("#cid").val();
        Module.post(cid == 0 ? 'courses/addModule' : 'courses/addModule', data, null, true);
        return;
    }

    $('.modal-footer button').prev('span').html('All fields are mandatory.');
    return false;
});

/**/
$('.feature-form').on('submit', function () {

    if (validPreviousFeature()) {

        var data = new FormData(this);

        var Feature = new request(true);

        Feature.defaultError = function (r) {

            return function (jqXHR, s, q, d) {

                buttonState('#saveFeatures', 'Save', true);
                var _res = jqXHR.responseJSON;
                triggerDialog(typeof _res == 'undefined' ? 'Something went wrong. Try again later' : _res.error, BootstrapDialog.TYPE_DANGER);
            };
        };

        Feature.callback = function (res) {

            buttonState('#saveFeatures', 'Save', true);

            if (res.success) {

                $('#feature-modal').modal('hide');

                return triggerDialog(res.message, BootstrapDialog.TYPE_SUCCESS);
            }
            return triggerDialog(res.message, BootstrapDialog.TYPE_WARNING)
        };

        var cid = $("#fcid").val();

        Feature.post(cid == 0 ? 'courses/addFeature' : 'courses/addFeature', data, null, true);

        return;
    }

    $('#feature-modal .modal-footer button').prev('span').html('All fields are mandatory.');
    return false;
});

/**/
$('.faq-form').on('submit', function () {

    if (validPreviousFaq()) {

        var data = new FormData(this);

        var Faq = new request(true);

        Faq.defaultError = function (r) {

            return function (jqXHR, s, q, d) {

                buttonState('#saveFaqs', 'Save', true);

                var _res = jqXHR.responseJSON;

                triggerDialog(typeof _res == 'undefined' ? 'Something went wrong. Try again later' : _res.error, BootstrapDialog.TYPE_DANGER);
            };
        };

        Faq.callback = function (res) {

            buttonState('#saveFaqs', 'Save', true);

            if (res.success) {

                $('#faq-modal').modal('hide');

                return triggerDialog(res.message, BootstrapDialog.TYPE_SUCCESS);
            }
            return triggerDialog(res.message, BootstrapDialog.TYPE_WARNING)
        };

        var cid = $("#qcid").val();

        Faq.post(cid == 0 ? 'courses/addFaq' : 'courses/addFaq', data, null, true);

        return;
    }

    $('#faq-modal .modal-footer button').prev('span').html('All fields are mandatory.');
    return false;
});

/**/
$('.review-form').on('submit', function () {

    if (validPreviousReview()) {

        var rtyp = $('input[name=revType]').val();

        if (rtyp == 'STU') {
            $('.rFor').val(currCourse);
        }

        var data = new FormData(this);

        var Review = new request(true);

        Review.defaultError = function (r) {

            return function (jqXHR, s, q, d) {

                buttonState('#saveReview', 'Save', true);

                var _res = jqXHR.responseJSON;

                triggerDialog(typeof _res == 'undefined' ? 'Something went wrong. Try again later' : _res.error, BootstrapDialog.TYPE_DANGER);
            };
        };

        Review.callback = function (res) {

            buttonState('#saveReview', 'Save', true);

            if (res.success) {

                $('#review-modal').modal('hide');

                return triggerDialog(res.message, BootstrapDialog.TYPE_SUCCESS, rtyp == 'STU' ? '' : 'manage-home.php');
            }
            return triggerDialog(res.message, BootstrapDialog.TYPE_WARNING)
        };

        var cid = $("#qcid").val();

        Review.post(cid == 0 ? 'courses/addReview' : 'courses/addReview', data, null, true);

        return;
    }

    $('#faq-modal .modal-footer button').prev('span').html('All fields are mandatory.');

    return false;
});


/**/
$('table').on('click', '.btn-mod', function () {

    var _this = this;

    var _id = '#' + $(this).attr('id');

    $('#cid').val($(_this).data('cid'));

    buttonState(_id, 'Please wait', true);

    var fetchModules = new request(true);

    fetchModules.defaultError = function () {
        return function () {
            buttonState(_id, 'Course Modules', true);
        }
    }

    var afterGet = function (response) {

        buttonState(_id, 'Course Modules', true);
        var res = [];
        res = response.data;
        /* delete the empty module to prepend the existing ones */
        $('#modules-modal').find('.module').remove();
        if ($.isPlainObject(res)) {

            var modulesDesc = $.parseJSON(res.moduleDesc);
            var modulesTitle = $.parseJSON(res.moduleTitle);
            var courseId = res.courseId;
            /**/
            for (var m = 0; m < modulesDesc.length; m++) {

                var html = ($.parseHTML($moduleTemplate));
                var thisId = window.btoa('mDesc' + (t++));
                $(html).find('textarea').attr('id', thisId);
                $(html).find('textarea').val(modulesDesc[m]);
                $(html).find('input').val(modulesTitle[m]);
                $('.add-module').before(html);
                setTimeout(attachEditor(thisId), 100);
            }

        } else {

            moduleTrayReset();
        }
        /**/
        $('#modules-modal').modal('show');
    }

    fetchModules.get('courses/getModules/' + $(this).data('cid'), {}, afterGet, null);
});

$('table').on('click', '.btn-feat', function () {

    var _this = this;

    var _id = '#' + $(this).attr('id');

    $('#fcid').val($(_this).data('cid'));

    buttonState(_id, 'Please wait', true);

    var fetchFeatures = new request(true);

    fetchFeatures.defaultError = function () {
        return function () {
            buttonState(_id, 'Course Modules', true);
        }
    }

    var afterGet = function (response) {

        buttonState(_id, 'Course Features', true);

        var res = [];
        res = response.data;
        /* delete the empty module to prepend the existing ones */
        $('#feature-modal').find('.feature').remove();

        if ($.isPlainObject(res)) {

            var featTitle = $.parseJSON(res.featureTitle);

            var featImage = $.parseJSON(res.featureImage);
            console.log(res.featureImageAlt);

            var featImageAlt = [];

            if (res.featureImageAlt !== undefined) {
                var featImageAlt = $.parseJSON(res.featureImageAlt);
            }


            var courseId = res.courseId;

            /**/
            for (var m = 0; m < featTitle.length; m++) {

                featImageAlt[m] = featImageAlt.length ? featImageAlt[m] : '';

                var html = ($.parseHTML($featureTemplate));

                var thisId = window.btoa('fImage' + (t++));

                var prevId = window.btoa('f_prev' + (t++));

                $(html).find('input[type=file]').removeAttr('required')

                $(html).find('input[type=file]').attr('id', thisId)

                $(html).find('input[type=file]').attr('data-prev', prevId);

                $(html).find('.f-preview').attr('id', prevId);

                $(html).find('.fTitle').val(featTitle[m]);

                $(html).find('.f-preview').attr('src', featImage[m]);

                $(html).find('.fImagePath').val(featImage[m]);

                $(html).find('.fImageAlt').val(featImageAlt[m]);

                $('.add-feature').before(html);

            }

        } else {

            featureTrayReset();
        }
        /**/
        $('#feature-modal').modal('show');
    }

    fetchFeatures.get('courses/getFeatures/' + $(this).data('cid'), {}, afterGet, null);

});

$('table').on('click', '.btn-faq', function () {

    var _this = this;

    var _id = '#' + $(this).attr('id');

    $('#qcid').val($(_this).data('cid'));

    buttonState(_id, 'Please wait', true);

    var fetchFaqs = new request(true);

    fetchFaqs.defaultError = function () {
        return function () {
            buttonState(_id, 'Course FAQs', true);
        }
    }


    fetchFaqs.defaultError = function () {
        return function () {
            buttonState(_id, 'Course FAQs', true);
        }
    }

    var afterGet = function (response) {

        buttonState(_id, 'Course FAQs', true);
        var res = [];
        res = response.data;
        /* delete the empty module to prepend the existing ones */
        $('#faq-modal').find('.faq').remove();
        if ($.isPlainObject(res)) {

            var fQuestion = $.parseJSON(res.faqQuestion);
            var fAnswer = $.parseJSON(res.faqAnswer);
            var courseId = res.courseId;
            /**/
            for (var m = 0; m < fQuestion.length; m++) {

                var html = ($.parseHTML($faqTemplate));

                $(html).find('input').val(fQuestion[m]);

                $(html).find('textarea').val(fAnswer[m]);

                $('.add-faq').before(html);
            }

        } else {

            faqTrayReset();
        }
        /**/
        $('#faq-modal').modal('show');
    }

    fetchFaqs.get('courses/getFaqs/' + $(this).data('cid'), {}, afterGet, null);

});

$('table').on('click', '.btn-rev', function () {

    var _this = this;

    var _id = '#' + $(this).attr('id');

    currCourse = $(this).data('course');

    $('#rcid').val($(_this).data('cid'));

    buttonState(_id, 'Please wait', true);

    var fetchFaqs = new request(true);

    fetchFaqs.defaultError = function () {
        return function () {
            buttonState(_id, 'Course Review', true);
        }
    }


    fetchFaqs.defaultError = function () {
        return function () {
            buttonState(_id, 'Course Review', true);
        }
    }


    var afterGet = function (response) {

        buttonState(_id, 'Course Review', true);
        var res = [];
        res = response.data;
        /* delete the empty module to prepend the existing ones */
        $('#review-modal').find('.review').remove();

        if ($.isPlainObject(res)) {

            var rName = $.parseJSON(res.reviewName);
            var rContent = $.parseJSON(res.reviewContent);
            var rFor = $.parseJSON(res.reviewFor);
            var rImage = $.parseJSON(res.reviewImage);
            var rImageAlt = $.parseJSON(res.reviewImageAlt);
            var rRating = $.parseJSON(res.reviewRating);
            var rType = res.reviewType;
            var courseId = res.courseId;

            /**/

            $("#rcid").val(courseId);

            for (var m = 0; m < rName.length; m++) {

                var html = ($.parseHTML($reviewTemplate));

                $(html).find('.rFor').val(rFor[m]);

                $(html).find('.rContent').val(br2nl(rContent[m]));

                $(html).find('.rName').val(rName[m]);

                $(html).find('.rImagePath').val(rImage[m]);

                $(html).find('.rImage').removeAttr('required');

                $(html).find('.r-preview').attr('src', rImage[m]);

                $(html).find('.rImageAlt').val(rImageAlt[m]);

                $(html).find('.rRating').val(rRating[m]);

                $('.add-review').before(html);
            }

        } else {

            reviewTrayReset();
        }
        /**/
        $('#review-modal').modal('show');
    }

    fetchFaqs.get('courses/getReviews/' + $(this).data('cid'), {}, afterGet, null);

});

/**/
$('#modules-modal').on('click', '.fa-remove', function () {

    $module = $(this).parents('.module');
    BootstrapDialog.confirm({
        title: 'WARNING',
        message: '<h3> Are you sure about this ? </h3>',
        type: BootstrapDialog.TYPE_WARNING,
        btnCancelLabel: 'Cancel Action',
        btnOKLabel: 'Remove',
        btnOKClass: 'btn-danger',
        btnCancelClass: 'btn-success',
        callback: function (result) {
            if (result) {

                triggerDialog('Changes will update once you click Update.', BootstrapDialog.TYPE_WARNING);
                $module.fadeOut('slow', function () {

                    $module.remove();
                    return setTimeout(moduleTrayReset, 100);
                });
            }
        }
    });
});

$('#feature-modal').on('click', '.fa-remove', function () {

    $feature = $(this).parents('.feature');

    BootstrapDialog.confirm({
        title: 'WARNING',
        message: '<h3> Are you sure about this ? </h3>',
        type: BootstrapDialog.TYPE_WARNING,
        btnCancelLabel: 'Cancel Action',
        btnOKLabel: 'Remove',
        btnOKClass: 'btn-danger',
        btnCancelClass: 'btn-success',
        callback: function (result) {
            if (result) {

                triggerDialog('Changes will update once you click Update.', BootstrapDialog.TYPE_WARNING);
                $feature.fadeOut('slow', function () {

                    $feature.remove();
                    return setTimeout(featureTrayReset, 100);
                });
            }
        }
    });
});

$('#faq-modal').on('click', '.fa-remove', function () {

    $faq = $(this).parents('.faq');

    BootstrapDialog.confirm({
        title: 'WARNING',
        message: '<h3> Are you sure about this ? </h3>',
        type: BootstrapDialog.TYPE_WARNING,
        btnCancelLabel: 'Cancel Action',
        btnOKLabel: 'Remove',
        btnOKClass: 'btn-danger',
        btnCancelClass: 'btn-success',
        callback: function (result) {
            if (result) {

                triggerDialog('Changes will update once you click Update.', BootstrapDialog.TYPE_WARNING);

                $faq.fadeOut('slow', function () {
                    $faq.remove();
                    return setTimeout(faqTrayReset, 100);
                });
            }
        }
    });
});

$('#review-modal').on('click', '.fa-remove', function () {

    $review = $(this).parents('.review');

    BootstrapDialog.confirm({
        title: 'WARNING',
        message: '<h3> Are you sure about this ? </h3>',
        type: BootstrapDialog.TYPE_WARNING,
        btnCancelLabel: 'Cancel Action',
        btnOKLabel: 'Remove',
        btnOKClass: 'btn-danger',
        btnCancelClass: 'btn-success',
        callback: function (result) {
            if (result) {

                triggerDialog('Changes will update once you click Update.', BootstrapDialog.TYPE_WARNING);

                $review.fadeOut('slow', function () {
                    $review.remove();
                    return setTimeout(reviewTrayReset, 100);
                });
            }
        }
    });
});




function youtube_parser(url) {
    var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
    var match = url.match(regExp);
    if (match && match[7].length == 11) {
        return match[7];
    } else {
        $('.err').html('<span class="error">Please enter valid video URL</span>');
        $('#video_url').focus();
        return false;
    }
}