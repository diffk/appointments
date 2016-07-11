/**
 * Base App
 * @constructor
 */
function App() {
}

/**
 * loader
 */
App.prototype.start = function () {

    this.config = {};

    this.initTemplate();
    this.initEvents();
};

App.prototype.initEvents = function () {
    var self = this;

    $('a[href=\"#about\"]').on('click', function () {
        $('#aboutModal').modal();
    });

    $("a[href=\"#record\"]").on('click', function () {
        self.doctorView();
    });

    $("a[href=\"#records\"]").on('click', function () {
        self.recordsView();
    });

};

App.prototype.doctorView = function () {
    var self = this;

    $.loader.open();
    $.get("/api/doctor", function (data) {
        self.rawDoctorData = data;
        self.showDoctorTable(data);
    });
};

App.prototype.showDoctorTable = function (data) {
    var self = this;

    var result = this.template.doctorList({rows: data});
    $('#content-region').html(result);
    $('.table tr.element').on('click', function (e) {
        self.sheduleView(e);
    });
    $.loader.close();
};

App.prototype.sheduleView = function (e) {
    var self = this;
    var id = $(e.currentTarget).data('id');
    this.currentDoctor = $($(e.currentTarget).children()[1]).html();

    $.loader.open();
    $.get("/api/doctor/" + id + "/shedule", function (data) {
        self.rawSheduleData = data;
        self.showShedule(data);
        self.formLoader();
    });
};


App.prototype.showShedule = function (data) {
    var self = this;

    var result = this.template.sheduleList({rows: data, doctor: this.currentDoctor});
    $('#content-region').html(result);
    $('.hovered').on('click', function (e) {
        self.recording(e);
    });
};

App.prototype.fillForm = function (id, day, start) {
    $('#form_id').val(id);
    $('#form_day').val(day);
    $('#form_start').val(start);
};

App.prototype.formLoader = function () {

    $.get("/form", function (data) {
        $("#formhelper").html(data);
        $.loader.close();
    });

};

App.prototype.recording = function (e) {
    var self = this;
    var $el = $(e.currentTarget);
    this.$el = $el;

    var day = $el.parent().data('day');
    var id = $el.parent().parent().data('id');
    var start = $el.html().split('-')[0];

    this.fillForm(id, day, start);

    var dateForm = $('form[name="form"]').serialize();
    $.loader.open();
    $.ajax({
        type: "POST",
        url: "/form",
        data: dateForm
    })
        .done(function (data) {
            if (typeof data.message !== 'undefined') {
                $('#resultMessage').html(data.message);
                $('#noteModal').modal();
            }
            self.$el.removeClass('label-success').removeClass('hovered').addClass('label-default');
            self.formLoader();

        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            if (typeof jqXHR.responseJSON !== 'undefined') {
                if (jqXHR.responseJSON.hasOwnProperty('form')) {
                    $('#form_body').html(jqXHR.responseJSON.form);
                }

                //$('.form_error').html(jqXHR.responseJSON.message);

            } else {
                alert(errorThrown);
            }
            $.loader.close();

        });

};

App.prototype.recordsView = function () {
    console.log('call records View');
};

App.prototype.initTemplate = function () {
    this.template = {};
    this.template.doctorList = _.template($('#doctor-list').html());
    this.template.sheduleList = _.template($('#shedule-list').html());
};

/*
 initial
 */
function Init() {
    App = new App();
    App.start();
}
