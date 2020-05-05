/**
 * Created by aziz titu2 on 1/5/2016.
 */
var GLOBAL_CONF_MODAL;

function reloadSelects() {
    $('select').material_select();
}

function reloadSelectsInsideSelector(selector) {
    $(selector + ' select').material_select();
}

function reloadAzeeTimePickers(){
    $("select.azee_timepicker").each(function(){
        $(GLOBAL_TIMEPICKER_OPTS).appendTo($(this));
    });
    reloadSelects();
}

function reloadAzeeTimePickersInsideSelector(selector){
    $(selector +" select.azee_timepicker").each(function(){
        $(GLOBAL_TIMEPICKER_OPTS).appendTo($(this));
    });
    reloadSelectsInsideSelector(selector);
}

function reloadCollapsibles() {
    $('.collapsible').collapsible({
        accordion: true  // A setting that changes the collapsible behavior to expandable instead of the default accordion style
    });
}

function reloadCollapsiblesInsideSelector(selector) {
    $(selector + ' .collapsible').collapsible({
        accordion: true // A setting that changes the collapsible behavior to expandable instead of the default accordion style
    });
}

function reloadTooltips() {
    $('.tooltipped').tooltip({delay: 50});
    prepareTooltips();
}

function reloadTooltipsInsideSelector(selector,hideOnElementClick) {
    $(selector + ' .tooltipped').tooltip({delay: 50});
    if(typeof hideOnElementClick!='undefined' && hideOnElementClick==true){
        $(selector + ' .tooltipped').click(function(){
            $('.material-tooltip').hide(300);
        });
    }
    prepareTooltips();
}

function prepareTooltips(){
    $('.material-tooltip').hover(function(){
        $(this).hide(300);
    });
    console.log("preparing tooltips");
}


function reloadLabels() {
    $('label').each(function () {
        $(this).addClass("active");

        if ($(this).siblings("input").val() != "")
            $(this).addClass("active");
        else
            $(this).removeClass("active");
    });
}
function reloadLabelsInsideSelector(selector) {
    $(selector + ' label').each(function () {
        if ($(this).siblings("input").val() != "")
            $(this).addClass("active");
        else
            $(this).removeClass("active");
    });
}


function createAndBindTimePicker(selector, g_var, openCallback, closeCallback) {
    var $timepicker = $(selector).pickatime({

        format: 'mmmm dd, yyyy',
        min: parseDate("1970-01-01"),
        max: parseDate("2050-12-31"),
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 80,
        close:"OK",
        onClose: function () {
            $(document.activeElement).blur();
            /*var dateStr = $("#hired_date").val();
             if (dateStr != "") {
             var date = new Date(dateStr);
             $("#start_year").val(date.getFullYear());
             $("#start_month").val(date.getMonth() + 1);
             $("#start_day").val(date.getDate());
             }*/
            console.log("DatePicker closed");
            closeCallback();
        },
        onOpen: function () {
            //g_var.set('select', $(selector).val());
            openCallback();
            //console.log(parseDate($("#start_year").val()+"-"+$("#start_month").val()+"-"+$("#start_day").val()));
        }
    });

    return $datepicker.pickadate('picker');
}


function createAndBindDatePicker(selector, g_var, openCallback, closeCallback) {
    var $datepicker = $(selector).pickadate({

        format: 'mmmm dd, yyyy',
        min: parseDate("1970-01-01"),
        max: parseDate("2050-12-31"),
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 80,
        close:"OK",
        onClose: function () {
            $(document.activeElement).blur();
            /*var dateStr = $("#hired_date").val();
             if (dateStr != "") {
             var date = new Date(dateStr);
             $("#start_year").val(date.getFullYear());
             $("#start_month").val(date.getMonth() + 1);
             $("#start_day").val(date.getDate());
             }*/
            console.log("DatePicker closed");
            closeCallback();
        },
        onOpen: function () {
            //g_var.set('select', $(selector).val());
            openCallback();
            //console.log(parseDate($("#start_year").val()+"-"+$("#start_month").val()+"-"+$("#start_day").val()));
        }
    });

    return $datepicker.pickadate('picker');
}


//Modals

function showCustomModal(selector, callbacks) {
    $(selector).openModal({
        dismissible: true,
        opacity: .5,
        in_duration: 300,
        out_duration: 200,
        ready: function() {
            blurBG()
            callbacks.onOpen();
        },
        complete: function() {
            unblurBG();
            callbacks.onClose();
        }
    });
}

function hideCustomModal(selector){

}

//Confirmation Modal

function createConfModal(title, content) {
    GLOBAL_CONF_MODAL = {
        canDismiss: true,
        onOpen: function () {
        },
        onClose: function () {
        },
        setPositiveAction: function (s, callback) {
            var pos_btn = $("#confirm_positive");
            pos_btn.html(GLOBAL_CONF_MODAL.s);
            pos_btn.unbind('click').click(callback);
        },
        setOnOpen: function (callback) {
            this.onOpen = callback
        },
        setOnClose: function (callback) {
            this.onClose = callback
        },
        showModal: function () {
            $("#confirmation_modal").openModal({
                dismissible: this.canDismiss,
                opacity: .5,
                in_duration: 300,
                out_duration: 200,
                ready: function () {
                    console.log("Opened Confirmation Modal");
                    blurBG()
                    GLOBAL_CONF_MODAL.onOpen();
                },
                complete: function () {
                    console.log("Closed Confirmation Modal");
                    unblurBG()
                    GLOBAL_CONF_MODAL.onClose();
                }
            });
        },
        hideModal: function () {
            unblurBG();
            $("#confirmation_modal").closeModal();
        }
    };

    var title_h = $("#confirm_title");
    var content_h = $("#confirm_content");

    title_h.html(title);
    content_h.html(content);

    return GLOBAL_CONF_MODAL;
}