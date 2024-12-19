<template>
    <div class="container mt-5">
      <h2>Login</h2>
      <form @submit.prevent="submitLogin">
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" id="email" v-model="email" class="form-control" required />
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" id="password" v-model="password" class="form-control" required />
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
      </form>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        email: '',
        password: ''
      };
    },
    methods: {
      submitLogin() {
        // Handle login logic here (e.g., send credentials to the server)
        console.log('Login Attempt:', this.email, this.password);
      }
    }
  };
  </script>
  
  <style scoped>
  .container {
    max-width: 400px;
    margin-top: 100px;
  }
  </style>
  