angular.module('app', [])
    .controller('AppController', function ($scope) {
        var app = this;
        app.https = HTTPS;
        app.dashboard = DASHBOARD;
        app.openDrawer = false;
        app.openScanner = false;
        // var sidebar = angular.element(document.querySelector('#sidebar'));
        // var camera = angular.element(document.querySelector('#camera'));
        app.user = {};
        app.vouchers = [];
        app.banners = [];
        var getUser = localStorage.getItem("user");
        if (getUser) {
            app.user = JSON.parse(getUser);
        } else {
            window.location.replace("./login/");
        }

        app.showDrawer = function () {
            app.openDrawer = !app.openDrawer;
            if (app.openDrawer) {
                sidebar.removeClass("active").addClass("active");
            } else {
                sidebar.removeClass("active");
            }
            $scope.$apply();
        }

        app.showScanner = function () {
            app.openScanner = !app.openScanner;
            if (app.openScanner) {
                camera.removeClass("hidden")
            } else {
                camera.removeClass("hidden").addClass("hidden");
            }
            $scope.$apply();
        }

        app.getVoucher = async function () {
            app.message = "";
            app.isLoading = true;
            const responses = await fetch(`${API}/vouchers/home`,
                {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    method: "GET",
                });
            const res = await responses.json();
            if (Object.keys(res).length > 0) {
                const datas = res.data;
                app.vouchers = datas;
                app.isLoading = false;
                $scope.$apply();
            } else {
                app.vouchers = [];
                app.isLoading = false;
                $scope.$apply();
            }

        }

        app.getVoucher();

        app.getBanner = async function () {
            app.message = "";
            app.isLoading = true;
            const responses = await fetch(`${API}/banners/all`,
                {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    method: "GET",
                });
            const res = await responses.json();
            if (Object.keys(res).length > 0) {
                const datas = res.data;
                app.banners = datas;
                app.isLoading = false;
                $scope.$apply();
            } else {
                app.banners = [];
                app.isLoading = false;
                $scope.$apply();
            }

        }

        app.getBanner();

        app.logout = function () {
            localStorage.removeItem("user");
            window.location.replace("./login/");
        }
    });