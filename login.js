const mysql = require('mysql');
const express = require('express');
const session = require('express-session');
const path = require('path');

const con = mysql.createConnection({
    host: 'db.davisaur.me',
    user: 'groupproj',
    password: '*r!%sV$nPZ5@%W%4',
    database: 'groupproj',
    port: '3306'
})
const app = express();

app.use(session({
	secret: 'secret',
	resave: true,
	saveUninitialized: true
}));
app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(express.static(path.join(__dirname, 'static')));


app.get('/', function (req,res){
    res.sendFile(path.join(__dirname + '/login.html'));
})

app.post('/auth', function(req, res) {

	const username = req.body.email;
	const password = req.body.password;
	if (username && password) {
		con.query('SELECT * FROM accounts WHERE username = ? AND password = ?', [username, password], function(error, results, fields) {
			if (results.length > 0) {
				req.session.loggedin = true;
				req.session.username = username;
				res.redirect('/home');
			} else {
				res.send('Wrong username or Password');
			}			
			res.end();
		});
	} else {
		res.send('Please enter Username and Password!');
		res.end();
	}
});


app.listen("3000");