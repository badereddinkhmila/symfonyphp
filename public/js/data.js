window.addEventListener('load', () => {
    let chartConfig = {
      type: 'line',
      globals: {
        fontFamily: 'Lucida Sans Unicode'
      },
      plotarea: {
        margin: '50px 40px 40px 80px'
      },
      scaleX: {
        item: {
          fontSize: '10px'
        },
        transform: {
          type: 'date'
        },
        zooming: true
      },
      scaleY: {
        autoFit: true,
        guide: {
          lineStyle: 'solid'
        },
        item: {
          fontSize: '10px'
        },
        label: {
          text: 'VOLUMES'
        },
        minValue: 'auto',
        short: true
      },
      preview: {
        adjustLayout: true,
        borderWidth: '1px',
        handle: {
          height: '20px',
          lineWidth: '0px'
        }
      },
      noData: {
        text: 'No data found',
        backgroundColor: '#efefef'
      },
      // if fetching data remotely define an empty series
      series: [{}]
    };
    // render the chart right away
    zingchart.render({
      id: 'chartContainer',
      data: chartConfig
    });
    // ONLY ONCE we have loaded the zoom-button module
    zingchart.loadModules('zoom-buttons', () => {
      // fetch the data remotely
      fetch('https://cdn.zingchart.com/datasets/timeseries-sample-data-2019.json')
        .then(res => res.json())
        .then(timeseriesData => {
          // assign data
          chartConfig.series[0].values = timeseriesData.values;
          // destroy the chart since we have to render the
          // chart with a module. if there is no module,
          // just use set data like the catch statement
          zingchart.exec('myChart', 'destroy');
          // render chart with width and height to
          // fill the parent container CSS dimensions
          zingchart.render({
            id: 'chartContainer',
            data: chartConfig,
            height: '100%',
            width: '100%',
            modules: 'zoom-buttons'
          });
        })
        .catch(e => {
          // if error, render blank chart
          console.error('--- error fetching data from: https://cdn.zingchart.com/datasets/timeseries-sample-data.json ---');
          chartConfig.title = {};
          chartConfig.title.text = 'Error Fetching https://cdn.zingchart.com/datasets/timeseries-sample-data.json';
          // just exec setdata api method since we don't need to render the zoom modules
          // https://www.zingchart.com/docs/api/methods/
          zingchart.exec('chartContainer', 'setdata', {
            data: chartConfig
          });
        });
  });
});