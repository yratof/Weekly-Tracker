<?php

class visualise {

  static function chart_html() {
    echo '<div id="chart"></div>';
  }

  static function scripts() {
    ?>
    <script src="https://unpkg.com/frappe-charts@0.0.5/dist/frappe-charts.min.iife.js"></script>
    <script defer>

     // Javascript
      let data = {
        labels: [
          "January",
          "February",
          "March",
          "April",
          "May",
          "June",
          "July",
          "August",
          "September",
          "October",
          "November",
          "December"

        ],

        datasets: [
          {
            title: "Total hours", color: "light-green",
            values: [
              156, // 1
              147, // 2
              176, // 3
              156, // 4
              147, // 5
              176, // 6
              156, // 7
              147, // 8
              176, // 9
              156, // 10
              147, // 11
              176  // 12
            ]
          },
          {
            title: "Overtime", color: "red",
            values: [
              26, // 1
              27, // 2
              26, // 3
              26, // 4
              27, // 5
              26, // 6
              26, // 7
              27, // 8
              26, // 9
              26, // 10
              27, // 11
              26  // 12
            ]
          }
        ]
      };

      let chart = new Chart({
        parent:  "#chart", // or a DOM element
        title:   "Weekly hours worked",
        data:    data,
        type:   'bar', // or 'line', 'scatter', 'pie', 'percentage'
        height:  200,
        format_tooltip_x: d => (d + '').toUpperCase(),
        format_tooltip_y: d => d + ' hours'
      });

      chart.show_sums();
    </script>
  <?php
  }

}
