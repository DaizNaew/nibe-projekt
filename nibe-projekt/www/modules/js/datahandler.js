
    module.exports.dbConn = (mysql) => {
        
        let dbHost="10.11.4.136",dbUser="nibe",dbPass="Masterfatman1337";

        var conn = mysql.createConnection({
            host: dbHost,
            user: dbUser,
            password: dbPass
        });
        conn.connect((err) => {
            if (err) console.log(err);
            console.log("Connected!");
        });
    }  