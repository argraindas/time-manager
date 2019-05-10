    var Ziggy = {
        namedRoutes: {"home":{"uri":"\/","methods":["GET","HEAD"],"domain":null},"dashboard":{"uri":"dashboard","methods":["GET","HEAD"],"domain":null},"categories":{"uri":"categories","methods":["GET","HEAD"],"domain":null},"records":{"uri":"records","methods":["GET","HEAD"],"domain":null},"api.categories":{"uri":"api\/categories","methods":["GET","HEAD"],"domain":null},"api.categories.store":{"uri":"api\/categories","methods":["POST"],"domain":null},"api.categories.destroy":{"uri":"api\/categories\/{category}","methods":["DELETE"],"domain":null},"api.categories.update":{"uri":"api\/categories\/{category}","methods":["PATCH"],"domain":null},"api.cards.store":{"uri":"api\/cards","methods":["POST"],"domain":null},"api.cards.destroy":{"uri":"api\/cards\/{card}","methods":["DELETE"],"domain":null},"api.cards.update":{"uri":"api\/cards\/{card}","methods":["PATCH"],"domain":null},"api.cardParticipants.store":{"uri":"api\/cards\/{card}\/participant","methods":["POST"],"domain":null},"api.cardParticipants.destroy":{"uri":"api\/cards\/{card}\/participant","methods":["DELETE"],"domain":null},"api.tasks.store":{"uri":"api\/tasks","methods":["POST"],"domain":null},"api.tasks.destroy":{"uri":"api\/tasks\/{task}","methods":["DELETE"],"domain":null},"api.tasks.update":{"uri":"api\/tasks\/{task}","methods":["PATCH"],"domain":null},"api.records":{"uri":"api\/records","methods":["GET","HEAD"],"domain":null},"api.records.store":{"uri":"api\/records","methods":["POST"],"domain":null},"api.records.destroy":{"uri":"api\/records\/{record}","methods":["DELETE"],"domain":null},"api.records.update":{"uri":"api\/records\/{record}","methods":["PATCH"],"domain":null}},
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
