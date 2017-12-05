var express = require('express');
var router = express.Router();

/* GET home page. */
router.get('/', function (req, res, next) {

    var text  = {"code": 200, "products": [{ "name": "Product A", "price": 30, "id": "id_A" }, { "name": "Product B", "price": 50, "id": "id_B" }]};
    res.json(text);
});

router.post('/', function (req, res) {
    var text = {
        "code": 201,
        "message": "created product",
        "products": [
            {
                "d5e89b80-b56e-11e6-adf3-75d0cc9cd069": {
                    "id": "d5e89b80-b56e-11e6-adf3-75d0cc9cd069",
                    "name": "Product C",
                    "price": 100
                }
            }
        ]
    };
    res.send(text)
});

module.exports = router;
