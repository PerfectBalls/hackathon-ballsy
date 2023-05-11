<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel=stylesheet href="\assets\css\styles.css">
    <title>API</title>
</head>

<body>

    <div id="results"></div>



    <script>
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                console.log("La géolocalisation n'est pas supportée par ce navigateur.");
            }
        }

        function showPosition(position) {
            let lat = position.coords.latitude;
            let lon = position.coords.longitude;
            fetchData(lat, lon);
        }

        function fetchData(lat, lon, start = 0) {
            const rows = 50;
            const meters = 5000;
            const url = `https://equipements.sports.gouv.fr/api/records/1.0/search/?dataset=data-es&q=&rows=${rows}&start=${start}&facet=famille&facet=caract24&facet=caract25&facet=caract74&facet=caract33&facet=caract156&facet=caract159&facet=caract167&facet=caract168&facet=caract169&facet=atlas&facet=nom_region&facet=nom_dept&refine.famille=Boulodrome&geofilter.distance=${lat},${lon},${meters}`;

            fetch(url)
                .then((response) => response.json())
                .then((data) => {
                    displayData(data);

                    // Si nous avons encore des résultats, continuez à les récupérer en utilisant la pagination
                    if (data.nhits > start + rows) {
                        fetchData(lat, lon, start + rows);
                    }
                });
        }


        function displayData(data) {
            const records = data.records;
            const resultsDiv = document.getElementById("results");

            records.forEach((record) => {
                const name = record.fields.nominstallation;
                const address = record.fields.adresse;
                const zip = record.fields.codeinsee;
                const commune = record.fields.commune;
                const coordgpsy = record.fields.coordgpsy;
                const coordgpsx = record.fields.coordgpsx;
                const listItem = document.createElement("p");
                listItem.textContent = `${name} - ${address} - ${zip} - ${commune} - ${coordgpsy} - ${coordgpsx}`;
                resultsDiv.appendChild(listItem);
            });
        }
        getLocation();
    </script>
</body>

</html>