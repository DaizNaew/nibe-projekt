// Dom7
var $$ = Dom7;

// Framework7 App main instance
var app = new Framework7({
  root: '#app', // App root element
  id: 'dk.nibefestival.nibelager', // App bundle ID
  name: 'nibelager', // App name
  theme: 'auto', // Automatic theme detection
  // App root data
  data: function () {

    // Datasæt som er globalt tilgængeligt
    return {
      username: "", // Den nuværende loggede ins brugernavn
      userID: -1, // Den nuværende loggede ins kort ID
      adminConfirmedBool: false, // Om der er logget en admin ind
      navbarheight: 0, // Højden på selve navbaren
      serverip: "http://10.11.5.135/cordova/", // IP addressen til serveren som kører restAPIen
    };
  },
  // App root methods
  methods: {

    /**
     * Denne funktion viser admin navbaren
     */
    showAdminNavbar: function () {
      if (app.data['adminConfirmedBool'] == true) {
        $$('#adminnavbarwrapper').show();
      }
    }, // Slut på showAdminNavbar() funktionen

    /**
     * Denne funktion skjuler admin navbaren
     */
    hideAdminNavbar: function () {
      app.data['adminConfirmedBool'] = false; // Sætter så at systemet ikke ser en admin loggede ind
      $$('#adminnavbarwrapper').hide();
    }, // Slut på hideAdminNavbar() funktionen

    /**
     * Denne funktion tilføjer en log til databasen med user kort ID og hvad der skete
     * @param {int} UserID Dette parameter er brugerens kort ID
     * @param {string} Handling Dette parameter er den handling der blev foretaget i systemet
     */
    addToLog: function (UserID, Handling) {
      // Et POST request til restAPIen på logging endpointet
      app.request.post(`${app.data['serverip']}endpoint/logging.php`, {
        UserID: UserID,
        Handling: Handling
      }, function (response) { // Det der skal ske hvis det lykkedes
        //console.log(response);
      }, function (e, e2) { // Det der skal ske hvis der meldes fejl fra restAPIen
        //console.log(e);
      }, "json");
    }, // Slut på addToLog() funktionen

    /**
     * Denne function søger i realtime i en tabel, hvor der indtastes kriterier i et input felt, som så kigger i et array,
     * Hvis arrayet indeholder noget som matcher kriterierne, laver den en 'style.display="none"' på alt det som ikke matcher,
     * som gør at det bliver skjult for brugeren.
     * @param {string} inputFieldID Dette parameter er det input felt som der bliver skrevet i for at søge i tabellen, 
     *                              hvor selve parametret er ID navnet på input feltet.
     * @param {string} tableID Dette parameter er det table som vi gerne vil søge igennem, hvor selve parametret er ID navnet på tabellen.
     * @param {int} rowCount Dette parameter er den position hvor i tabellen vi gerne vil filtrere resultater med
     */
    searchFunction: function (inputFieldID, tableID, rowCount) {

      // En constant som er den funktion der skal køres
      const search = (field, tableID, rowCount) => {
        //Definer lets som der skal bruges i functionen
        let input, filter, table, tr, td, txtValue;
        //Sætter input variablen til at være vores input field object
        input = field.target;
        //Sætter det som skal bruges til at filtre i databasen til værende selve input feltets value
        filter = input.value.toUpperCase();
        //Sætter Table variablen til at være vores table object som skal søges i
        table = $$(`#${tableID}`);
        //Sætter tr variablen til at være alle de elemener i tabellen som matcher vores kriteria som er tr
        tr = table.find('tr');
        //Start på for loop for at kigge igennem alle tabel rows og finde noget der matcher
        for (i = 0; i < tr.length; i++) {
          //Sætter td til at være det specifikke element i tr som vi vil søge igennem
          td = tr[i].getElementsByTagName("td")[rowCount];
          //Checker om der faktisk er sat noget til td
          if (td) {
            //Finder den text value som er i td
            txtValue = td.textContent || td.innerText;
            //Checker om text valuen passer med noget af det vi søger efter
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
              //Sætter rowets display til ikke at være none, så det faktisk er synligt
              tr[i].style.display = "";
            } else {
              //Ellers sætter vi display til at være none, så det ikke kan ses af brugeren.
              tr[i].style.display = "none";
            }
          }
        }
      }
      
      //Vi bruger dom7 til at vælge vores input felt, og så reagere vi på keyup events via et callback
      $$(`#${inputFieldID}`).keyup(field => {
        // Kald til funktionen men de krævede variabler
        search(field, tableID, rowCount);
      });
      //Vi bruger dom7 til at vælge vores input felt, og så reagere vi på keyup events via et callback
      $$(`#${inputFieldID}`).click(field => {
        // Kald til funktionen men de krævede variabler
        search(field, tableID, rowCount);
      });
    }, //Slut searchFunction() funktion
  },

  // App routes
  // Her defineres routes til at være alle tilgængelige routes der er blevet defineret i routes.js
  routes: routes,

  // Framework7 on component
  // Dette er hvor der kan defineres hvad der skal ske på forskellige events i systemet
  on: {
    // Start på pageInit()
    // Dette er når en side i systemet bliver initialized
    pageInit: function (page) {
      // Dette er for at sikre os at den første side der bliver indlæst af systemet, er udlon siden
      let currentpage = app.views.main.router.currentPageEl.dataset.name;
      if (currentpage == "home") {
        app.views.main.router.navigate("/udlon/", {
          reloadCurrent: true, // Sikrer at der kommer friskt data på siden
        });
        // Dette gør så at navbaren passer til main viewet, for at sikre at der er det rigtige forhold imellem deres højder
        app.data['navbarheight'] = $$('#view-navbar')[0].clientHeight;
        document.getElementById('view-home').style.top = app.data['navbarheight'] + "px";
      }
      
      /**
       * Moment exempel på hvordan det skal bruges
       */
      // only needing core
      define(['moment'], function (moment) {
        console.log(moment().format('LLLL'));
      });

    }, // Slut på pageInit eventet

    // Start på pageAfterIn()
    // Dette er når en side kommer ind i view for brugeren
    pageAfterIn: function (page) {
      setTimeout(function () {
        // Dette gør så at navbaren passer til main viewet, for at sikre at der er det rigtige forhold imellem deres højder
        app.data['navbarheight'] = $$('#view-navbar')[0].clientHeight;
        document.getElementById('view-home').style.top = app.data['navbarheight'] + "px";
      }, 0);
      // En variabel som indeholder en array af alle list items i navbaren som HTMLObjekter
      let navbarheader = $$('#navbarheader').find('li');
      var idleTime = 0; // variabel som indeholder det antal minutter der ikke er sket noget på siden
      // Laver en intern timer som kører hver 60000ms, dvs hvert minut tjekker vi om 
      // der er gået det antal minutter der skal inden der bliver redirected
      setInterval(function () {
        idleTime++; // Incrementer idleTime hvert minut
        // Hvis der er gået længere tid end der er defineret der skal, så køres funktionerne som redirekter brugeren til udlon og logger ud som admin
        if (idleTime > 5) {
          app.methods.hideAdminNavbar(); // Gemmer admin navbaren
          // For hver list item element i headeren som har en active class, de mister den class
          for (let i = 0; i < navbarheader.length; i++) {
            if ($$(navbarheader[i]).hasClass('active')) {
              $$(navbarheader[i]).removeClass('active');
            }
          }
          $$(`#navbarudlon`).addClass('active'); // Sætter udlon som aktiv i navbaren
          // Redirekter tilbage til udlon siden
          app.views.main.router.navigate("/udlon/", {
            reloadCurrent: true, // Sikrer at der kommer friskt data på siden
          });
        }
      }, 60000);

      // Checker om der sker mousemovements eller keypresses
      $$(document).mousemove(function (e) {
        idleTime = 0;
      });

      $$(document).keypress(function (e) {
        idleTime = 0;
      });
    }, // Slut på pageAfterIn eventet
  }, // Slut på on delen af Framework7
});

/**
 * Init/Create views
 */
// Homeview som er det main view component der håndterer det meste af vores data og pages
var homeView = app.views.create('#view-home', {
  url: '/'
});
// Navbarview som er det view component der håndtere selve navbaren og alt hvad den gør
var navbarView = app.views.create('#view-navbar', {
  url: '/navbar/'
});