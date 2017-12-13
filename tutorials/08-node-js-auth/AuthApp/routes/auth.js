var express = require('express');
var passport = require('passport');
var LocalStrategy = require('passport-local').Strategy;
var dbHandler = require('../util/DBHandler')();

var router = express.Router();

// TODO ab Folie 31

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

passport.serializeUser(function(user, callback) {
    callback(null, user.id);
});

passport.deserializeUser(function(id, callback) {
    dbHandler.getUserById(id, callback);
});

router.post('/login', function (req, res) {
    res.send("Ok")
});


router.post('/register', passport.authenticate('mmn-register', {
    successRedirect: '/secret',
    failureRedirect: '/register.html'
}));

router.post('/logout', function (req, res) {
    res.send("Ok")
});


module.exports = router;
