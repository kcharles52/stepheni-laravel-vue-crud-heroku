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
              <form method="POST">
                  <div class="form-group">
                    <label for="email" class="col-md-4 col-form-label text-md-right">Email Address</label>
                    <div class="col-md-6">
                      <input id="email" type="email" class="form-control" name="email" required autocomplete="email" autofocus>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password">
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

          if (user.status == 201) {
            swal('Account Created', 'Congratulations. Your ld-talk account has been created Successfully.', 'success');

            localStorage.setItem('access-token', user.token);
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
