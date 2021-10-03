$(function($) {
     $(document).ready( function () {
        // data for the sparklines that appear below header area
        let sparklineData = [47, 45, 54, 38, 56, 24, 65, 31, 37, 39, 62, 51, 35, 41, 35, 27, 93, 53, 61, 27, 54, 43, 19, 46];

        // the default colorPalette for this dashboard
        //var colorPalette = ['#01BFD6', '#5564BE', '#F7A600', '#EDCD24', '#F74F58'];
        let colorPalette = ['#00D8B6', '#008FFB', '#FEB019', '#FF4560', '#775DD0']
        var locale = [
            {
                "name": "fr",
                "options": {
                  "months": [
                    "janvier",
                    "février",
                    "mars",
                    "avril",
                    "mai",
                    "juin",
                    "juillet",
                    "août",
                    "septembre",
                    "octobre",
                    "novembre",
                    "décembre"
                  ],
                  "shortMonths": [
                    "janv.",
                    "févr.",
                    "mars",
                    "avr.",
                    "mai",
                    "juin",
                    "juill.",
                    "août",
                    "sept.",
                    "oct.",
                    "nov.",
                    "déc."
                  ],
                  "days": [
                    "dimanche",
                    "lundi",
                    "mardi",
                    "mercredi",
                    "jeudi",
                    "vendredi",
                    "samedi"
                  ],
                  "shortDays": ["dim.", "lun.", "mar.", "mer.", "jeu.", "ven.", "sam."],
                  "toolbar": {
                    "exportToSVG": "Télécharger au format SVG",
                    "exportToPNG": "Télécharger au format PNG",
                    "exportToCSV": "Télécharger au format CSV",
                    "menu": "Menu",
                    "selection": "Sélection",
                    "selectionZoom": "Sélection et zoom",
                    "zoomIn": "Zoomer",
                    "zoomOut": "Dézoomer",
                    "pan": "Navigation",
                    "reset": "Réinitialiser le zoom"
                  }
                }
            }
              
        ];   
        let spark1 = {
            chart: {
                id: 'sparkline1',
                type: 'area',
                height: 50,
                sparkline: {
                    enabled: true
                },
                locales:locale,
                defaultLocale:'fr'
            },
            stroke: {
                curve: 'smooth'
            },
            fill: {
                opacity: 1,
            },
            tooltip:{
                theme: 'light',
                show:true,
                x:{
                    format:"dd/MM/yyyy"
                }
            },
            series: [{
                name: 'Complaints',
                data: sparklineData
            }],
            labels: [...Array(24).keys()].map(n => `2018-09-0${n+1}`),
            yaxis: {
                min: 0
            },
            xaxis: {
                type: 'datetime',
            },
            colors: ['#aed6dc'],
        }

        let spark2 = {
            chart: {
                id: 'sparkline2',
                type: 'area',
                height: 50,
                sparkline: {
                    enabled: true
                },
                locales:locale,
                defaultLocale:'fr'
            },
            stroke: {
                curve: 'smooth'
            },
            fill: {
                opacity: 1,
            },
            tooltip:{
                theme: 'light',
                show:true,
                x:{
                    format:"dd/MM/yyyy"
                }
            },
            series: [{
                name: 'Randez-vous',
                data: sparklineData
            }],
            labels: [...Array(24).keys()].map(n => `2018-09-0${n+1}`),
            yaxis: {
                min: 0
            },
            xaxis: {
                type: 'datetime',
            },
            colors: ['#ff9a8d'],
        }

        let spark3 = {
            chart: {
                id: 'sparkline3',
                type: 'area',
                height: 50,
                sparkline: {
                    enabled: true
                },
                locales:locale,
                defaultLocale:'fr'
            },
            stroke: {
                curve: 'smooth'
            },
            fill: {
                opacity: 1,
            },
            tooltip:{
                theme: 'light',
                show:true,
                x:{
                    format:"dd/MM/yyyy"
                }
            },
            series: [{
                name: 'Dispositifs',
                data: sparklineData
            }],
            labels: [...Array(24).keys()].map(n => `2018-09-0${n+1}`),
            xaxis: {
                type: 'datetime',
            },
            yaxis: {
                min: 0
            },
            colors: ['#e75874'],
        }

        let spark4 = {
            chart: {
                id: 'sparkline4',
                type: 'area',
                height: 50,
                sparkline: {
                    enabled: true
                },
                locales:locale,
                defaultLocale:'fr'
            },
            stroke: {
                curve: 'smooth'
            },
            fill: {
                opacity: 1,
            },

            tooltip:{
                theme: 'light',
                show:true,
                x:{
                    format:"dd/MM/yyyy"
                }
            },
            series: [{
                name: 'Users',
                data: sparklineData
            }],
            labels: [...Array(24).keys()].map(n => `2018-09-0${n+1}`),
            xaxis: {
                type: 'datetime',
            },
            yaxis: {
                min: 0
            },
            colors: ['#008FFB'],
        }

         let spark4_line = new ApexCharts(document.querySelector("#spark4"), spark4);
        spark4_line.render();
        let spark1Chart = new ApexCharts(document.querySelector("#spark1"), spark1);
        spark1Chart.render();
        let spark2Chart = new ApexCharts(document.querySelector("#spark2"), spark2);
        spark2Chart.render();
        let spark3Chart = new ApexCharts(document.querySelector("#spark3"), spark3)
        spark3Chart.render();


        fetch("/dashboard/stats")
            .then(res => res.json())
            .then(time_series_Data => {
                console.log(time_series_Data['patients']);
                spark4_line.updateSeries([{
                    name: 'Users',
                    data: time_series_Data['patients']
                }])
                spark1Chart.updateSeries([{
                    name:'Réclamation',
                    data: time_series_Data['complaints']
                }])
                spark2Chart.updateSeries([{
                    name:'Randez-vous',
                    data: time_series_Data['meeting']
                }])

                spark3Chart.updateSeries([{
                    name:'Dispositifs',
                    data: time_series_Data['sensors']
                }])
        });
    })
})