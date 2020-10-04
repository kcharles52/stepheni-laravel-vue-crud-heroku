<template>
  <nav>
    <div class="container">
      <div class="row">
        <div class="six columns">
          <a href="/" class="application-title"><i class="material-icons">sms</i> Ld-Talk</a>
        </div>
        <div class="six columns">
          <ul v-if="!isLoggedIn">
            <li><router-link :to="{ name: 'Welcome' }"><i class="material-icons">lock_open</i> Login</router-link></li>
            <li><a href=""><i class="material-icons">local_cafe</i> Buy Me Coffee</a></li>
          </ul>
          <ul v-else>
            <li><router-link :to="{ name: 'Welcome' }"><i class="material-icons">home</i> Home</router-link></li>
            <li><a href=""><i class="material-icons">lock_open</i> Logout</a></li>
            <li><a href=""><i class="material-icons">local_cafe</i> Buy Me Coffee</a></li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
</template>

<script type="text/javascript">
  import axios from 'axios';

  export default {
    data() {
      return {
        isLoggedIn: false
      }
    },
    created() {
      let userSecret = localStorage.getItem('access-token');
      if (userSecret.trim() !== '') {
        this.isLoggedIn = true;
        return;
      }

      this.isLoggedIn = false;
    }
  }
</script>
