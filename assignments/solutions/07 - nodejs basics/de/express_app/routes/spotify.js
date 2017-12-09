var express = require('express');

// path allows you to easily resolve the relative path of the spotifysearch directory
var path = require('path');

// Init the router
var router = express.Router();


router.use('/', express.static(path.join(__dirname, '../spotifysearch')));
module.exports = router;
