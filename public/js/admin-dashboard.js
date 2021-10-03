$(function($) {
    $(document).ready(async function() {
        // data for the sparklines that appear below header area
        var sparklineData = [47, 45, 54, 38, 56, 24, 65, 31, 37, 39, 62, 51, 35, 41, 35, 27, 93, 53, 61, 27, 54, 43, 19, 46];
        var locale = [{
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
          }];
        var spark1 = {
            chart: {
                id: 'sparkline1',
                type: 'area',
                height: 50,
                sparkline: {
                    enabled: true
                },
                locales:locale,
                defaultLocale: 'fr',
            },
            stroke: {
                curve: 'smooth'
            },

            tooltip:{
                theme: 'light',
                show:true,
                x:{
                    format:"dd/MM/yyyy"
                }
            },
            fill: {
                opacity: 1,
            },
            series: [{
                name: 'Réclamation',
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

        var spark2 = {
            chart: {
                id: 'sparkline2',
                type: 'area',
                height: 50,
                sparkline: {
                    enabled: true
                },
                locales:locale,
                defaultLocale: 'fr',
            },
            stroke: {
                curve: 'smooth'
            },

            tooltip:{
                theme: 'light',
                show:true,
                x:{
                    format:"dd/MM/yyyy"
                }
            },
            fill: {
                opacity: 1,
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

        var spark3 = {
            chart: {
                id: 'sparkline3',
                type: 'area',
                height: 50,
                sparkline: {
                    enabled: true
                },
                locales:locale,
                defaultLocale: 'fr',
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

        var spark4Opt = {
            chart: {
                id: 'sparkline4',
                type: 'area',
                height: 50,
                sparkline: {
                    enabled: true
                },
                locales:locale,
                defaultLocale: 'fr',
            },

            tooltip:{
                theme: 'light',
                show:true,
                x:{
                    format:"dd/MM/yyyy"
                }
            },
            stroke: {
                curve: 'smooth'
            },
            fill: {
                opacity: 0.8,
            },
            series: [{
                name: 'Utilisateur',
                data: []
            }],
            xaxis: {
                type: 'datetime',
            },
            yaxis: {
                min: 0
            },
            colors: ['#008FFB'],
        }


        const optionDonut = {
            chart: {
                type: 'donut',
                width: '100%',
                height: 300,
                foreColor: '#777',
                locales:locale,
                defaultLocale: 'fr',
            },
            dataLabels: {
                enabled: false,
            },
            plotOptions: {
                pie: {
                    customScale: 1,
                    donut: {
                        size: '80%',
                    },
                    offsetY: 20,
                },
                stroke: {
                    colors: undefined
                }
            },
            colors: ['#e75874','#008FFB'],
            title: {
                text: "Genre Utilisateurs",
                style: {
                    fontSize: '18px'
                }
            },
            series: [],
            labels: [],
            legend: {
                show: true,
                horizontalAlign: 'center',
                fontSize: '14px',
                position: 'bottom',
                containerMargin: {
                    right: 0
                }
            },
        }

        const optionsBar = {
            chart: {
                type: 'bar',
                height: 300,
                width: '100%',
                locales:locale,
                defaultLocale: 'fr',
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    },
                    columnWidth: '5%',
                    endingShape: 'rounded'
                }
            },
            colors: ['#008FFB'],
            series: [{
                name: "Utilisateurs",
                data: []
            }],
            tooltip:{
                theme: 'light',
                show:true,
                x:{
                    format:"dd/MM/yyyy"
                }
            },
            xaxis: {
                axisBorder: {
                    show: true
                },
                axisTicks: {
                    show: true
                },
                crosshairs: {
                    show: true
                },
                labels: {
                    show: true,
                    style: {
                        fontSize: '14px'
                    }
                },
            },
            grid: {
                xaxis: {
                    lines: {
                        show: false
                    },
                },
                yaxis: {
                    lines: {
                        show: false
                    },
                }
            },
            yaxis: {
                axisBorder: {
                    show: false
                },
                labels: {
                    show: false
                },
            },
            xaxis:{
                type: 'datetime',
                format:"dd/MM/yyyy"
            },
            legend: {
                floating: true,
                position: 'top',
                horizontalAlign: 'center',
                offsetY: 0
            },
            title: {
                text: "Nombre d'utilisateur",
                align: 'left',
            },
            markers: {
                size: 10
            },
        }

        const optionsCircle4 = {
            chart: {
                height: 300,
                type: 'pie',
                foreColor: '#999',
                locales:locale,
                defaultLocale: 'fr'
            },
            colors: ['#4a536b', '#e75874', '#008FFB'],
            series: [],
            labels: [],
            theme: {
                monochrome: {
                    enabled: false
                }
            },
            plotOptions: {
                radialBar: {
                    offsetY: -30
                }
            },
            legend: {
                show: true,
                horizontalAlign: 'center',
                fontSize: '14px',
                position: 'bottom',
                containerMargin: {
                    right: 0
                }
            },
            title: {
                text: 'Utilisateurs',
                style: {
                    fontSize: '18px'
                }
            },
        }

        const PolarOptions = {
            series: [],
            labels: [],
            chart: {
                type: 'polarArea',
                height: 250,
                foreColor: '#777',
                locales:locale,
                defaultLocale:'fr',
            },
            legend: {
                show: true,
                horizontalAlign: 'center',
                fontSize: '14px',
                position: 'bottom',
                containerMargin: {
                    right: 0
                }
            },
            title: {
                text:'Groupement par age',
                style: {
                    fontSize: '18px'
                }
            },
            stroke: {
                colors: ['#fff']
            },
            fill: {
                opacity: 0.8
            },
            responsive: [{
                breakpoint: 760,
                options: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        const polarChart = new ApexCharts(document.querySelector("#polar"), PolarOptions);
        polarChart.render();

        var chartCircle4 = new ApexCharts(document.querySelector('#radial'), optionsCircle4);
        chartCircle4.render();

        var chartBar = new ApexCharts(document.querySelector('#bar'), optionsBar);
        chartBar.render();

        var Donut= new ApexCharts(document.querySelector("#donut"), optionDonut);
        Donut.render();
        let spark1Chart=new ApexCharts(document.querySelector("#spark1"), spark1);
        spark1Chart.render();
        let spark2Chart=new ApexCharts(document.querySelector("#spark2"), spark2);
        spark2Chart.render();
        let spark3Chart=new ApexCharts(document.querySelector("#spark3"), spark3);
        spark3Chart.render();
        let spark4 = new ApexCharts(document.querySelector("#spark4"), spark4Opt);
        spark4.render();

        fetch("/admin/stats")
            .then(res => res.json())
            .then(time_series_Data => {
                time_series_Data=JSON.parse(time_series_Data);
                console.log(time_series_Data);
                spark4.updateSeries([{
                    data: time_series_Data.users_growth
                }]);
                spark1Chart.updateSeries([{
                    data: time_series_Data.comp_stats
                }]);
                spark2Chart.updateSeries([{
                    data: time_series_Data.rdv_stats
                }]);
                spark3Chart.updateSeries([{
                    data: time_series_Data.sensors_stats
                }]);
                chartBar.updateSeries([{
                    data: time_series_Data.users_growth
                }]);
                let gender_lab=[]
                let gender_series = []
                time_series_Data.users_gender.map(function(val){
                    gender_lab.push(val[0])
                    gender_series.push(val[1])
                })
                Donut.updateOptions({
                  series:gender_series,
                  labels:gender_lab
                })
                let role_s=[]
                let role_lab=[]
                time_series_Data.users_role.map(function(val){
                    role_lab.push(val[0])
                    role_s.push(val[1])
                })
                chartCircle4.updateOptions({
                    series:role_s,
                    labels:role_lab
                })

                let age_s=[]
                let age_lab=[]
                time_series_Data.users_age.map(function(val){
                    age_lab.push(val[1])
                    age_s.push(val[0])
                })
                polarChart.updateOptions({
                    series:age_s,
                    labels:age_lab
                })

        });

    });
})