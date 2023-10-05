angular.module('app', [])
    .controller('AppController', function ($scope) {
        var app = this;
        app.isLoading = false;
        app.isShow = false;
        app.policy_no = "";
        app.password = "";
        app.error = "";


        app.show = function () {
            app.isShow = !app.isShow;
        };

        app.login = async function () {
            const validate = await app.validate();
            if (validate === true) {
                app.error = "";
                app.isLoading = true;
                const responses = await fetch(`${API}/members/login?policy_no=${app.policy_no}&password=${app.password}`,
                    {
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        method: "GET"
                    });
                const res = await responses.json();
                app.isLoading = false;
                if (Object.keys(res).length > 0) {
                    const datas = Object.values(res);
                    const item = datas[0];
                    localStorage.setItem("user", JSON.stringify(item));
                    $scope.$apply();
                    var url = localStorage.getItem("url");
                    if (url) {
                        localStorage.removeItem("url");
                        window.location.replace(url);
                    } else {
                        window.location.replace("../");
                    }
                } else {
                    app.error = "no polis atau password salah!";
                    $scope.$apply();
                }
            }
        }

        app.validate = async function () {
            if (app.policy_no == "" || app.password == "") {
                return false;
            }

            return true;
        }
    });