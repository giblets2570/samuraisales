// server.js

// modules =================================================
var express        = require('express');
var app            = express();
var bodyParser     = require('body-parser');
var methodOverride = require('method-override');
var mongoose       = require('mongoose');
var AWS 		   = require('aws-sdk');
var nodemailer     = require('nodemailer');
var multer  	   = require('multer');
var fs 			   = require("fs");
// configuration ===========================================
var awsURL = "https://s3-eu-west-1.amazonaws.com/salessamurai/";
var done=false;

// set the view engine to ejs
app.set('view engine', 'ejs');

/*Configure the multer.*/

app.use(multer({ 
	// dest: 'uploads/',
rename: function (fieldname, filename) {
	var re = / /gi;
    return filename.replace(re, "-")+Date.now();
},
onFileUploadStart: function (file) {
  console.log(file.originalname + ' is starting ...')
  // done=false;
},
onFileUploadComplete: function (file) {
  console.log(file.fieldname + ' uploaded to  ' + file.path)
  done=true;
}
}));
// app.use(require('multer')({
// 	dest: 'uploads/',
// 	rename: function (fieldname, filename) {
//     	return filename+Date.now();
// 	},
// 	limits: {
// 		fieldNameSize: 999999999,
// 		fieldSize: 999999999
// 	},
// 	includeEmptyFields: true,
// 	inMemory: true,
// 	onFileUploadStart: function(file) {
// 		console.log('Starting ' + file.fieldname);
// 	},
// 	onFileUploadData: function(file, data) {
// 		console.log('Got a chunk of data!');
// 	},
// 	onFileUploadComplete: function(file) {
// 		console.log('Completed file!');
// 	},
// 	onParseStart: function() {
// 		done=false;
// 		console.log('Starting to parse request!');
// 	},
// 	onParseEnd: function(req, next) {
// 		console.log('Done parsing!');
// 		done=true;
// 		next();
// 	},
// 	onError: function(e, next) {
// 	if (e) {
// 	  	console.log(e.stack);
// 	}
// 		next();
// 	}
// }));

var Signup   = require('./app/models/signup.js');

var s3 = new AWS.S3();
var bucket = process.env.S3_BUCKET;

var transporter = nodemailer.createTransport({
    service: 'Zoho',
    auth: {
        user: 'admin@yakhub.co.uk',
        pass: process.env.ZOHO_PASS
    }
});
console.log('SMTP Configured');

// config files
var db = require('./config/db');

// set our port
var port = process.env.PORT || 8080;

// connect to our mongoDB database
// (uncomment after you enter in your own credentials in config/db.js)
mongoose.connect(db.url);

// get all data/stuff of the body (POST) parameters
// parse application/json
app.use(bodyParser.json());

// parse application/vnd.api+json as json
app.use(bodyParser.json({ type: 'application/vnd.api+json' }));

// parse application/x-www-form-urlencoded
app.use(bodyParser.urlencoded({ extended: true }));

// override with the X-HTTP-Method-Override header in the request. simulate DELETE/PUT
app.use(methodOverride('X-HTTP-Method-Override'));

// set the static files location /public/img will be /img for users
app.use(express.static(__dirname + '/public'));

// routes ==================================================

// require('./app/routes')(app,express); // configure our routes

app.post('/signup', function(req,res){
	// console.log(req.files.CVFile);
	if(done==true){
	    var CV_url = awsURL + req.files.CVFile.name;
	    // console.log(CV_url);
	    // Asynchronous read
		fs.readFile(req.files.CVFile.path, function (err, file_data) {
		   	if (err) {
		       return console.error(err);
		   	}
		   	var params = {
				Bucket: bucket, 
				Key: req.files.CVFile.name, 
				Body: file_data,
				ACL:'public-read'
			};
			s3.putObject(params, function(err, data) {

		      	if (err)
		        	console.log(err)
		      	else
		      		console.log("Successfully uploaded data to myBucket/myKey");
		      	var signup = new Signup();
				signup.name = req.body.inputName;
				signup.email = req.body.inputEmail;
				signup.phone = req.body.inputPhone;
				signup.availability = req.body.inputAvailability;
				signup.months = req.body.inputMonths;
				signup.CV_url = CV_url;
				signup.save(function(err){
					if(err)
						return res.send(err);
					var text = "Signup,\n\n";
	        		text += "Name: " + req.body.inputName + "\n";
	        		text += "Email: " + req.body.inputEmail + "\n";
	        		text += "Phone: " + req.body.inputPhone + "\n";
	        		text += "Availability: " + req.body.inputAvailability + "\n";
	        		text += "Months: " + req.body.inputMonths + "\n";
	        		text += "CV_url: " + CV_url + "\n";
	        		// console.log(text);
					var mailOptions = {
					    from: 'Yak Hub<admin@yakhub.co.uk>', // sender address
					    to: 'tom@yakhub.co.uk, dan@yakhub.co.uk', // list of receivers
					    subject: 'Samurai sales signup', // Subject line
					    text: text, // plaintext body
					    // html: '<b>Hello world ✔</b>' // html body
					};

					// send mail with defined transport object
					transporter.sendMail(mailOptions, function(error, info){
					    if(error){
					        return console.log(error);
					    }
					    console.log('Message sent: ' + info.response);
					    return res.render('../public/index',{'message':'Thanks for signing up!'}); // load our public/index.html file
					});
					// return res.render('../public/index',{'message':'Thanks for signing up!'}); // load our public/index.html file
				});
		   	});
		});
	}

	// (id, name, email, phone, availability ,months, CV_url, timeCreated)
});

app.use(function(req, res) {
    res.render('../public/index',{'message':''}); // load our public/index.html file
});


// start app ===============================================
// startup our app at http://localhost:8080
app.listen(port);

// shoutout to the user
console.log('Magic happens on port ' + port);

// expose app
exports = module.exports = app;

