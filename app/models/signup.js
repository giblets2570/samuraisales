// app/models/nerd.js
// grab the mongoose module
var mongoose = require('mongoose');

// define our agent model
// module.exports allows us to pass this to other files when it is called

var signupSchema = mongoose.Schema({

	name: String,
	email: String,
	phone: String,
	availability: String,
	months: String,
	CV_url: String,
	timeCreated: {
		type: Date,
		default: Date.now()
	}
});

module.exports = mongoose.model('signup', signupSchema);