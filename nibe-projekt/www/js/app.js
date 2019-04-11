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

    return {
      username: "",
      userID: -1,
      adminConfirmedBool: false,
      navbarheight: 0,
      serverip: "http://10.11.5.135/cordova/",
    };
  },
  // App root methods
  methods: {
    showAdminNavbar: function () {
      if (app.data['adminConfirmedBool'] == true) {
        //document.getElementById('adminnavbarwrapper').style.display = "block";
        $$('#adminnavbarwrapper').show();
      }
    },

    hideAdminNavbar: function () {
      app.data['adminConfirmedBool'] = false;
      //document.getElementById('adminnavbarwrapper').style.display = "none";
      $$('#adminnavbarwrapper').hide();
    },

    addToLog: function (UserID, Handling) {
      app.request.post(`${app.data['serverip']}endpoint/logging.php`, {
        UserID: UserID,
        Handling: Handling
      }, function (response) {
        //console.log(response);
      }, function (e, e2) {
        //console.log(e);
      }, "json");
    },
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
      //Vi bruger dom7 til at vælge vores input felt, og så reagere vi på keyup events via et callback
      $$(`#${inputFieldID}`).keyup(field => {
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
      });
    }, //Slut searchFunction() funktion
  },
  // App routes
  routes: routes,

  on: {
    pageInit: function (page) {
      let currentpage = app.views.main.router.currentPageEl.dataset.name;
      if (currentpage == "home") {
        app.views.main.router.navigate("/udlon/", {
          reloadCurrent: true,
        });
        app.data['navbarheight'] = $$('#view-navbar')[0].clientHeight;
        document.getElementById('view-home').style.top = app.data['navbarheight'] + "px";
      }
    },
    pageAfterIn: function (page) {
      setTimeout(function () {
        app.data['navbarheight'] = $$('#view-navbar')[0].clientHeight;
        document.getElementById('view-home').style.top = app.data['navbarheight'] + "px";
      }, 0);

      let currentpage = app.views.main.router.currentPageEl.dataset.name;
      let navbarheader = $$('#navbarheader').find('li');
      if (currentpage == "udlon") {

      } else {
        var idleTime = 0;
        setInterval(function () {
          idleTime++;
          if (idleTime > 5) {
            app.methods.hideAdminNavbar();
            for (let i = 0; i < navbarheader.length; i++) {
              if ($$(navbarheader[i]).hasClass('active')) {
                $$(navbarheader[i]).removeClass('active');
              }
            }
            $$(`#navbarudlon`).addClass('active');
            app.views.main.router.navigate("/udlon/", {
              reloadCurrent: true,
            });
          }
        }, 60000);


        $$(document).mousemove(function (e) {
          idleTime = 0;
        });

        $$(document).keypress(function (e) {
          idleTime = 0;
        });
      }
    },
  },
});

// Init/Create views
var homeView = app.views.create('#view-home', {
  url: '/'
});
var navbarView = app.views.create('#view-navbar', {
  url: '/navbar/'
});