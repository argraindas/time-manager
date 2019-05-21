let user = window.App.user;

module.exports = {
    isCreator (model, prop = 'id') {
        return parseInt(model[prop]) === user.id;
    },

    isAdmin () {
        return user.isAdmin;
    }
};
