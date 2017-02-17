<script>
import LineChart from './LineChart.vue';

export default LineChart.extend({
  name: 'MonthlyChart',

  props: ['months'],

  mounted() {
    this.render({
      labels: this.months.map(month => month.id),

      datasets: [
        {
          label: 'Views',
          backgroundColor: "rgba(151,187,205,0.2)",
          borderColor: "rgba(151,187,205,1)",
          lineTension: 0,
          pointBackgroundColor: "#fff",
          pointBorderColor: "rgba(151,187,205,1)",
          pointHoverBackgroundColor: "#fff",
          pointHoverBorderColor: "rgba(151,187,205,1)",
          pointHoverRadius: 6,
          pointRadius: 5,
          data: this.months.map(month => month.total)
        },
      ]
    })
  }
})
</script>