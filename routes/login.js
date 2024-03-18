require('dotenv').config();
const mysql = require('mysql');
const express = require('express');
const session = require('express-session');
const path = require('path');

const con = mysql.createConnection({
	host: process.env.DB_HOST,
	user: process.env.DB_USER,
	password: process.env.DB_PASSWORD,
	database: process.env.DB_DATABASE
})
const app = express();

app.use(session({
	secret: 'secret',
	resave: true,
	saveUninitialized: true
}));
app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(express.static(path.join(__dirname, '..', 'public')));

app.get('/', function (req, res) {
	res.sendFile(path.join(__dirname + '/index.html'));

});

app.get('/login', function (req, res) {
	res.sendFile(path.join(__dirname, '..', 'public/login.html'));

});

app.post('/auth', function (req, res) {

	const username = req.body.email;
	const password = req.body.password;
	if (username && password) {
		con.query('SELECT * FROM users WHERE email_address = ? AND password = ?', [username, password], function (error, results, fields) {
			if (error){
				throw error;
			}
			
			if (fields.length > 0) {
				req.session.loggedin = true;
				req.session.username = username;
				res.redirect('/');
			} else {
				res.send('Wrong username or Password');
				res.redirect('/login')
			}
			res.end();
		});
	} else {
		res.send('Please enter Username and Password!');
		res.end();
	}
});




const PORT = 3000;
app.listen(PORT, () => {
	console.log(`Server is running on port ${PORT}`);
});