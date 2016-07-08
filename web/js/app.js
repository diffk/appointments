

/**
 * Base App
 * @constructor
 */
function App() {}

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
    console.log('call initEvents');

};


App.prototype.initTemplate = function () {
    this.template = {};
};


/*
initial
 */
function Init() {
    App = new App();
    App.start();
}
