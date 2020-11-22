<template>
  <div class="sidebar-left">
    <div class="sidebar-body scroll-pane">
      <div class="side-nav">
        <v-collapse accordion>
          <v-collapse-item active-url="/admin/dashboard">
            <a slot="item-title" href="#">
              <i class="icon-fa icon-fa-dashboard"/>Dashboard
              <span class="icon-fa arrow icon-fa-fw"/>
            </a>

            <router-link to="/admin/dashboard/basic">
              Dashboard
            </router-link>
          </v-collapse-item>


          <v-collapse-item active-url="/admin/chart">
            <a slot="item-title" href="#">
              <i class="icon-fa icon-fa-bar-chart"/>Expense Management
              <span class="icon-fa arrow icon-fa-fw"/>
            </a>

            <router-link to="/admin/expense/categories">
              Expense Categories
            </router-link>

            <router-link to="/admin/expense/expenses">
              Expenses
            </router-link>
          </v-collapse-item>

          <v-collapse-item active-url="/admin/users">
            <a slot="item-title" href="#">
              <i class="icon-fa icon-fa-user"/>User Management
              <span class="icon-fa arrow icon-fa-fw"/>
            </a>

            <router-link to="/admin/users/roles">
              Roles
            </router-link>

            <router-link to="/admin/users/all">
              All Users
            </router-link>
          </v-collapse-item>

          <v-collapse-item active-url="/admin/settings">
            <router-link
              slot="item-title"
              to="/admin/settings">
              <i class="icon-fa icon-fa-cogs"/>Settings
            </router-link>
          </v-collapse-item>
        </v-collapse>
      </div>
    </div>
  </div>
</template>
