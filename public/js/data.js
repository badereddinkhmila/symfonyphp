window.addEventListener('load', () => {
    fetch('https://cdn.zingchart.com/datasets/timeseries-sample-data-2019.json')
        .then(res => res.json())
        .then(timeseriesData => {
            // assign data
            var data = timeseriesData.values;

            var options = {
                series: [{
                    data: data
                }],
                chart: {
                    id: 'chart2',
                    type: 'line',
                    height: 370,
                    zoom: {
                        type: 'x',
                        enabled: true,
                        autoScaleYaxis: true
                    },
                    toolbar: {
                        autoSelected: 'zoom',
                        show: true
                    }
                },

                theme: {
                    monochrome: {
                        enabled: true,
                        color: '#f9a3a4',
                        shadeTo: 'light',
                        shadeIntensity: 0.3
                    },
                    palette: 'palette4' // upto palette10
                },
                animations: {
                    enabled: true,
                    easing: 'linear',
                    speed: 2000,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 350
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                dataLabels: {
                    enabled: false
                },

                fill: {
                    opacity: 1,
                },
                markers: {
                    size: 0
                },
                xaxis: {
                    type: 'datetime'
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart-line2"), options);
            chart.render();

            var jaugeoption = {
                series: [67],
                chart: {
                    height: 350,
                    type: 'radialBar',
                    offsetY: -10
                },
                plotOptions: {
                    radialBar: {
                        startAngle: -135,
                        endAngle: 135,
                        dataLabels: {
                            name: {
                                fontSize: '16px',
                                color: undefined,
                                offsetY: 120
                            },
                            value: {
                                offsetY: 76,
                                fontSize: '22px',
                                color: undefined,
                                formatter: function(val) {
                                    return val + "%";
                                }
                            }
                        }
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        shadeIntensity: 0.15,
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 50, 65, 91]
                    },
                },
                responsive: [{
                    breakpoint: undefined,
                    options: {},
                }],

                animations: {
                    enabled: true,
                    easing: 'easein',
                    speed: 2000,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 350
                    }
                },
                stroke: {
                    dashArray: 4
                },
                labels: ['Median Ratio'],
            };

            var jauge = new ApexCharts(document.querySelector("#chart"), jaugeoption);
            jauge.render();





            var optionsLine = {
                series: [{
                    data: data
                }],
                responsive: [{
                    breakpoint: undefined,
                    options: {},
                }],
                chart: {
                    id: 'chart1',
                    height: 100,
                    type: 'area',
                    brush: {
                        target: 'chart2',
                        enabled: true
                    },
                    selection: {
                        enabled: true,
                        xaxis: {
                            //min: new Date('19 Jun 2017').getTime(),
                            //max: new Date().getTime()
                        }
                    },
                },
                colors: ['#008FFB'],
                grid: {
                    borderColor: '#e7e7e7',
                    row: {
                        colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                    },
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        opacityFrom: 0.91,
                        opacityTo: 0.1,
                    }
                },
                xaxis: {
                    type: 'datetime',
                    tooltip: {
                        enabled: false
                    }
                },
                yaxis: {
                    tickAmount: 2
                }
            };

            var brushLine = new ApexCharts(document.querySelector("#chart-line"), optionsLine);
            brushLine.render();
        });
});