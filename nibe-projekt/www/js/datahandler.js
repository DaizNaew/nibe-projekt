import { createConnection } from 'mysql';

define(dataModules = () => {
    let dbHost="10.11.4.136",dbUser="nibe",dbPass="Masterfatman1337";

    var conn = createConnection({
        host: dbHost,
        user: dbUser,
        password: dbPass
    });
    return {
        dbConn:function(){
            return conn.connect((err) => {
                if (err) throw err;
                console.log("Connected!");
            })
        } 
    }
    
})