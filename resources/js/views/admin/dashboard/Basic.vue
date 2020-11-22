<template>
  <div
    id="dashboardPage"
    class="main-content">
    <div class="row">


      <div class="col-md-12 col-lg-6">
        <a class="dashbox dashbox-circle-progress" href="#">
          <span class="desc">
            Expense
          </span>
          <span class="title text-danger">
            $ 200
          </span>
          <div
            class="easy-pie-chart"
            data-percent="70"
            data-color="#f35a3d"
          >
            <span class="percent text-danger"/>
          </div>
        </a>
      </div>

    </div>

    <div class="row">
      <div class="col-lg-12 col-xl-6 mt-2">
        <div class="card">
          <div class="card-header">
            <div class="caption">
              <h6><i class="icon-fa icon-fa-credit-card text-primary"/> Summary</h6>
            </div>
            <div class="actions">
              <div
                class="btn-group"
                role="group">
                <v-dropdown :show-arrow="false">
                  <button
                    slot="activator"
                    type="button"
                    class="btn btn-sm btn-outline-primary dropdown-toggle">
                    Filters
                  </button>
                  <v-dropdown-item>
                    <a class="dropdown-item" href="#">
                      October
                    </a>
                  </v-dropdown-item>
                  <v-dropdown-item>
                    <a class="dropdown-item" href="#">
                      September
                    </a>
                  </v-dropdown-item>
                </v-dropdown>
              </div>
            </div>
          </div>
          <div class="card-body">
            <pie-chart
              :labels="['Revenue', 'Expense', 'Profit']"
              :values="[300, 50, 250]"
              :bg-colors="['#5BBFDE','#f35a3d','#4fc47f']"
              :hover-bg-colors="['#5BBFDE','#f35a3d','#4fc47f']"
            />
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script type="text/babel">
import { Tabs, Tab } from 'vue-tabs-component'
import LineChart from '../../../components/chartjs/LineChart.vue'
import BarChart from '../../../components/chartjs/BarChart.vue'
import PieChart from '../../../components/chartjs/PieChart.vue'

export default {
  components: {
    LineChart,
    BarChart,
    PieChart,
    Tabs,
    Tab
  },
  data () {
    return {
      header: 'header'
    }
  },
}
</script>
