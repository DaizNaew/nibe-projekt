// For any third party dependencies, like jQuery, place them in the lib folder.

// Configure loading modules from the lib directory,
// except for 'app' ones, which are in a sibling
// directory.
requirejs.config({
    baseUrl: 'modules',
    paths: {
        app: '../js',
        framework7: '../framework7',
        mysql: '../node_modules/mysql'
    },
    // Extended Node modules being loaded
    packages: [{
        // Moment is a module in which to manipulate datetimes
        name: 'moment',
        location: '../node_modules/moment',
        main: 'moment'
    }]
});

// Start loading the main app file. Put all of
// your application logic in there.
requirejs(['js/app.js']);