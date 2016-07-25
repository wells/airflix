<template>
  <div>
    <canvas v-el:canvas></canvas>
  </div>
</template>

<script>
import Vue from 'vue';
import Chart from 'chart.js';

export default Vue.extend({
  methods: {
    render(data) {
      const options = {
        type: 'line',
        data: data,
        options: {
          legend: { 
            display: false 
          },
          scales: {
            yAxes: [{
              ticks: { 
                min: 0,
                stepSize: 1,
                beginAtZero: true, 
                fontColor: 'rgba(255,255,255,0.5)' 
              },
              gridLines: { 
                color: 'rgba(255,255,255,0.1)', 
                zeroLineColor: 'rgba(255,255,255,0.2)' 
              }
            }],
            xAxes: [{
              ticks: { 
                fontColor: 'rgba(255,255,255,0.5)' 
              },
              gridLines: { 
                color: 'rgba(255,255,255,0.1)', 
                zeroLineColor: 'rgba(255,255,255,0.2)' 
              }
            }]
          }
        }
      }

      const chart = new Chart(
        this.$els.canvas.getContext('2d'), 
        options
      )
    }
  }
})
</script>