angular.module("auth.service", [])

    .factory("AuthService", AuthService)


    ;




function AuthService($http, $q, helperServices) {

    var service = {};

    return {
        login: login,
        // logOff: logoff,
        // userIsLogin: userIsLogin,
        // getUserName: getUserName,
        // userIsLogin: userIsLogin,
        // userInRole: userInRole,
        getHeader: getHeader,
        // getToken: getToken,
        // getUserId: getUserId
    }

    function login(user) {
        var def = $q.defer();
        var a = helperServices.url;
        var b = getHeader();
        $http({
            method: 'POST',
            url: helperServices.url + "/auth/login",
            data: user,
            headers: {
                'Content-Type': 'application/json'
            }
        }).then(res => {
            var user = res.data;
            def.resolve(user);
        }, err => {
            def.reject(err);
            message.error(err);
        });
        return def.promise;
    }





    function getHeader() {

        try {
            if (userIsLogin()) {
                return {
                    'Content-Type': 'application/json'
                }
            }
            throw new Error("Not Found Token");
        } catch {
            return {
                'Content-Type': 'application/json'
            }
        }
    }

    // function logoff() {
    //     StorageService.clear();
    //     $state.go("login");

    // }

    // function getUserName() {
    //     if (userIsLogin) {
    //         var result = StorageService.getObject("user");
    //         return result.Username;
    //     }
    // }

    // function getToken() {
    //     if (userIsLogin) {
    //         var result = StorageService.getObject("user");
    //         return result.token;
    //     }
    // }
    // function getUserId() {
    //     if (userIsLogin) {
    //         var result = StorageService.getObject("user");
    //         return result.id;
    //     }
    // }

    // function userIsLogin() {
    //     var def = $q.defer();
    //     $http({
    //         method: 'GET',
    //         url: helperServices.url + "/auth/checklogin",
    //         headers: {
    //             'Content-Type': 'application/json'
    //         }
    //     }).then(res => {
    //         def.resolve(res.data);
    //     }, err => {
    //         def.reject(err);
    //         document.location.href = helperServices.url;
    //     });
    //     return def.promise;
    // }

    // function userInRole(role) {
    //     var result = StorageService.getItem("user");
    //     if (result && result.roles.find(x => x.name = role)) {

    //         return true;
    //     }
    // }
}