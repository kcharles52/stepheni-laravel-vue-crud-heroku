<template>
  <div>
    <header>
      <Nav></Nav>
    </header>
    <section class="app_description">
      <div class="container">
        <div class="row">
          <div class="six columns login-column">
            <h5>Login to your <b>ld-talk</b> account.</h5>
            <hr>
            <div class="card-body">
              <form method="POST" @submit.prevent="accountLogin()">
                  <div class="form-group">
                    <label for="login-email" class="col-md-4 col-form-label text-md-right">Email Address</label>
                    <div class="col-md-6">
                      <input id="login-email" type="email" class="form-control" name="email" v-model="login.email" required autocomplete="email" autofocus>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="login-password" class="col-md-4 col-form-label text-md-right">Password</label>
                    <div class="col-md-6">
                        <input id="login-password" v-model="login.password" type="password" class="form-control" name="password" required autocomplete="current-password">
                    </div>
                  </div>
                  <div class="form-group mb-0">
                    <div class="col-md-8 offset-md-4">
                      <button type="submit" class="btn btn-primary">
                        Login
                      </button>
                    </div>
                  </div>
              </form>
            </div>
          </div>
          <div class="six columns signup-column">
            <h5>Create an <b>ld-talk</b> account. It's <b>free.</b></h5>
            <hr>
            <form method="POST" @submit.prevent="createAccount()">
              <div class="form-group">
                <label for="email" class="col-md-4 col-form-label text-md-right">Name</label>
                <div class="col-md-6">
                  <input type="text" class="form-control" name="name" required autocomplete="name" autofocus v-model="register.name">
                </div>
              </div>
                <div class="form-group">
                  <label for="email" class="col-md-4 col-form-label text-md-right">Email Address</label>
                  <div class="col-md-6">
                    <input id="email" type="email" class="form-control" name="email" required autocomplete="email" autofocus v-model="register.email">
                  </div>
                </div>
                <div class="form-group">
                  <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                  <div class="col-md-6">
                      <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password" v-model="register.password">
                  </div>
                </div>
                <div class="form-group mb-0">
                  <div class="col-md-8 offset-md-4">
                      <button type="submit" class="btn btn-primary">
                        Signup
                      </button>
                  </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script type="text/javascript">
  import Nav from '../includes/Nav';

  import axios from 'axios';
  import swal from 'sweetalert';

  export default {
    components: {
      Nav
    },
    methods: {
      async createAccount () {
        if (this.register.name.trim() == '') {
          swal('Name Error', 'Sorry, This Field Cannot Be Empty.', 'error');
          return;
        }

        if (this.register.email.trim() == '') {
          swal('Email Error', 'Sorry, This Field Cannot Be Empty', 'error');
          return;
        }

        if (this.register.password.length < 7) {
          swal('Password Error', 'Please, Use A Stronger Password', 'error');
          return;
        }

        // Make an xhr request to create the account....
        try {
          let user = await axios.post(this.$baseUrl + 'register', this.register, {
            headers: {
              Accept: 'application/json',
              'Content-Type': 'application/json',
              'Cache-Control': 'no-cache',
            }
          });

          // check the http status code....
          if (user.status == 201) {
            swal('Account Created', 'Congratulations. Your ld-talk account has been created Successfully.', 'success');

            // clear the form fields..........
            this.register.name = '';
            this.register.email = '';
            this.register.password = '';

            // set some storage and all.....
            localStorage.setItem('access-token', user.data.token);
            localStorage.setItem('user-id', user.data.data.id);

            setTimeout(() => {
              this.$router.push({
                name: 'Dashboard'
              });
            }, 1000);
            return;
          }
        } catch (e) {
          let errorData = e.response.data;
          if (errorData.status == 400) {
            for (const error in errorData.errors) {
              swal(
                `${error} Error: `,
                errorData.errors[error][0],
                'error'
              );
            }
            return;
          }

          swal('Registration Failed', errorData.message, 'error');
          return;
        }
      },
      async accountLogin() {
        // Validate credentials....
        if (this.login.email.trim() == '') {
          swal('Email Error', 'Sorry, The Email Field Cannot Be Empty.', 'errors');
          return;
        }

        if (this.login.password.length < 7) {
          swal('Invalid Credentials', 'Please, Check The Email Or Password And Try Again.', 'errors');
          return;
        }

        // make an xhr request...........
        try {
          let user = await axios.post(this.$baseUrl + 'login', this.login, {
            headers: {
              Accept: 'application/json',
              'Content-Type': 'application/json',
              'Cache-Control': 'no-cache'
            }
          });

          // check the http status code...
          if (user.status == 200) {
            swal('Login Successfull', `Welcome Back ${user.data.data.name}.`, 'success');

            // clear the form fields..........
            this.login.email = '';
            this.login.password = '';

            // set some storage and all.....
            localStorage.setItem('access-token', user.data.token);
            localStorage.setItem('user-id', user.data.data.id);
            setTimeout(() => {
              this.$router.push({
                name: 'Dashboard'
              });
            }, 1000);
            return;
          }
        } catch (e) {
          let errorData = e.response.data;
          if (errorData.status == 400 || errorData.status == 401) {
            for (const error in errorData.errors) {
              swal(
                `${error} Error: `,
                errorData.errors[error][0],
                'error'
              );
            }
            return;
          }

          swal('Login Failed', errorData.message, 'error');
          return;
        }
      }
    },
    async created() {
      try {
        let userSecret = localStorage.getItem('access-token');

        // check if the user access token is set.....
        if (userSecret.trim() !== '') {
          let user = await axios.get(this.$baseUrl + 'user',  {
            headers: {
              Accept: 'application/json',
              'Authorization': 'Bearer ' + userSecret,
              'Cache-Control': 'no-cache'
            }
          });

          // check and redirect the user...
          if (user.status == 200) {
            setTimeout(() => {
              this.$router.push({
                name: 'Dashboard'
              });
            }, 1000);
          }
        }
      } catch (e) {

      }
    },
    data() {
      return {
        register: {
          name: '',
          email: '',
          password: '',
        },
        login: {
          email: '',
          password: ''
        }
      }
    }
  }
</script>

<style scoped>
  .form-control {
    width: 100% !important;
  }
  .login-column {
    padding-right: 100px;
    border-right: 1px solid grey;
  }
</style>
