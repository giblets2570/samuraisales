 // app/routes.js
var Signup = require('./models/signup');
// grab the models we want

    module.exports = function(app,express) {

        // server routes ===========================================================
        // handle things like api call
        // authentication routes

        var router = express.Router();

        // middleware to use for all requests
        router.use(function(req, res, next) {
            // do logging
            console.log('Something is happening.');
            next(); // make sure we go to the next routes and don't stop here
        });

        // REGISTER OUR ROUTES -------------------------------
        // all of our API routes will be prefixed with /api
        app.use('/api', router);

        // frontend routes =========================================================
        // route to handle all angular requests

    };

