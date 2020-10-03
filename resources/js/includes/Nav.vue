<template>
  <nav>
    <div class="container">
      <div class="row">
        <div class="six columns">
          <a href="" class="application-title"><i class="material-icons">sms</i> Ld-Talk</a>
        </div>
        <div class="six columns">
          <ul>
            <li><router-link :to="{ name: 'Welcome' }"><i class="material-icons">lock_open</i> Login</router-link></li>
            <li><a href=""><i class="material-icons">local_cafe</i> Buy Me Coffee</a></li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
</template>

<script type="text/javascript">
  export default {

  }
</script>
