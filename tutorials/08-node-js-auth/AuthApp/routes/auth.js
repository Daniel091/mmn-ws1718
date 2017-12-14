var express = require('express');
var passport = require('passport');
var LocalStrategy = require('passport-local').Strategy;
var dbHandler = require('../util/DBHandler')();

var router = express.Router();

passport.use('mmn-register', new LocalStrategy({
    usernameField: 'username',
    passwordField: 'password'
}, function (username, password, callback) {
    console.log("call local stragtegy " + username);

    dbHandler.addUser(
        username,
        password,
        function (err, id) {
            callback(err, {
                    id: id,
                    username: username,
                    password: password
                }
            )
        });

}));

passport.use('mmn-login', new LocalStrategy({
    usernameField: 'username',
    passwordField: 'password'

}, function (username, password, callback) {
    console.log("Trying to login " + username);

    dbHandler.getUserByUsername(username, function (err, user) {
        if (user.password === password) {
            console.log("Password matches");
            callback(null, user);
        } else {
            callback(new Error('wrong credentials'));
        }
    })
}));

// serialize id of user to session, to keep amount of data stored in sessio small
passport.serializeUser(function(user, callback) {
    console.log("Serialize User");
    callback(null, user.id);
});

// id from session is used to get Full User Object from Database
passport.deserializeUser(function(id, callback) {
    console.log("Deserialize User");
    dbHandler.getUserById(id, callback);
});

router.post('/register', passport.authenticate('mmn-register', {
    successRedirect: '/secret',
    failureRedirect: '/register.html'
}));

router.post('/login', passport.authenticate('mmn-login', {
    successRedirect: '/secret',
    failureRedirect: '/index.html'
}));

router.get('/logout', function (req, res) {
    req.logout();
    res.redirect('/index.html');
});


module.exports = router;
