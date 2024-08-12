// scripts.js
document.addEventListener('DOMContentLoaded', function() {
    // Simulate loading time
    setTimeout(function() {
      document.getElementById('loading-page').style.display = 'none';
      document.getElementById('main-content').style.display = 'block';
    }, 3000); // 3 seconds
  
    document.getElementById('fetch-data-button').addEventListener('click', function() {
      const dataLoader = document.getElementById('data-loader');
      const dataContainer = document.getElementById('data-container');
  
      // Show loader
      dataLoader.style.display = 'block';
  
      // Simulate data fetching
      setTimeout(function() {
        dataLoader.style.display = 'none';
        dataContainer.innerHTML = 'Data fetched successfully!';
      }, 2000); // 2 seconds
    });
  });
  