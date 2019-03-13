    var Ziggy = {
        namedRoutes: {"home":{"uri":"\/","methods":["GET","HEAD"],"domain":null},"dashboard":{"uri":"dashboard","methods":["GET","HEAD"],"domain":null},"records":{"uri":"records","methods":["GET","HEAD"],"domain":null},"records.store":{"uri":"records","methods":["POST"],"domain":null},"categories":{"uri":"categories","methods":["GET","HEAD"],"domain":null},"categories.store":{"uri":"categories","methods":["POST"],"domain":null},"api.categories":{"uri":"api\/categories","methods":["GET","HEAD"],"domain":null}},
        baseUrl: 'http://project.time-manager/',
        baseProtocol: 'http',
        baseDomain: 'project.time-manager',
        basePort: false,
        defaultParameters: []
    };

    if (typeof window.Ziggy !== 'undefined') {
        for (var name in window.Ziggy.namedRoutes) {
            Ziggy.namedRoutes[name] = window.Ziggy.namedRoutes[name];
        }
    }

    export {
        Ziggy
    }
