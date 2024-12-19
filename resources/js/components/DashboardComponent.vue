<template>
    <div class="container mt-5">
      <h1 class="text-center">Dashboard</h1>
  
      
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        tasks: [],
        task: { title: '', description: '' },
        userRole: ''
      };
    },
    methods: {
      fetchTasks() {
        axios.get('/api/tasks').then(response => this.tasks = response.data);
      },
      addTask() {
        axios.post('/api/tasks', this.task).then(() => this.fetchTasks());
      }
    },
    mounted() {
      axios.get('/api/user').then(response => this.userRole = response.data.role);
      this.fetchTasks();
    }
  }
  </script>
  