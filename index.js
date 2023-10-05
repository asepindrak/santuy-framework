angular.module('app', [])
    .controller('AppController', function ($scope) {
        var getUser = localStorage.getItem("user");
        if (getUser) {
            window.location.replace("./pages/dashboard/");
        } else {
            window.location.replace("./pages/login/");
        }
    });